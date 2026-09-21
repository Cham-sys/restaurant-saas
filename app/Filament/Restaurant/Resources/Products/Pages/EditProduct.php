<?php

namespace App\Filament\Restaurant\Resources\Products\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Restaurant\Resources\Products\ProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    use HandlesImageUrl;

    protected static string $resource = ProductResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->mergeImageUrls($data, ['image']);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
