<?php

namespace App\Web\Polls\Controllers;

use App\Web\Polls\Requests\StorePollVoteRequest;
use Domain\Events\Models\Event;
use Domain\Polls\Models\Poll;
use Domain\Polls\Models\PollVote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Collection;
use Illuminate\Validation\ValidationException;
use Support\Controllers\Controller;

class PollVoteController extends Controller
{
    public function store(Event $event, Poll $poll, StorePollVoteRequest $request): RedirectResponse
    {
        $this->authorize('vote', $poll);

        $userId = auth()->id();

        if ($poll->vote_mode === 'single') {
            $optionId = (int) $request->validated()['option_id'];

            $this->ensureOptionsBelongToPoll($poll, collect([$optionId]), 'option_id');

            PollVote::query()
                ->where('poll_id', $poll->id)
                ->where('user_id', $userId)
                ->delete();

            PollVote::create([
                'poll_id' => $poll->id,
                'poll_option_id' => $optionId,
                'user_id' => $userId,
            ]);

            return redirect()->back();
        }

        $optionIds = collect($request->validated()['option_ids'] ?? [])
            ->map(fn ($id) => (int) $id)
            ->filter()
            ->unique()
            ->values();

        $this->ensureOptionsBelongToPoll($poll, $optionIds, 'option_ids');

        PollVote::query()
            ->where('poll_id', $poll->id)
            ->where('user_id', $userId)
            ->whereNotIn('poll_option_id', $optionIds)
            ->delete();

        $existing = PollVote::query()
            ->where('poll_id', $poll->id)
            ->where('user_id', $userId)
            ->whereIn('poll_option_id', $optionIds)
            ->pluck('poll_option_id');

        $toInsert = $optionIds->diff($existing);

        $toInsert->each(function ($optionId) use ($poll, $userId) {
            PollVote::create([
                'poll_id' => $poll->id,
                'poll_option_id' => $optionId,
                'user_id' => $userId,
            ]);
        });

        return redirect()->back();
    }

    private function ensureOptionsBelongToPoll(Poll $poll, Collection $optionIds, string $errorKey): void
    {
        $validOptionIds = $poll->options()->whereIn('id', $optionIds)->pluck('id');

        if ($validOptionIds->count() !== $optionIds->count()) {
            throw ValidationException::withMessages([
                $errorKey => 'Invalid option selection.',
            ]);
        }
    }
}
