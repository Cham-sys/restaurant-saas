<?php

namespace App\Filament\Sham\Resources\Invoices\Pages;

use App\Filament\Sham\Resources\Invoices\InvoiceResource;
use Filament\Resources\Pages\CreateRecord;

class CreateInvoice extends CreateRecord
{
    protected static string $resource = InvoiceResource::class;
}
