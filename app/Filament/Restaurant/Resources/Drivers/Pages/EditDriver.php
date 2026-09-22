<?php

namespace App\Filament\Restaurant\Resources\Drivers\Pages;

use App\Filament\Restaurant\Resources\Drivers\DriverResource;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditDriver extends EditRecord
{
    protected static string $resource = DriverResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        $data['role'] = 'driver';
        $data['restaurant_id'] = Auth::user()->restaurant_id;

        return $data;
    }
}
