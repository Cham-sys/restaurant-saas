<?php

namespace App\Models;

use Database\Factories\RestaurantTableFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class RestaurantTable extends Model
{
    /** @use HasFactory<RestaurantTableFactory> */
    use HasFactory;

    protected $fillable = ['restaurant_id', 'number', 'name', 'seats', 'qr_token', 'is_active'];

    protected $casts = ['seats' => 'integer', 'is_active' => 'boolean'];

    protected static function booted(): void
    {
        static::creating(function (self $table): void {
            $table->qr_token ??= Str::random(24);
        });
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function qrUrl(): string
    {
        $path = route('restaurant.table.menu', [$this->restaurant->slug, $this->qr_token], false);

        return request()->getSchemeAndHttpHost().$path;
    }
}
