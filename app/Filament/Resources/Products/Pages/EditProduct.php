<?php

namespace App\Filament\Resources\Products\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Resources\Products\ProductResource;
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
