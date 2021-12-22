<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\ApprovalRequest;
use App\Channels\DatabaseChannel;

class ResourceSubmittedForApproval extends Notification implements ShouldQueue
{
    use Queueable;

    protected $approvalRequest;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(ApprovalRequest $approvalRequest)
    {
        $this->approvalRequest = $approvalRequest;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', DatabaseChannel::class];
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
                    ->line('A resource "' . $this->approvalRequest->resource->title . '" was submitted for approval by ' . $this->approvalRequest->user->name . '.')
                    ->line('Message: ')
                    ->line('"Message: ' . $this->approvalRequest->description . '"')
                    ->action('View', route('member.approval-request.show', $this->approvalRequest->id))
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
            'title' => 'A resource "' . $this->approvalRequest->resource->title . '" was submitted for approval by ' . $this->approvalRequest->user->name . '.',
            'body' => 'A resource "' . $this->approvalRequest->resource->title . '" was submitted for approval by ' . $this->approvalRequest->user->name . '.',
            'click_action_link' => route('member.approval-request.show', $this->approvalRequest->id),
            'click_action_page' => 'ApprovalRequest',
            'model_id' => $this->approvalRequest->id,
            'line' => 'Thank you for using our application!'
        ];
    }
}
