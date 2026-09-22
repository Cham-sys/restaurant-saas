<?php

namespace App\Filament\Resources\RestaurantTables\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;

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
                
                // عمود صورة الـ QR
                ImageColumn::make('qr_code_image')
                    ->label('صورة QR')
                    ->getStateUsing(fn ($record) => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($record->qrUrl()))
                    ->square(),

                // عمود رابط الـ QR (مصحح بالكامل مع TextColumn)
                TextColumn::make('qr_link')
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