<?php

namespace App\Services;

use App\Enums\OrderEmailStatus;
use App\Mail\MatchTicketEmail;
use App\Mail\SeasonTicketEmail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;

class SendTicketEmailService
{

    public function sendMail($orders, $queryUpdateEmailStatus)
    {
        if ($orders->first()->email_status->isUnsent()) {
            // loop through orders and send mail to customer
            $orderCollection = collect();
            foreach ($orders as $order) {
                $data = [
                    "customer_name"          => $order->customer->name,
                    "customer_email"         => $order->customer->email,
                    "ticket_number"          => $order->ticket_number,
                    "serial_number"          => $order->serial_number,
                    "order_id"               => $order->order_id,
                    "season_year"            => date('Y'),
                    "section"                => $order->section->section_no,
                    "price"                  => $order->price,
                    "category"               => $order->ticket->category->name ?? null,
                    "age_group"              => $order->ticket->ageGroup->name ?? null,
                    "qr_image"               => $order->qr_image,
                ];

                // type of order is match ticket
                if ($order->ticket_type->isMatchTicket()) {
                    $data["match"]         = $order->match->home->name . " - " . $order->match->away->name;
                    $data["match_time"]    = Carbon::parse($order->match->match_time)->format('Y-m-d H:i');
                    $data["stadium"]       = "Studenternas IP";
                    $data["supporter"]     =  $order->ticket->supporter->name;
                }

                // generate pdf and save to s3
                // $pdf = PDF::loadView('frontend.pdf.season-ticket', $data);
                // $pdfName = $order->serial_number . ".pdf";
                // $pdfPath = "season_tickets/" . $pdfName;
                // Storage::disk('public')->put($pdfPath, $pdf->output());

                $orderCollection->push($data);
            }

            if ($orders->first()->ticket_type->isMatchTicket()) {
                // Send mail to customer
                Mail::to($orders->first()->customer->email)
                    ->cc(config("mail.reply_to.address"))
                    ->send(new MatchTicketEmail($orderCollection));
            } else {
                // Send mail to customer
                Mail::to($orders->first()->customer->email)
                    ->cc(config("mail.reply_to.address"))
                    ->send(new SeasonTicketEmail($orderCollection));
            }

            // update email status to sent
            $queryUpdateEmailStatus->update(["email_status" => OrderEmailStatus::SENT]);
        }
    }
}
