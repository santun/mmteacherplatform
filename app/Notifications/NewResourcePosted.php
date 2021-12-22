<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Resource;
use App\Channels\DatabaseChannel;

class NewResourcePosted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $resource;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Resource $resource)
    {
        $this->resource = $resource;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [DatabaseChannel::class, 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('New resource - "' . $this->resource->title . '" was posted.')
                    ->line('New resource "' . $this->resource->title . '" was posted.')
                    ->line('Resource: ' . $this->resource->title)
                    ->action('View', route('resource.show', $this->resource->slug))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toDatabase($notifiable)
    {
        return [
            'title' => 'New resource - "' . $this->resource->title . '" was posted',

            'body' => 'New resource - "' . $this->resource->title . '" was posted',
            'click_action_link' => route('resource.show', $this->resource->slug),
            'click_action_page' => 'Resource',
            'model_id' => $this->resource->id,
            'line' => 'Thank you for using our application!'
        ];
    }
}
