<?php

namespace App\Filament\Resources\Restaurants\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Resources\Restaurants\RestaurantResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRestaurant extends CreateRecord
{
    use HandlesImageUrl;

    protected static string $resource = RestaurantResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mergeImageUrls($data, ['logo', 'cover_image', 'qr_code_image']);
    }
}
