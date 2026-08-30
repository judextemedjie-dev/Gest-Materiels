<?php
namespace App\Notifications;

use App\Models\Maintenance;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MaintenanceRetardNotification extends Notification
{
    use Queueable;

    public function __construct(public Maintenance $maintenance) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type'           => 'maintenance_retard',
            'titre'          => 'Maintenance en retard',
            'message'        => "La maintenance de \"{$this->maintenance->materiel?->designation}\" planifiée le {$this->maintenance->date_planifiee?->format('d/m/Y')} n'a pas été effectuée.",
            'maintenance_id' => $this->maintenance->id,
            'icone'          => 'fa-clock',
            'couleur'        => 'red',
        ];
    }
}