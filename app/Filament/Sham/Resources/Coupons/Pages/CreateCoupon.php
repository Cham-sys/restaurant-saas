<?php

namespace App\Filament\Sham\Resources\Coupons\Pages;

use App\Filament\Sham\Resources\Coupons\CouponResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCoupon extends CreateRecord
{
    protected static string $resource = CouponResource::class;
}
