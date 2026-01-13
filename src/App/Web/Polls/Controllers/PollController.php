<?php

namespace App\Web\Polls\Controllers;

use App\Web\Polls\Requests\StorePollRequest;
use App\Web\Polls\Requests\UpdatePollRequest;
use Domain\Events\DataTransferObjects\EventEntity;
use Domain\Events\Models\Event;
use Domain\Events\Services\EventParticipantService;
use Domain\Polls\Models\Poll;
use Domain\Polls\Models\PollVote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;
use Support\Controllers\Controller;

class PollController extends Controller
{
    public function index(Event $event): Response
    {
        $this->authorize('viewAny', [Poll::class, $event]);

        $polls = Poll::query()
            ->where('event_id', $event->id)
            ->withCount(['options', 'votes'])
            ->latest()
            ->get()
            ->map(fn (Poll $poll) => [
                'id' => $poll->id,
                'question' => $poll->question,
                'description' => $poll->description,
                'status' => $poll->status,
                'voteMode' => $poll->vote_mode,
                'guestsCanAddOptions' => $poll->guests_can_add_options,
                'optionsCount' => $poll->options_count,
                'votesCount' => $poll->votes_count,
            ]);

        $participantService = new EventParticipantService();

        return Inertia::render('Polls/Index', [
            'event' => EventEntity::from($event),
            'polls' => $polls,
            'canManage' => $participantService->isHost($event, auth()->user()),
        ]);
    }

    public function create(Event $event): Response
    {
        $this->authorize('create', [Poll::class, $event]);

        return Inertia::render('Polls/Create', [
            'event' => EventEntity::from($event),
        ]);
    }

    public function store(Event $event, StorePollRequest $request): RedirectResponse
    {
        $this->authorize('create', [Poll::class, $event]);

        $validated = $request->validated();
        $options = collect($validated['options'])
            ->map(fn (string $option) => trim($option))
            ->filter();

        if ($options->count() < 2) {
            throw ValidationException::withMessages([
                'options' => 'At least two options are required.',
            ]);
        }

        $lowercaseOptions = $options->map(fn (string $option) => Str::lower($option));

        if ($lowercaseOptions->unique()->count() !== $lowercaseOptions->count()) {
            throw ValidationException::withMessages([
                'options' => 'Options must be unique.',
            ]);
        }

        $poll = DB::transaction(function () use ($event, $validated, $options) {
            $poll = Poll::create([
                'event_id' => $event->id,
                'question' => $validated['question'],
                'description' => $validated['description'] ?? null,
                'vote_mode' => $validated['vote_mode'],
                'guests_can_add_options' => $validated['guests_can_add_options'] ?? false,
                'created_by_user_id' => auth()->id(),
            ]);

            $poll->options()->createMany(
                $options->map(fn (string $option) => [
                    'text' => $option,
                    'created_by_user_id' => auth()->id(),
                ])->values()->all()
            );

            return $poll;
        });

        return redirect()->route('events.polls.show', [$event, $poll]);
    }

    public function show(Event $event, Poll $poll): Response
    {
        $this->authorize('view', $poll);

        $user = auth()->user();
        $participantService = new EventParticipantService();
        $isHost = $participantService->isHost($event, $user);

        $poll->load([
            'options' => function ($query) {
                $query->withCount('votes')
                    ->with('createdBy');
            },
        ]);

        if ($isHost) {
            $poll->load('options.votes.user');
        }

        $selectedOptionIds = PollVote::query()
            ->where('poll_id', $poll->id)
            ->where('user_id', $user->id)
            ->pluck('poll_option_id')
            ->values();

        $options = $poll->options->map(function ($option) use ($isHost) {
            return [
                'id' => $option->id,
                'text' => $option->text,
                'votesCount' => $option->votes_count,
                'createdBy' => $option->createdBy ? [
                    'id' => $option->createdBy->id,
                    'name' => $option->createdBy->name,
                    'email' => $option->createdBy->email,
                ] : null,
                'voters' => $isHost
                    ? $option->votes->map(fn ($vote) => [
                        'id' => $vote->user?->id,
                        'name' => $vote->user?->name,
                        'email' => $vote->user?->email,
                    ])->values()
                    : [],
            ];
        });

        return Inertia::render('Polls/Show', [
            'event' => EventEntity::from($event),
            'poll' => [
                'id' => $poll->id,
                'question' => $poll->question,
                'description' => $poll->description,
                'status' => $poll->status,
                'voteMode' => $poll->vote_mode,
                'guestsCanAddOptions' => $poll->guests_can_add_options,
            ],
            'options' => $options,
            'selectedOptionIds' => $selectedOptionIds,
            'canManage' => $isHost,
            'canVote' => $participantService->isParticipant($event, $user) && $poll->status === 'open',
            'canAddOption' => $poll->status === 'open' && ($poll->guests_can_add_options || $isHost),
        ]);
    }

    public function update(Event $event, Poll $poll, UpdatePollRequest $request): RedirectResponse
    {
        $this->authorize('update', $poll);

        $poll->update($request->validated());

        return redirect()->back();
    }

    public function close(Event $event, Poll $poll): RedirectResponse
    {
        $this->authorize('close', $poll);

        $poll->update(['status' => 'closed']);

        return redirect()->back();
    }

    public function reopen(Event $event, Poll $poll): RedirectResponse
    {
        $this->authorize('reopen', $poll);

        $poll->update(['status' => 'open']);

        return redirect()->back();
    }

    public function destroy(Event $event, Poll $poll): RedirectResponse
    {
        $this->authorize('delete', $poll);

        $poll->delete();

        return redirect()->route('events.polls.index', $event);
    }
}
