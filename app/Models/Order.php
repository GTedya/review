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
 * @property Collection<int, OrderLeasingVehicle> $leasingVehicles
 * @property Collection<int, OrderHistory> $orderHistory
 * @property Collection<int, OrderDealerVehicle> $dealerVehicles
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

    public function orderHistory(): HasMany
    {
        return $this->hasMany(OrderHistory::class);
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
