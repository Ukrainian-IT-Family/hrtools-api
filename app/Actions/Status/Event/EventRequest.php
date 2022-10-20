<?php

declare(strict_types=1);

namespace App\Actions\Status\Event;

class EventRequest
{
    private string $message;

    public function __construct(string $message)
    {
        $this->message = $message;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
