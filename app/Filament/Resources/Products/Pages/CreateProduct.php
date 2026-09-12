<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Resources\Products\ProductResource;
use Filament\Resources\Pages\CreateRecord;

class CreateProduct extends CreateRecord
{
    use HandlesImageUrl;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mergeImageUrls($data, ['image']);
    }
}
