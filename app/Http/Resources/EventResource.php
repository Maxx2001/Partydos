<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id'                  => $this->id,
            'uniqueIdentifier'    => $this->unique_identifier,
            'title'               => $this->title,
            'description'         => $this->description,
            'location'            => $this->location,
            'endDateTime'         => $this->end_date_time,
            'status'              => $this->status,
            'participants'        => ParticipantsResource::collection($this->whenLoaded('guestUsers')),
            'shareLink'           => $this->getShareLinkAttribute(),
            'guestUserEventOwner' => new GuestUserResource($this->whenLoaded('guestUser')),
            'createdAt'           => $this->created_at,
            'updatedAt'           => $this->updated_at,
        ];
    }
}
