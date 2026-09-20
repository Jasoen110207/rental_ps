<?php

namespace App\Notifications;

use App\Models\CustomerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class CustomerRequestNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public CustomerRequest $customerRequest)
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $tvName = $this->customerRequest->tv ? $this->customerRequest->tv->name : 'Meja';
        $type = $this->customerRequest->type;
        $message = '';
        
        if ($type === 'add_time') {
            $hours = $this->customerRequest->payload['duration_hours'] ?? 1;
            $message = "Request tambah waktu $hours jam dari $tvName";
        } elseif ($type === 'order_food') {
            $message = "Pesanan F&B baru dari $tvName";
        } elseif ($type === 'service_call') {
            $message = "Panggilan kasir dari $tvName";
        } else {
            $message = "Permintaan baru dari $tvName";
        }

        return [
            'request_id' => $this->customerRequest->id,
            'tv_id' => $this->customerRequest->tv_id,
            'type' => $type,
            'message' => $message,
            'tv_name' => $tvName,
        ];
    }
}
