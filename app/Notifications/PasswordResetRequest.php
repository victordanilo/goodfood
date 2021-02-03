<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PasswordResetRequest extends Notification
{
    use Queueable;

    protected $token;
    protected $scope;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token, $scope)
    {
        $this->token = $token;
        $this->scope = $scope;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = route("{$this->scope}.password.find", ['token' => $this->token]);

        return (new MailMessage)
            ->subject(__('passwords.email_password_reset_request_subject'))
            ->line(__('passwords.email_password_reset_request_line1'))
            ->action(__('passwords.email_password_reset_request_action'), $url)
            ->line(__('passwords.email_password_reset_request_line2'));
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
            //
        ];
    }
}
