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
            ->subject('Email Verification OTP - IPHACON 2027')
            ->greeting('Hello ' . ($notifiable->full_name ?? 'Delegate') . '!')
            ->line('Welcome to IPHACON 2027 Conference Registration.')
            ->line('Your One-Time Password (OTP) for email verification is:')
            ->line('### **' . $otp . '**')
            ->line('This OTP is valid for 15 minutes.')
            ->line('Please enter this code on the verification page to complete your registration.')
            ->line('If you did not request this OTP, no further action is required.')
            ->salutation('Best regards, IPHACON 2027 Organizing Committee');
    }
}
