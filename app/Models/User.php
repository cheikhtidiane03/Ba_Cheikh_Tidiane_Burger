<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    // -----------------------------------------------
    // Relations
    // -----------------------------------------------

    /**
     * Commandes passées par ce client
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Paiements enregistrés par ce gestionnaire
     */
    public function recordedPayments()
    {
        return $this->hasMany(Payment::class, 'recorded_by');
    }

    // -----------------------------------------------
    // Méthodes utilitaires
    // -----------------------------------------------

    public function isGestionnaire(): bool
    {
        return $this->hasRole('gestionnaire');
    }

    public function isClient(): bool
    {
        return $this->hasRole('client');
    }

    /**
     * Initiales pour l'avatar
     */
    public function getInitialsAttribute(): string
    {
        $words = explode(' ', $this->name);
        return strtoupper(
            collect($words)->take(2)->map(fn($w) => $w[0])->implode('')
        );
    }
}