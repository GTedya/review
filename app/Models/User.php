<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Laravel\Sanctum\HasApiTokens;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Permission\Traits\HasRoles;

/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property string $password
 * @property ?string $phone_confirmation_code
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property ?Carbon $phone_verified_at
 * @property ?string $remember_token
 * @property ?string $device_key
 * @property Collection<Order> $orders
 * @property Collection<int, Order> $takenOrders
 * @property Collection<int, File> $files
 * @property Collection<int, ManagerOffer> $offers
 * @property Collection<Rent> $rents
 * @property Collection $userFiles
 * @property ?Order $banOrders
 * @property ?Company $company
 */
class User extends Authenticatable implements FilamentUser, HasMedia
{
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use InteractsWithMedia;
    use Notifiable;

    public const ROLE_NAMES = [
        'admin' => 'Администратор',
        'leasing_manager' => 'Менеджер Лизинга',
        'dealer_manager' => 'Менеджер Дилера',
        'client' => 'Клиент',
    ];

    public const ROLE_PERMISSION = [
        'client' => ['edit_order', 'create_order'],
        'manager' => ['send_offer', 'view_all_orders', 'take_order']
    ];


    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'phone_confirmation_code',
        'device_key',
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
        'phone_verified_at' => 'datetime',
    ];

    public function canAccessFilament(): bool
    {
        return $this->can('view admin panel');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function company(): HasOne
    {
        return $this->hasOne(Company::class);
    }

    public function rents(): HasMany
    {
        return $this->hasMany(Rent::class);
    }

    public function banOrders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'manager_order_bans', 'user_id', 'order_id');
    }

    public function files(): HasMany
    {
        return $this->hasMany(UserFile::class);
    }

    public function takenOrders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'taken_orders', 'user_id', 'order_id');
    }

    public function offers(): HasMany
    {
        return $this->hasMany(ManagerOffer::class);
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();
    }

    public function getCreatedAtAttribute($value)
    {
        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value;
    }


}
