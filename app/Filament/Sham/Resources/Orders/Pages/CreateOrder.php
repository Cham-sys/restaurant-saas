<?php

namespace App\Filament\Sham\Resources\Orders\Pages;

use App\Filament\Sham\Resources\Orders\OrderResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOrder extends CreateRecord
{
    protected static string $resource = OrderResource::class;
}
