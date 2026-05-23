<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

   public function toMail($notifiable)
{
    $url = url('/reset-password/'.$this->token.'?email='.urlencode($notifiable->email));

    return (new MailMessage)
        ->subject('【重要】パスワード再設定のご案内')
        ->greeting($notifiable->name . ' 様')
        ->line('いつもご利用いただき、誠にありがとうございます。')
        ->line('パスワード再設定のリクエストを受け付けました。')
        ->line('以下のボタンをクリックして、パスワードの再設定を行ってください。')
        ->action('パスワードを再設定する', $url)
        ->line('※このリンクの有効期限は60分です。')
        ->line('本メールにお心当たりがない場合は、本メールを破棄してください。')
        ->line('今後ともよろしくお願いいたします。');
}
}