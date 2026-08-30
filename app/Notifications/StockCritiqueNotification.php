<?php

namespace App\Notifications;

use App\Models\Materiel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class StockCritiqueNotification extends Notification
{
    use Queueable;

    public function __construct(public Materiel $materiel) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'stock_critique',
            'titre'       => 'Stock critique',
            'message'     => "Le matériel \"{$this->materiel->designation}\" a un stock de {$this->materiel->quantite_stock} unité(s).",
            'materiel_id' => $this->materiel->id,
            'icone'       => 'fa-triangle-exclamation',
            'couleur'     => 'amber',
        ];
    }
}