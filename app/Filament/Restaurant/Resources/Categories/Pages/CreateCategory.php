<?php

namespace App\Filament\Restaurant\Resources\Categories\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Restaurant\Resources\Categories\CategoryResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCategory extends CreateRecord
{
    use HandlesImageUrl;

    protected static string $resource = CategoryResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mergeImageUrls($data, ['image']);
    }
}
