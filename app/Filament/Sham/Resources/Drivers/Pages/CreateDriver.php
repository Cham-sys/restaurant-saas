<?php

namespace App\Filament\Sham\Resources\Drivers\Pages;

use App\Filament\Sham\Resources\Drivers\DriverResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Support\Facades\Auth;

class CreateDriver extends CreateRecord
{
    protected static string $resource = DriverResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        $data['role'] = 'driver';
        $data['restaurant_id'] = Auth::user()->restaurant_id;
        $data['is_active'] = (bool) ($data['is_active'] ?? true);

        return $data;
    }
}
