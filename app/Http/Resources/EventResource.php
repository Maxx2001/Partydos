<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'               => $this->id,
            'uniqueIdentifier' => $this->unique_identifier,
            'share_link'       => $this->getShareLinkAttribute(),
            'title'            => $this->title,
            'description'      => $this->description,
            'location'         => $this->location,
            'startDateTime'    => $this->start_date_time,
            'endDateTime'      => $this->end_date_time,
            'status'           => $this->status,
            'participants'     => ParticipantsResource::collection($this->whenLoaded('guestUsers')),
            'createdAt'        => $this->created_at,
            'updatedAt'        => $this->updated_at,
        ];
    }
}
