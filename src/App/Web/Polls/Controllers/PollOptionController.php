<?php

namespace App\Web\Polls\Controllers;

use App\Web\Polls\Requests\StorePollOptionRequest;
use Domain\Events\Models\Event;
use Domain\Polls\Models\Poll;
use Domain\Polls\Models\PollOption;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Support\Controllers\Controller;

class PollOptionController extends Controller
{
    public function store(Event $event, Poll $poll, StorePollOptionRequest $request): RedirectResponse
    {
        $this->authorize('create', [PollOption::class, $poll]);

        $text = $request->validated()['text'];

        $duplicate = $poll->options()
            ->whereRaw('LOWER(text) = ?', [Str::lower($text)])
            ->exists();

        if ($duplicate) {
            throw ValidationException::withMessages([
                'text' => 'This option already exists.',
            ]);
        }

        $poll->options()->create([
            'text' => $text,
            'created_by_user_id' => auth()->id(),
        ]);

        return redirect()->back();
    }

    public function destroy(Event $event, Poll $poll, PollOption $option): RedirectResponse
    {
        $this->authorize('delete', $option);

        $option->delete();

        return redirect()->back();
    }
}
