<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'status',
        'total_amount',
        'notes',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
    ];

    // -----------------------------------------------
    // Constantes de statuts
    // -----------------------------------------------
    const STATUS_PENDING   = 'pending';
    const STATUS_PREPARING = 'preparing';
    const STATUS_READY     = 'ready';
    const STATUS_PAID      = 'paid';
    const STATUS_CANCELLED = 'cancelled';

    /**
     * Liste des statuts avec libellés et couleurs Tailwind
     */
    public static function statusOptions(): array
    {
        return [
            self::STATUS_PENDING   => ['label' => 'En attente',      'color' => 'yellow'],
            self::STATUS_PREPARING => ['label' => 'En préparation',   'color' => 'blue'],
            self::STATUS_READY     => ['label' => 'Prête',            'color' => 'green'],
            self::STATUS_PAID      => ['label' => 'Payée',            'color' => 'purple'],
            self::STATUS_CANCELLED => ['label' => 'Annulée',          'color' => 'red'],
        ];
    }

    // -----------------------------------------------
    // Boot : génère la référence automatiquement
    // -----------------------------------------------
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($order) {
            $order->reference = self::generateReference();
        });
    }

    /**
     * Génère une référence unique : CMD-20260320-0042
     */
    public static function generateReference(): string
    {
        $date  = now()->format('Ymd');
        $count = self::whereDate('created_at', today())->count() + 1;
        return 'CMD-' . $date . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
    }

    // -----------------------------------------------
    // Accessors
    // -----------------------------------------------

    /**
     * Retourne le libellé du statut en français
     */
    public function getStatusLabelAttribute(): string
    {
        return self::statusOptions()[$this->status]['label'] ?? $this->status;
    }

    /**
     * Retourne la couleur Tailwind du statut
     */
    public function getStatusColorAttribute(): string
    {
        return self::statusOptions()[$this->status]['color'] ?? 'gray';
    }

    /**
     * Montant total formaté
     */
    public function getFormattedTotalAttribute(): string
    {
        return number_format($this->total_amount, 0, ',', ' ') . ' FCFA';
    }

    // -----------------------------------------------
    // Méthodes utilitaires
    // -----------------------------------------------

    public function isPending(): bool    { return $this->status === self::STATUS_PENDING; }
    public function isPreparing(): bool  { return $this->status === self::STATUS_PREPARING; }
    public function isReady(): bool      { return $this->status === self::STATUS_READY; }
    public function isPaid(): bool       { return $this->status === self::STATUS_PAID; }
    public function isCancelled(): bool  { return $this->status === self::STATUS_CANCELLED; }

    /**
     * Recalcule et sauvegarde le montant total
     */
    public function recalculateTotal(): void
    {
        $this->total_amount = $this->items()->sum('subtotal');
        $this->save();
    }

    /**
     * Transitions de statut autorisées
     */
    public function canTransitionTo(string $newStatus): bool
    {
        $allowed = [
            self::STATUS_PENDING   => [self::STATUS_PREPARING, self::STATUS_CANCELLED],
            self::STATUS_PREPARING => [self::STATUS_READY, self::STATUS_CANCELLED],
            self::STATUS_READY     => [self::STATUS_PAID],
            self::STATUS_PAID      => [],
            self::STATUS_CANCELLED => [],
        ];

        return in_array($newStatus, $allowed[$this->status] ?? []);
    }

    // -----------------------------------------------
    // Scopes
    // -----------------------------------------------

    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    public function scopeActive($query)
    {
        return $query->whereNotIn('status', [self::STATUS_PAID, self::STATUS_CANCELLED]);
    }

    // -----------------------------------------------
    // Relations
    // -----------------------------------------------

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }
}