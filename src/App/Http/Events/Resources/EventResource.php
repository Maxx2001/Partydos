<?php

namespace App\Http\Events\Resources;

use App\Http\GuestUsers\Resources\GuestUserResource;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Support\Resources\ParticipantsResource;
use function PHPUnit\Framework\assertNotEmpty;

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
            'startDateTime'       => $this->start_date_time,
            'isoStartDateTime'    => Carbon::parse($this->start_date_time)->setTimezone('UTC')->format('Ymd\THisO'),
            'endDateTime'         => $this->end_date_time,
            'isoEndDateTime'      => ! $this->end_date_time ? null : Carbon::parse($this->end_date_time)
                ->setTimezone('UTC')
                ->format('Ymd\THisO'),
            'status'              => $this->status,
            'participants'        => ParticipantsResource::collection($this->whenLoaded('guestUsers')),
            'shareLink'           => $this->getShareLinkAttribute(),
            'eventOwner'          => $this->getEventOwner(),     
            'createdAt'           => $this->created_at,
            'updatedAt'           => $this->updated_at,
        ];
    }

    private function getEventOwner(): GuestUserResource
    {
        if (!empty($this->attributes(['guestUser'])->data)){
            return new GuestUserResource($this->whenLoaded('guestUser'));
        }

        return new GuestUserResource($this->whenLoaded('user'));
    }
}
