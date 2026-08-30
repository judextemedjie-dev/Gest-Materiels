<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Affectation extends Model
{
    protected $fillable = [
        'materiel_id',
        'client_id',
        'gestionnaire_id',
        'quantite',
        'date_affectation',
        'heure_affectation',
        'statut',
        'date_restitution',
        'notes',
    ];

    protected $casts = [
        'date_affectation' => 'date',
        'date_restitution' => 'date',
    ];

    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'materiel_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'gestionnaire_id');
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'affecte'  => 'bg-blue-100 text-blue-800',
            'restitue' => 'bg-gray-100 text-gray-700',
            default    => 'bg-gray-100 text-gray-700',
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'affecte'  => 'Affecté',
            'restitue' => 'Restitué',
            default    => ucfirst($this->statut),
        };
    }
}