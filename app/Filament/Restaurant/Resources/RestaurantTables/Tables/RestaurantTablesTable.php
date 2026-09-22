<?php

namespace App\Filament\Restaurant\Resources\RestaurantTables\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\HtmlString;

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

                // 1. عمود صورة الـ QR
                ImageColumn::make('qr_code_image')
                    ->label('صورة QR')
                    ->getStateUsing(fn ($record) => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($record->qrUrl()))
                    ->square(),

                // 2. عمود رابط الـ QR
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
                // زر المنيو المباشر
                Action::make('open_menu')
                    ->label('اطلب طلبك معنا 🍽️')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn ($record): string => $record->qrUrl(), shouldOpenInNewTab: true),

                // زر عرض كود الـ QR
                Action::make('view_qr')
                    ->label('معاينة الـ QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('warning')
                    ->modalHeading(fn ($record) => 'رمز QR للطاولة: ' . $record->number)
                    ->modalContent(fn ($record) => new HtmlString('
                        <div class="flex flex-col items-center justify-center p-4 text-center">
                            <img src="https://api.qrserver.com/v1/create-qr-code/?size=250x250&data=' . urlencode($record->qrUrl()) . '" class="rounded-lg shadow-md mb-3"/>
                            <p class="text-sm text-gray-500">امسح الكود لتجربة المنيو والمشاهدة بنفس شكل الجوال</p>
                        </div>
                    '))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('إغلاق'),

                // زر التعديل الأصلي
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}