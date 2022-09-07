<?php
namespace App\Notifications;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Bus\Queueable;
class ResetPassword extends Notification
{
    use Queueable;
/**
     * The callback that should be used to build the mail message.
     *
     * @var \Closure|null
     */
  public $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }
/**
     * Get the notification's channels.
     *
     * @param  mixed  $notifiable
     * @return array|string
     */
    public function via($notifiable)
    {
        return ['mail'];
    }
/**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {

return (new MailMessage)
            ->subject(Lang::get('Reset your Password'))
            ->line(Lang::get('Please click the button below to reset your password.'))
            ->action(Lang::get('Reset your Password'), "http://localhost:3000/password/reset?token=".$this->token);
    }

}
