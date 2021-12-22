<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Course;
use App\Channels\DatabaseChannel;

class NewCoursePosted extends Notification implements ShouldQueue
{
    use Queueable;

    protected $course;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(Course $course)
    {
        $this->course = $course;
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
                    ->subject('New course - "' . $this->course->title . '" was posted.')
                    ->line('New course "' . $this->course->title . '" was posted.')
                    ->line('Course: ' . $this->course->title)
                    ->action('View', route('member.course.show', $this->course->id))
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
            'title' => 'New course - "' . $this->course->title . '" was posted',

            'body' => 'New course - "' . $this->course->title . '" was posted',
            'click_action_link' => route('member.course.show', $this->course->id),
            'click_action_page' => 'App\Models\Course',
            'model_id' => $this->course->id,
            'line' => 'Thank you for using our application!'
        ];
    }
}
