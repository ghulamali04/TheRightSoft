<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewUserEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected $client;

    /**
     * Create a new notification instance.
     */
    public function __construct(\App\Models\Client $client)
    {
        $this->client = $client;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
        ->subject('New User Registered')
        ->greeting('Hello Admin!')
        ->line('A new user has registered on the platform.')
        ->line('Name: ' . $this->client->first_name . ' ' . $this->client->last_name)
        ->line('Email: ' . $this->client->email)
        ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
