<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Config;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CustomVerifyEmail extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
                    ->subject('Xác minh địa chỉ email của bạn')
                    ->view('emails.verify', compact('verificationUrl'));
                    // ->line('Vui lòng nhấp vào nút bên dưới để xác minh địa chỉ email của bạn.')
                    // ->action('Xác minh địa chỉ email', $verificationUrl)
                    // ->line('Nếu bạn không tạo tài khoản, bạn không cần thực hiện thêm hành động nào.')
                    // ->line('Email này sẽ hết hạn sau 24 giờ.');
    }

    protected function verificationUrl($notifiable)
    {
        // Tạo URL tạm thời
        return URL::temporarySignedRoute(
            'verification.verify', // trỏ đến name route
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 15)), // Thời gian hết hạn của URL
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
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
