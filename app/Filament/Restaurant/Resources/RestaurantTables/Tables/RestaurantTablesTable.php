<?php

namespace App\Filament\Restaurant\Resources\RestaurantTables\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RestaurantTablesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('number')->label('رقم الطاولة')->searchable()->sortable(),
                TextColumn::make('name')->label('الاسم')->searchable(),
                TextColumn::make('seats')->label('المقاعد')->numeric()->sortable(),
                IconColumn::make('is_active')->label('مفعلة')->boolean(),
                TextColumn::make('qr_url')
                    ->label('رابط QR')
                    ->state(fn ($record): string => $record->qrUrl())
                    ->copyable()
                    ->url(fn ($record): string => route('restaurant.table.qr', $record), shouldOpenInNewTab: true)
                    ->limit(35),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
