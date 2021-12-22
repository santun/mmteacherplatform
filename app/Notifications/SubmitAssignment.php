<?php

namespace App\Notifications;

use App\Models\Assignment;
use App\Models\AssignmentUser;
use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SubmitAssignment extends Notification
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
//    public function toMail($notifiable)
//    {
//        return (new MailMessage)
//                    ->line('The introduction to the notification.')
//                    ->action('Notification Action', url('/'))
//                    ->line('Thank you for using our application!');
//    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }

    public function toDatabase()
    {
        return [
            'title' => $this->assignmentUser->user->name . ' submitted ' . $this->assignmentUser->assignment->title . ' for ' . $this->assignmentUser->assignment->course->title . 'course',
            'body' => $this->assignmentUser->user->name . ' submitted ' . $this->assignmentUser->assignment->title . ' for ' . $this->assignmentUser->assignment->course->title . 'course',
            'model_id' => $this->assignmentUser->id,
            'click_action_link' => route('member.assignment.detail', $this->assignmentUser->assignment_id)
        ];
    }
}
