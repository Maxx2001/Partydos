<?php

namespace Support;

use Support\Traits\Creatable;

class Notification
{
    const SESSION_NAME = 'notification';
    use Creatable;

    /**
     * @param string                    $content
     * @param \Support\NotificationType $type
     */
    public function __construct(public string $content, public NotificationType $type = NotificationType::success)
    {
    }

    public function send(): void
    {
        session()->flash(static::SESSION_NAME, $this);
    }
}
