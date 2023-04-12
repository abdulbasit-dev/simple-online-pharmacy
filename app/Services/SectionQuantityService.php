<?php

namespace App\Services;

use App\Models\Section;
use App\Models\Ticket;

class SectionQuantityService
{
    //DESC: Update product stock while ordering and changing order status (cancel/accept)

    // while creating order
    public function decrementSectionQuantity($sectionId, $qty, $ticketType)
    {
        $section = Section::find($sectionId);
        if ($ticketType == "MATCH_TICKET") {
            $section->sold_match_ticket_qty += $qty;
        } else {
            $section->sold_season_ticket_qty += $qty;
        }

        $section->remain_qty = $section->quantity - $section->sold_match_ticket_qty - $section->sold_season_ticket_qty;

        if ($section->remain_qty < 0) {
            throw new \Exception("The remain quantity is less than 0");
        }
        $section->save();
    }

    // while canceling order
    public function incrementSectionQuantity($sectionId, $qty, $ticketType)
    {
        // prevent incrementing quantity to negative number
        $section = Section::find($sectionId);
        if ($ticketType == "MATCH_TICKET") {
            $section->sold_match_ticket_qty -= $qty;
        } else {
            $section->sold_season_ticket_qty -= $qty;
        }

        $section->remain_qty = $section->quantity - $section->sold_match_ticket_qty - $section->sold_season_ticket_qty;

        if ($section->remain_qty < 0) {
            throw new \Exception("The remain quantity is less than 0");
        }

        $section->save();
    }

    public function checkQuantity($sectionId, $qty)
    {
        $section = Section::find($sectionId);
        if ($section->remain_qty < $qty) {
            return false;
        }
        return true;
    }

    public function getRemainQuantity($sectionId)
    {
        $section = Section::find($sectionId);
        return $section->remain_qty;
    }
}
