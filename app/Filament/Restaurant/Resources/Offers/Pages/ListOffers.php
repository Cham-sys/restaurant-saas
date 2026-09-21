<?php

namespace App\Filament\Restaurant\Resources\Offers\Pages;

use App\Filament\Restaurant\Resources\Offers\OfferResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListOffers extends ListRecords
{
    protected static string $resource = OfferResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
