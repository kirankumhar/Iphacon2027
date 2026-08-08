<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends Notification
{
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $otp = $notifiable->otp;

        return (new MailMessage)
            ->subject('OTP for Account Verification - IPHACON 2027')
            ->view('emails.otp_verification', [
                'user' => $notifiable,
                'otp' => $otp
            ]);
    }
}
