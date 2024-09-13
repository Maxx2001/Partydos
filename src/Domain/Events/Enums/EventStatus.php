<?php

namespace Domain\Events\Enums;

enum EventStatus
{
    case PENDING;

    case MAYBE;
    case ACCEPTED;
    case DECLINED;


    public function label(): string
    {
        return match ($this) {
            EventStatus::PENDING  => 'pending',
            EventStatus::MAYBE    => 'maybe',
            EventStatus::ACCEPTED => 'accepted',
            EventStatus::DECLINED => 'declined',
        };
    }
}
