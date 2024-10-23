<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class UserVerifiedNotification extends Notification
{
    use Queueable;

    public function __construct()
    {
        //
    }

    public function via($notifiable)
    {
        return ['mail']; //definindo aonde sera enviada a notification
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Usuário Verificado')
                    ->line('O seu usuário foi verificado com sucesso.')
                    ->action('Acesse agora!', url('/'))
                    ->line('Obrigado pela preferência!');
    }
}
