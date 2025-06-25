<?php

namespace Domain\Events\Actions;

use Domain\Addresses\Actions\CreateAddressAction;
use Domain\Events\DataTransferObjects\GuestEventCreateData;
use Domain\Events\Models\Event;
use Domain\GuestUsers\Actions\CreateOrFindGuestUserAction;
use Illuminate\Support\Arr;
use Support\Actions\AttachMediaToModelAction;
use Support\Services\DateAdjustmentService;

class GuestEventCreateAction
{
    public function __construct(
        protected CreateAddressAction $createAddressAction,
        protected CreateOrFindGuestUserAction $createOrFindGuestUserAction,
        protected AttachMediaToModelAction $attachMediaToModelAction,
        protected DateAdjustmentService $dateAdjustmentService
    ) {
    }

    public function execute(GuestEventCreateData $guestEventCreateData): Event
    {
        $guestUser = $this->createOrFindGuestUserAction->execute($guestEventCreateData->email, $guestEventCreateData->name);

        $address = null;
        if ($guestEventCreateData->location) {
            $address = $this->createAddressAction->execute($guestEventCreateData->location);
        }

        $guestEventCreateData->end_date_time = $this->dateAdjustmentService->adjustEndDate(
            $guestEventCreateData->start_date_time,
            $guestEventCreateData->end_date_time
        );

        $event = Event::create(
            Arr::except($guestEventCreateData->toArray(), ['location', 'image', 'email', 'name'])
        );

        $event->guestUser()->associate($guestUser);

        if ($address) {
            $event->address()->save($address);
        }
        $event->save();

        if ($guestEventCreateData->image) {
            $this->attachMediaToModelAction->execute([$guestEventCreateData->image], $event, '-banner');
        }

        return $event;
    }
}
