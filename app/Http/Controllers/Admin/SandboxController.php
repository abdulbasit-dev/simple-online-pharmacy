<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\SeasonTicketEmail;
use App\Mail\TicketMail;
use App\Models\AgeGroup;
use App\Models\Order;
use App\Models\Supporter;
use Illuminate\Support\Facades\Mail;
use PDF;
use URL;

class SandboxController extends Controller
{
    //    difine orders collection here as a property and fill it in the constructor
    protected $orders;

    public function __construct()
    {
        // get ranodm order qr code
        $orderQrCode = Order::inRandomOrder()->first()->qr_code ?? null;
        $this->orders = collect([
            [
                "customer_name" => "Abdulbasit Salah",
                "customer_email" => "basit99dev@gmail.com",
                "ticket_number" => "16778198462144472355",
                "serial_number" => "DUKZAW-010302082",
                "order_id" => "16778198462144472355",
                "section" => "Section 1",
                "category" => "Normal",
                "price" => formatPrice(100),
                // "qr_image" => "https://asset.sportsclubticket.com/qrcodes/DUK_ZAW2023_03_01/DUK_ZAW_16776883013667096310.png",
                "qr_image" => "https://asset.sportsclubticket.com/dev_qrcodes/season_tickets/normal_16800936842203435579_2023.png",
                "club_logo" => URL::asset('assets/images/logo.png'),
                "supporter" => Supporter::first()->name,
                "age_group" => AgeGroup::inRandomOrder()->first()->name,
                "season_year" => date('Y'),
            ],
            [
                "customer_name" => "Abdulbasit Salah",
                "customer_email" => "basit99dev@gmail.com",
                "ticket_number" => "16778198462144472355",
                "serial_number" => "DUKZAW-010302082",
                "order_id" => "16778198462144472355",
                "section" => "Section 1",
                "category" => "Normal",
                "price" => formatPrice(100),
                "qr_image" => "https://asset.sportsclubticket.com/qrcodes/DUK_ZAW2023_03_01/DUK_ZAW_16776883013667096310.png",
                // "qr_image" => "https://asset.sportsclubticket.com/dev_qrcodes/season_tickets/normal_16800936842203435579_2023.png",
                "club_logo" => URL::asset('assets/images/logo.png'),
                "supporter" => Supporter::first()->name,
                "age_group" => AgeGroup::inRandomOrder()->first()->name,
                "season_year" => date('Y'),
            ],
        ]);
    }

    public function qrcodeViewer()
    {
        return view('admin.sandbox.qrcode-viewer');
    }

    public function renderSeasonTicket()
    {
        return new SeasonTicketEmail($this->orders);
    }

    public function sendSeasonTicket()
    {
        Mail::to($this->orders->pluck("customer_email")->toArray()[0])
            ->cc(config("mail.reply_to.address"))
            ->send(new SeasonTicketEmail($this->orders));
        return "sent";
    }

    public function ticketInvoice()
    {
        $data = [
            "name"          => "Abdulbasit Salah",
            "ticket_number" => "16778198462144472355",
            "order_id"      => "16778198462144472355",
            "serial_number" => "DUKZAW-010302082",
            "order_id"      => "16778198462144472355",
            "section"       => "Section 1",
            "category"      => "Normal",
            "price"         => formatPrice(100),
            "qr_image"      => "https://asset.sportsclubticket.com/qrcodes/DUK_ZAW2023_03_01/DUK_ZAW_16776883013667096310.png",
            "club_logo"     => URL::asset('assets/images/logo.png'),
            "supporter"     => Supporter::first()->name,
            "age_group"     => AgeGroup::inRandomOrder()->first()->name,
            "season_year"   => date('Y'),
        ];

        return view('frontend.pdf.season-ticket', $data);
    }

    public function ticketInvoicePdf()
    {
        $data = [
            "name"          => "Abdulbasit Salah",
            "ticket_number" => "16778198462144472355",
            "serial_number" => "DUKZAW-010302082",
            "section"       => "Section 1",
            "category"      => "Normal",
            "price"         => formatPrice(100),
            "qr_image"      => "https://asset.sportsclubticket.com/qrcodes/DUK_ZAW2023_03_01/DUK_ZAW_16776883013667096310.png",
            "club_logo"     => URL::asset('assets/images/logo.png'),
            "supporter"     => Supporter::first()->name,
            "age_group"     => AgeGroup::inRandomOrder()->first()->name,
            "season_year"   => date('Y'),
        ];

        $pdf = PDF::loadView('frontend.pdf.season-ticket', $data);
        return $pdf->stream('ticket.pdf');
    }
}
