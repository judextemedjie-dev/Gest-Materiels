<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = ['name', 'email', 'password'];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return ['email_verified_at' => 'datetime', 'password' => 'hashed'];
    }

    public function clients()
    {
        return $this->hasMany(Client::class, 'created_by');
    }

    public function affectations()
    {
        return $this->hasMany(Affectation::class, 'gestionnaire_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'created_by');
    }

    public function mouvements()
    {
        return $this->hasMany(Mouvement::class);
    }
}