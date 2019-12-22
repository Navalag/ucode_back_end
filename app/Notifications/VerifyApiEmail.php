<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as VerifyEmailBase;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class VerifyApiEmail extends VerifyEmailBase
{
    /**
     * Get the verification URL for the given notifiable.
     *
     * @param mixed $notifiable
     * @return string
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'auth.verification_api.verify', Carbon::now()->addMinutes(60), ['id' => $notifiable->getKey()]
        );
    }
}
