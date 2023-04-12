<?php

namespace App\Enums;

enum TicketType: int
{
    case SEASON_TICKET = 0;
    case MATCH_TICKET = 1;

    public function isSeasonTicket(): bool
    {
        return $this === self::SEASON_TICKET;
    }

    public function isMatchTicket(): bool
    {
        return $this === self::MATCH_TICKET;
    }

    public function getLabelText(): string
    {
        return match ($this) {
            self::SEASON_TICKET => 'Season Ticket',
            self::MATCH_TICKET  => 'Match Ticket',
        };
    }

    public static function getEnumValue(string $name): int
    {
        $name = strtoupper($name);
        return match ($name) {
            'SEASON_TICKET' => 0,
            'MATCH_TICKET'  => 1,
        };
    }

    public static function getEnumText(int $value): string
    {
        return match ($value) {
            0 => 'SEASON_TICKET',
            1 => 'MATCH_TICKET',
        };
    }

    public function getLabelColor(): string
    {
        return match ($this) {
            self::SEASON_TICKET => "badge-soft-info",
            self::MATCH_TICKET  => "badge-soft-warning",
        };
    }

    public function getLabelHtml(): string
    {
        return "<span class='badge badge-pill font-size-13 p-2 {$this->getLabelColor()}'>{$this->getLabelText()}</span>";
    }
}
