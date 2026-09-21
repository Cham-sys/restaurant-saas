<?php

namespace App\Filament\Sham\Resources\Categories\Pages;

use App\Filament\Sham\Resources\Categories\CategoryResource;
use App\Filament\Resources\Concerns\HandlesImageUrl;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    use HandlesImageUrl;

    protected static string $resource = CategoryResource::class;

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
