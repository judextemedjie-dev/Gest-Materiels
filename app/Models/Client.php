<?php
// app/Models/Client.php — VERSION MISE À JOUR avec token

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Client extends Model
{
    protected $fillable = ['nom', 'email', 'contact', 'created_by', 'token'];

    // Génère automatiquement un token unique à la création
    protected static function booted(): void
    {
        static::creating(function (Client $client) {
            if (empty($client->token)) {
                $client->token = Str::random(40);
            }
        });
    }

    public function gestionnaire()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'client_id')->orderByDesc('created_at');
    }

    public function affectationsEnCours()
    {
        return $this->hasMany(Affectation::class, 'client_id')->where('statut', 'affecte');
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class, 'client_id');
    }

    public function signalements()
    {
        return $this->hasMany(Signalement::class, 'client_id')->orderByDesc('created_at');
    }

    // Lien du portail client
    public function getLienPortailAttribute(): string
    {
        return url('/portail/' . $this->token);
    }
}