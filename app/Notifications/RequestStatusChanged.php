<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class RequestStatusChanged extends Notification
{
    use Queueable;

    // 1. Define a protected property to hold the data
    protected $businessRequest;

    /**
     * Create a new notification instance.
     */
    public function __construct($businessRequest)
    {
        // 2. Assign the data from the controller to the property
        $this->businessRequest = $businessRequest;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via($notifiable)
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray($notifiable)
    {
        // 3. Use $this->businessRequest to access the model data
        return [
            'request_id'     => $this->businessRequest->id,
            'request_number' => $this->businessRequest->request_number,
            'title'          => $this->businessRequest->title,
            'status'         => $this->businessRequest->status,
            'message'        => "Your request #{$this->businessRequest->request_number} has been updated to {$this->businessRequest->status}.",
        ];
    }
}