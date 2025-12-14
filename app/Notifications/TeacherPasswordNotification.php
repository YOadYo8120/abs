<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherPasswordNotification extends Notification
{
    use Queueable;

    public $password;

    /**
     * Create a new notification instance.
     */
    public function __construct(string $password)
    {
        $this->password = $password;
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
            ->subject('Welcome to ENSA Safi - Your Teacher Account')
            ->greeting("Hello {$notifiable->name}!")
            ->line('Your teacher account has been created successfully at ENSA Safi.')
            ->line('Here are your login credentials:')
            ->line("**Email:** {$notifiable->email}")
            ->line("**Password:** {$this->password}")
            ->line('Please keep this information secure and change your password after your first login.')
            ->action('Login to Your Account', url('/login'))
            ->line('You can now access the system to manage your modules and schedules.')
            ->line('If you have any questions, please contact the administration.')
            ->salutation('Best regards, ENSA Safi Administration');
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
