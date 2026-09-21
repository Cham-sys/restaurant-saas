<?php

namespace App\Filament\Sham\Resources\Restaurants\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Sham\Resources\Restaurants\RestaurantResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditRestaurant extends EditRecord
{
    use HandlesImageUrl;

    protected static string $resource = RestaurantResource::class;

    protected function mutateFormDataBeforeSave(array $data): array
    {
        return $this->mergeImageUrls($data, ['logo', 'cover_image', 'qr_code_image']);
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
