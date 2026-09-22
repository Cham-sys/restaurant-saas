<?php

namespace App\Filament\Restaurant\Resources\Invoices\Pages;

use App\Filament\Restaurant\Resources\Invoices\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}
