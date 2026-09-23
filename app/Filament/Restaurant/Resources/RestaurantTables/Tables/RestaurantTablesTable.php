<?php

namespace App\Filament\Restaurant\Resources\RestaurantTables\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
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
                ViewColumn::make('qr_code')
                    ->label('QR Code')
                    ->view('filament.tables.qr-code'),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
                Action::make('openMenu')
                    ->label('فتح المنيو')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->url(fn ($record): string => $record->qrUrl())
                    ->openUrlInNewTab(),
                Action::make('printQr')
                    ->label('طباعة QR')
                    ->icon('heroicon-o-printer')
                    ->url(fn ($record): string => route('restaurant.table.qr', $record))
                    ->openUrlInNewTab(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
