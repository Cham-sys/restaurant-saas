<?php

namespace App\Filament\Sham\Resources\Themes\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Sham\Resources\Themes\ThemeResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditTheme extends EditRecord
{
    use HandlesImageUrl;

    protected static string $resource = ThemeResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->mergeImageUrls($data, ['preview_image']);
    }

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
