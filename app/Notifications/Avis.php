<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class Avis extends Notification
{
    use Queueable;
    public $avis;

    /**
     * Create a new notification instance.
     */
    public function __construct($avis)
    {
        $this->avis = $avis;
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
        return (new MailMessage)
        ->subject('Nouvel avis créé')
        ->view('emails.avis')
        ->with([
            'utilisateur' => $this->avis->user->name,
            'commentaire' => $this->avis->commentaire,
            'statut' => $this->avis->statut,
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
           'message' => "L'avis de {$this->avis->User->name} a été créé.",
           'avis_id' => $this->avis->id,
            'statut' => $this->avis->statut,
            'created_at' => now(),

        ];
    }
}
