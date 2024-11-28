<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification //implements ShouldQueue
{
    use Queueable;

    public $token;
    public $email;

    /**
     * Create a new notification instance.
     */
    public function __construct($token, $email)
    {
        $this->token = $token;
        $this->email = $email;
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
        // $url = url(config('app.url')); // Sửa lại tên miền sang bên FE
        $url = 'https://umeacademy.online'; 

        $resetUrl = $url . "/reset-password?token={$this->token}&email={$this->email}"; 

        return (new MailMessage)
                    ->subject('Đặt lại mật khẩu của bạn')
                    ->greeting('Chào bạn!')
                    ->line('Bạn đã yêu cầu đặt lại mật khẩu cho tài khoản của mình.')
                    ->action('Đặt lại mật khẩu', $resetUrl)
                    ->line('Nếu bạn không yêu cầu thay đổi này, vui lòng bỏ qua email này.')
                    ->salutation('Chân thành, đội ngũ hỗ trợ.');
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
