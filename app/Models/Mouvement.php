<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mouvement extends Model
{
    protected $fillable = [
        'materiel_id',
        'type',
        'quantite',
        'from_location',
        'to_location',
        'user_id',
        'client_id',
        'description',
    ];

    public function materiel()
    {
        return $this->belongsTo(Materiel::class, 'materiel_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'affectation' => 'Affectation',
            'retour'      => 'Retour/Restitution',
            'transfert'   => 'Transfert',
            'ajout'       => 'Ajout stock',
            'archivage'   => 'Archivage',
            default       => ucfirst($this->type),
        };
    }
}