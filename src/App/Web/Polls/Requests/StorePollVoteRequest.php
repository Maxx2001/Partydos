<?php

namespace App\Web\Polls\Requests;

use Domain\Polls\Models\Poll;
use Illuminate\Foundation\Http\FormRequest;

class StorePollVoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        /** @var Poll $poll */
        $poll = $this->route('poll');

        if ($poll && $poll->vote_mode === 'multiple') {
            return [
                'option_ids' => ['required', 'array', 'min:1'],
                'option_ids.*' => ['integer'],
            ];
        }

        return [
            'option_id' => ['required', 'integer'],
        ];
    }
}
