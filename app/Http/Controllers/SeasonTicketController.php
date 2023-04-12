<?php

namespace App\Http\Controllers;

use App\Enums\TicketType;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\CheckoutRequest;
use App\Models\AgeGroup;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Section;
use App\Models\Supporter;
use App\Models\Ticket;
use App\Services\SectionQuantityService;
use Illuminate\Http\Response;


class SeasonTicketController extends Controller
{
    protected $ticketType;
    protected $sectionQuantityService;

    public function __construct(SectionQuantityService $sectionQuantityService)
    {
        $this->ticketType = TicketType::getEnumValue('SEASON_TICKET');
        $this->sectionQuantityService = $sectionQuantityService;
    }

    public function seasonTicket(Request $request)
    {
        // forget session
        $request->session()->forget('ticketSession');

        $ageGroups = AgeGroup::all()->map(function ($age) {
            $data = [
                "id" => $age->id,
            ];

            if (!$age->to) {
                $data["name"] = "{$age->name} " . __("app.from") . "  {$age->from}";
                // $data["name"] = "{$age->name} " . __("app.from") . "  {$age->from} "  . __("app.above");
            } else {
                $data["name"] = "{$age->name} " . __("app.from") . "  {$age->from} " . __("app.to") . " {$age->to}";
            }

            return $data;
        })->pluck("name", "id");

        $sections = Section::active()->get()->map(function ($section) {
            return [
                "id" => $section->id,
                "name" => __("app.section") . " {$section->section_no}",
            ];;
        })->pluck("name", "id");

        $supporters = Supporter::pluck("name", "id");
        $categories = Category::all()->map(function ($category) {
            return [
                "id" => $category->id,
                "name" => $category->name,
            ];
        })->pluck("name", "id");

        $normaLCategoryId = Category::where("name", "like", "%Normal%")->value("id");

        $banners = Banner::query()
            ->active()
            ->where("name", "season-ticket")
            ->get()
            ->map(function ($banner) {
                // add image to $banner
                $banner["image"] = $banner->getFirstMediaUrl("season-ticket");
                return $banner;
            });

        return view('frontend.pages.season-ticket', compact('ageGroups', 'sections', 'supporters', 'categories', 'banners', 'normaLCategoryId'));
    }

    public function seasonTicketStore(CheckoutRequest $request)
    {
        $validated = $request->validated();

        // store data in session
        $validated["category"] = Category::whereId($validated["category_id"])->value("name");
        $validated["age_group"] = AgeGroup::whereId($validated["age_group_id"])->value("name");
        $validated["section"] = Section::whereId($validated["section_id"])->value("section_no");
        $validated["supporter"] = Supporter::whereId($validated["supporter_id"])->value("name");
        $ticket =  Ticket::query()
            ->where("ticket_type", $this->ticketType)
            ->where("category_id", $request->category_id)
            ->where("age_group_id", $request->age_group_id)
            ->first();

        // // check quantity
        // if ($ticket->remain_qty < $validated["qty"]) {
        //     return redirect()->back()->with([
        //         "message" => __("app.messages.only_amount_ticket_left", ["amount" => $ticket->remain_qty]),
        //         "icon" => "info",
        //     ]);
        // }

        $validated["price"] = $ticket->price;
        $validated["ticket_type"] = TicketType::getEnumValue('SEASON_TICKET');

        // put validated data in session
        $request->session()->put('ticketSession', $validated);

        return redirect()->route('checkout.index');
    }

    public function getTicketPrice(Request $request)
    {
        $ticket = Ticket::query()
            ->where("age_group_id", $request->age_group_id)
            ->where("category_id", $request->category_id)
            ->where("ticket_type", $this->ticketType)
            ->first();

        return $this->jsonResponse(true, "Ticket Price", Response::HTTP_OK, [
            "price" => $ticket->price,
        ]);
    }

    public function checkTicketQuantity(Request $request)
    {
        $remainAmount = $this->sectionQuantityService->getRemainQuantity($request->section_id, $request->qty);
        $isSoldOut =  !$this->sectionQuantityService->checkQuantity($request->section_id, $request->qty);

        return $this->jsonResponse(true, "Remain Quantity", Response::HTTP_OK, [
            "remainQty" => $remainAmount,
            "isSoldOut" => $isSoldOut,
        ]);
    }
}
