<?php

namespace App\Notifications;

use App\Models\AssignmentUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Channels\DatabaseChannel;

class FeedbackAssignment extends Notification
{
    use Queueable;

    private $assignmentUser;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(AssignmentUser $assignmentUser)
    {
        $this->assignmentUser = $assignmentUser;
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
                    ->line('The introduction to the notification.')
                    ->action('Notification Action', url('/'))
                    ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'title' => $this->assignmentUser->commentUser->name . ' return feedback for ' . $this->assignmentUser->assignment->title . ' for ' . $this->assignmentUser->assignment->course->title . ' course',
            'body' => $this->assignmentUser->commentUser->name . ' return feedback for ' . $this->assignmentUser->assignment->title . ' for ' . $this->assignmentUser->assignment->course->title . ' course',
            'model_id' => $this->assignmentUser->id,
            'click_action_link' => route('courses.view-assignment-feedback', $this->assignmentUser)
        ];
    }
}
