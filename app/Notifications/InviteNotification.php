<?php

namespace App\Notifications;

use App\Models\Invite;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InviteNotification extends Notification
{
    use Queueable;

    public function __construct(
        protected Invite $invite
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = config('app.frontend_url') . '/invite/' . $this->invite->token;

        return (new MailMessage)
            ->subject("Você foi convidado para {$this->invite->tenant->name}")
            ->greeting("Olá!")
            ->line("Você recebeu um convite de **{$this->invite->invitedBy->name}** para entrar no tenant **{$this->invite->tenant->name}**.")
            ->action('Aceitar Convite', $url)
            ->line("Este convite expira em 7 dias.")
            ->line("Se você não esperava este convite, ignore este email.");
    }
}
