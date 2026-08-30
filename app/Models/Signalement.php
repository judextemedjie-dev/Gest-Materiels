<?php
// ============================================================
// app/Models/Signalement.php
// ============================================================
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Signalement extends Model
{
    protected $fillable = [
        'affectation_id', 'client_id', 'materiel_id',
        'type', 'description', 'statut', 'lu_at',
    ];

    protected $casts = [
        'lu_at' => 'datetime',
    ];

    public function affectation()
    {
        return $this->belongsTo(Affectation::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class);
    }

    public function materiel()
    {
        return $this->belongsTo(Materiel::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'panne'         => '🔴 Panne',
            'deterioration' => '🟠 Détérioration',
            'perte'         => '⚫ Perte / Vol',
            'autre'         => '🔵 Autre problème',
            default         => ucfirst($this->type),
        };
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'nouveau' => 'bg-red-100 text-red-700',
            'lu'      => 'bg-yellow-100 text-yellow-700',
            'traite'  => 'bg-green-100 text-green-700',
            default   => 'bg-gray-100 text-gray-700',
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'nouveau' => 'Nouveau',
            'lu'      => 'Lu',
            'traite'  => 'Traité',
            default   => ucfirst($this->statut),
        };
    }
}