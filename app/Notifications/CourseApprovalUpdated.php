<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\CourseApprovalRequest;
use App\Channels\DatabaseChannel;

class CourseApprovalUpdated extends Notification implements ShouldQueue
{
    use Queueable;

    protected $approvalRequest;
    protected $action;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(CourseApprovalRequest $approvalRequest, $action)
    {
        $this->approvalRequest = $approvalRequest;
        $this->action = $action;
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
                    ->line('The course "' . $this->approvalRequest->course->title . '" was ' . $this->action . ' by '
                        . $this->approvalRequest->approver->name . '.')
                    ->line('Course: ' . $this->approvalRequest->course->title)
                    ->action('View', route('member.course-approval-request.show', $this->approvalRequest->id))
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
            'title' => 'The course "' . $this->approvalRequest->course->title . '" was ' . $this->action . ' by '
            . $this->approvalRequest->approver->name . '.',
            'body' => 'The course "' . $this->approvalRequest->course->title . '" was ' . $this->action . ' by '
            . $this->approvalRequest->approver->name . '.',
            'click_action_link' => route('member.course-approval-request.show', $this->approvalRequest->id),
            'click_action_page' => 'App\Models\CourseApprovalRequest',
            'model_id' => $this->approvalRequest->id,
            'line' => 'Thank you for using our application!'
        ];
    }
}
