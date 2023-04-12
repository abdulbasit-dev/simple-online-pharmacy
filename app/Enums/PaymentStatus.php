<?php

namespace App\Enums;

enum PaymentStatus: int
{
    case UNPAID  = 0;
    case PAID    = 1;
    case PENDING = 2;
    case CANCELLED = 3;

    public function isPaid(): bool
    {
        return $this === self::PAID;
    }

    public function isUnpaid(): bool
    {
        return $this === self::UNPAID;
    }

    public function isPending(): bool
    {
        return $this === self::PENDING;
    }

    public function isCancelled(): bool
    {
        return $this === self::CANCELLED;
    }

    public function getLabelText(): string
    {
        return match ($this) {
            self::PAID      => 'Paid',
            self::UNPAID    => 'Unpaid',
            self::PENDING   => 'Pending',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function getLabelColor(): string
    {
        return match ($this) {
            self::PAID    => "badge-soft-success",
            self::UNPAID  => "badge-soft-danger",
            self::PENDING => "badge-soft-warning",
            self::CANCELLED => "badge-soft-danger",
        };
    }

    public function getLabelHtml(): string
    {
        return "<span class='badge badge-pill font-size-13 p-2 {$this->getLabelColor()}'>{$this->getLabelText()}</span>";
    }
}
