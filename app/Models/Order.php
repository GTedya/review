<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $user_id
 * @property ?int $geo_id
 * @property int $status_id
 * @property ?string $inn
 * @property ?string $org_name
 * @property ?string $admin_comment
 * @property string $name
 * @property string $email
 * @property string $phone
 * @property ?Carbon $end_date
 * @property ?Carbon $created_at
 * @property ?Carbon $updated_at
 * @property User $user
 * @property ?Geo $geo
 * @property ?OrderLeasing $leasing
 * @property Collection<File> $files
 * @property Status $status
 * @property ?User $banUsers
 * @method static Builder manager(int $userId)
 * @method Builder manager(int $userId)
 * @property Collection<int, User> $managers
 * @property Collection<OrderLeasingVehicle> $leasingVehicles
 * @property Collection<OrderDealerVehicle> $dealerVehicles
 *
 */
class Order extends Model
{
    protected $fillable = [
        'user_id',
        'org_name',
        'inn',
        'phone',
        'name',
        'email',
        'end_date',
        'created_at',
        'geo_id',
        'status_id',
        'admin_comment',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    public function geo(): BelongsTo
    {
        return $this->belongsTo(Geo::class, 'geo_id');
    }

    public function leasing(): HasOne
    {
        return $this->hasOne(OrderLeasing::class);
    }

    public function files(): MorphMany
    {
        return $this->morphMany(File::class, 'fileable',);
    }

    public function banUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'manager_order_bans', 'order_id', 'user_id');
    }

    public function dealerVehicles(): HasMany
    {
        return $this->hasMany(OrderDealerVehicle::class);
    }


    public function leasingVehicles(): HasMany
    {
        return $this->hasMany(OrderLeasingVehicle::class);
    }

    public function managers()
    {
        return $this->belongsToMany(User::class, 'taken_orders', 'order_id', 'user_id');
    }

    public function getCreatedAtAttribute($value)
    {
        return $value;
    }

    public function getUpdatedAtAttribute($value)
    {
        return $value;
    }

    /**
     * @param Builder $query
     * @param int $userId
     * @return Builder
     */
    public function scopeManager(Builder $query, int $userId): Builder
    {
       return $query->whereDoesntHave('banUsers', function (\Illuminate\Database\Eloquent\Builder $query) use ($userId) {
            $query->where('manager_order_bans.user_id', $userId);
        });
    }
}
