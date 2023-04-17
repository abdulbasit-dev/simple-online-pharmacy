<?php

namespace App\Notifications;

use App\Models\Medicine;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MedicineStockAlertNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(public  $desc)
    {
        $this->desc = $desc;
    }

    public function via($notifiable)
    {
        return ['database'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    public function toArray($notifiable)
    {
        return [
            'message' => "Low Medicine Stock Alert",
            "desc"    => $this->desc,
            'icon'    => 'bx bx-package',
        ];
    }
}
