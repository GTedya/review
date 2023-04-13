<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;

    /**
     * @property int $id
     * @property string $name
     * @property string $email
     * @property string $password
     * @property ?Carbon $created_at
     * @property ?Carbon $updated_at
     * @property ?string $remember_token
     * @property Collection<Order> $orders
     * @property ?Order $banOrders
     *
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function canAccessFilament(): bool
    {
        return $this->can('view admin panel');
    }

    public function orders(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function banOrders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'manager_order_bans', 'user_id', 'order_id');
    }
}
