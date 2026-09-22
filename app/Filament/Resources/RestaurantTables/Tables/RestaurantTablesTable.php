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
                TextColumn::make('number')
                    ->label('رقم الطاولة')
                    ->badge()
                    ->color('primary')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('name')
                    ->label('اسم الطاولة / الموقع')
                    ->searchable(),

                TextColumn::make('seats')
                    ->label('المقاعد')
                    ->numeric()
                    ->sortable(),

                IconColumn::make('is_active')
                    ->label('مفعلة')
                    ->boolean(),

                ImageColumn::make('qr_code_image')
                    ->label('رمز QR')
                    ->getStateUsing(fn ($record) => 'https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=' . urlencode($record->qrUrl()))
                    ->circular(),

                TextColumn::make('qr_url')
                    ->label('رابط المنيو')
                    ->state(fn ($record): string => $record->qrUrl())
                    ->copyable()
                    ->copyMessage('تم نسخ الرابط بنجاح!')
                    ->url(fn ($record): string => $record->qrUrl(), shouldOpenInNewTab: true)
                    ->limit(30),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                // زر تجربة ومشاهدة المنيو الخاص بالطاولة
                Action::make('open_menu')
                    ->label('اطلب طلبك معنا 🍽️')
                    ->icon('heroicon-o-arrow-top-right-on-square')
                    ->color('success')
                    ->url(fn ($record): string => $record->qrUrl(), shouldOpenInNewTab: true),

                // زر معاينة الـ QR
                Action::make('view_qr')
                    ->label('معاينة الـ QR')
                    ->icon('heroicon-o-qr-code')
                    ->color('info')
                    ->modalHeading(fn ($record) => 'رمز QR - طاولة رقم (' . $record->number . ')')
                    ->modalContent(fn ($record) => new HtmlString('
                        <div class="flex flex-col items-center justify-center p-6 text-center space-y-4">
                            <div class="p-4 bg-white rounded-2xl shadow-md border border-gray-100">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=220x220&data=' . urlencode($record->qrUrl()) . '" class="w-52 h-52 object-contain"/>
                            </div>
                            <div>
                                <h3 class="text-base font-bold text-gray-800">' . e($record->name ?? 'طاولة ' . $record->number) . '</h3>
                                <p class="text-xs text-gray-500 mt-1">امسح الكود عبر جوالك لعرض قائمة الطعام المباشرة</p>
                            </div>
                            <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data=' . urlencode($record->qrUrl()) . '" download="table-' . $record->number . '-qr.png" target="_blank" class="inline-flex items-center px-4 py-2 text-xs font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-lg shadow transition">
                                📥 تحميل صورة الرمز للطباعة
                            </a>
                        </div>
                    '))
                    ->modalSubmitAction(false)
                    ->modalCancelActionLabel('إغلاق'),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}