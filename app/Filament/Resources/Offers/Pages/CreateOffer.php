<?php

namespace App\Filament\Resources\Offers\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Resources\Offers\OfferResource;
use Filament\Resources\Pages\CreateRecord;

class CreateOffer extends CreateRecord
{
    use HandlesImageUrl;

    protected static string $resource = OfferResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mergeImageUrls($data, ['image']);
    }
}
