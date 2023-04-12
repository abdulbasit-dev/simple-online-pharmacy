<?php

namespace App\Enums;

enum OrderEmailStatus: int
{
    case UNSENT  = 0;
    case SENT    = 1;

    public function isUnsent(): bool
    {
        return $this === self::UNSENT;
    }

    public function isSent(): bool
    {
        return $this === self::SENT;
    }

    public function getLabelText(): string
    {
        return match ($this) {
            self::SENT      => 'Sent',
            self::UNSENT    => 'Unsent',
        };
    }

    public function getLabelColor(): string
    {
        return match ($this) {
            self::SENT   => "badge-soft-success",
            self::UNSENT => "badge-soft-danger",
        };
    }

    public function getLabelHtml(): string
    {
        return "<span class='badge badge-pill font-size-13 p-2 {$this->getLabelColor()}'>{$this->getLabelText()}</span>";
    }
}
