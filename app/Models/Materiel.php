<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Materiel extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'designation',
        'code_identification',
        'categorie_id',
        'quantite_stock',
        'statut',
        'localisation',
        'description',
    ];

    public function categorie()
    {
        return $this->belongsTo(Categorie::class, 'categorie_id');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'materiel_id');
    }

    public function affectationsEnCours()
    {
        return $this->hasMany(Affectation::class, 'materiel_id')->where('statut', 'affecte');
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class, 'materiel_id')->orderByDesc('created_at');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'materiel_id')->orderByDesc('created_at');
    }

    public function getStatutBadgeAttribute(): string
    {
        return match($this->statut) {
            'en_service'    => 'bg-green-100 text-green-800',
            'en_panne'      => 'bg-red-100 text-red-800',
            'en_reparation' => 'bg-orange-100 text-orange-800',
            'archive'       => 'bg-gray-100 text-gray-800',
            default         => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatutLabelAttribute(): string
    {
        return match($this->statut) {
            'en_service'    => 'En service',
            'en_panne'      => 'En panne',
            'en_reparation' => 'En réparation',
            'archive'       => 'Archivé',
            default         => ucfirst($this->statut),
        };
    }
}