<?php

namespace App\Filament\Sham\Resources\Offers\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Sham\Resources\Offers\OfferResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditOffer extends EditRecord
{
    use HandlesImageUrl;

    protected static string $resource = OfferResource::class;

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
