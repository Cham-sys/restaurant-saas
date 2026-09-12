<?php

namespace App\Filament\Resources\Categories\Pages;

use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Concerns\HandlesImageUrl;
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
