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
        $fromAddress = config('mail.from.address') ?: 'noreply@iphacon2027.com';

        return (new MailMessage)
            ->from($fromAddress, 'IPHACON 2027')
            ->subject('OTP for Account Verification - IPHACON 2027')
            ->view('emails.otp_verification', [
                'user' => $notifiable,
                'otp' => $otp
            ]);
    }
}
