<?php

namespace App\Notifications;

use App\Models\VerifyAdmin;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class UserVerificationRequestNotification extends Notification 
{
    use Queueable;

    protected $verifyAdmin;

    public function __construct(VerifyAdmin $verifyAdmin)
    {
        $this->verifyAdmin = $verifyAdmin;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        
        return (new MailMessage)
            ->subject('Solicitação de Verificação de Usuário')
            ->line('Um novo usuário precisa ser verificado.')
            ->action('Verificar Usuário', url(route('verify.admin', ['hash' => $this->verifyAdmin->hash])));
    }
}
