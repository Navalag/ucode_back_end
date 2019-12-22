<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword;

class MailResetPasswordNotification  extends ResetPassword
{
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        parent::__construct($token);
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return MailMessage
     */
    public function toMail($notifiable)
    {
        $link = url( "/api/auth/password-reset/".$this->token );

        return ( new MailMessage )
            ->subject( 'Reset Password Notification' )
            ->line( "Hello! You are receiving this email because we received a password reset request for your account." )
            ->action( 'Reset Password', $link )
            ->line( "This password reset link will expire in ".config('auth.passwords.users.expire')." minutes" )
            ->line( "If you did not request a password reset, no further action is required." );
    }
}
