<?php

namespace App\Enums;

enum OrderStatus: int
{
    case PENDING  = 0;
    case ACCEPTED = 1;
    case CANCELED = 2;


    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isAccepted()
    {
        return $this === self::ACCEPTED;
    }

    public function isCanceled()
    {
        return $this === self::CANCELED;
    }

    public function getLabelText(): string
    {
        return match ($this) {
            self::PENDING  => 'Pending',
            self::ACCEPTED => 'Accepted',
            self::CANCELED => 'Canceled',
        };
    }


    public function getLabelColor(): string
    {
        return match ($this) {
            self::PENDING  => "bg-red-900",
            self::ACCEPTED => "bg-green-500",
            self::CANCELED => "bg-red-500",
        };
    }

    public function getLabelHtml(): string
    {
        return "<span class='badge badge-pill font-size-13 p-2 {$this->getLabelColor()}'>{$this->getLabelText()}</span>";
    }
}
