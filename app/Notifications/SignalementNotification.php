<?php
// app/Notifications/SignalementNotification.php

namespace App\Notifications;

use App\Models\{Signalement, Client, Affectation};
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class SignalementNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Signalement $signalement,
        public Client $client,
        public Affectation $affectation
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'signalement_client',
            'titre'          => '⚠️ Signalement de ' . $this->client->nom,
            'message'        => "Problème signalé sur « {$this->affectation->materiel?->designation} » : {$this->signalement->type_label}. \"{$this->signalement->description}\"",
            'signalement_id' => $this->signalement->id,
            'client_id'      => $this->client->id,
            'icone'          => 'fa-triangle-exclamation',
            'couleur'        => 'red',
        ];
    }
}