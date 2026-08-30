<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    protected $fillable = [
        'materiel_id',
        'type',
        'statut',
        'date_planifiee',
        'date_realisation',
        'technicien',
        'rapport',
        'cout',
        'created_by',
    ];

    protected $casts = [
        'date_planifiee'   => 'date',
        'date_realisation' => 'date',
        'cout'             => 'decimal:2',
    ];

    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'materiel_id');
    }

    public function createur()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function estEnRetard(): bool
    {
        return $this->statut !== 'termine' && $this->date_planifiee < now();
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'planifie' => 'bg-yellow-100 text-yellow-800',
            'en_cours' => 'bg-blue-100 text-blue-800',
            'termine'  => 'bg-green-100 text-green-800',
            default    => 'bg-gray-100 text-gray-700',
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'planifie' => 'Planifié',
            'en_cours' => 'En cours',
            'termine'  => 'Terminé',
            default    => ucfirst($this->statut),
        };
    }
}