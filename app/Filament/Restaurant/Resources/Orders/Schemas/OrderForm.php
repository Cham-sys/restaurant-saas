<?php

namespace App\Filament\Restaurant\Resources\Orders\Schemas;

use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->label('المستخدم')
                    ->numeric(),
                TextInput::make('customer_name')
                    ->label('اسم العميل')
                    ->required(),
                TextInput::make('customer_phone')
                    ->label('رقم الهاتف')
                    ->tel()
                    ->required(),
                Select::make('driver_id')
                    ->label('مندوب التوصيل')
                    ->options(fn (): array => User::query()
                        ->where('role', 'driver')
                        ->where('restaurant_id', auth()->user()->restaurant_id)
                        ->where('is_active', true)
                        ->pluck('name', 'id')
                        ->all())
                    ->searchable()
                    ->preload(),
                TextInput::make('customer_email')
                    ->label('البريد الإلكتروني')
                    ->email(),
                TextInput::make('delivery_type')
                    ->label('نوع التوصيل')
                    ->required()
                    ->default('delivery'),
                TextInput::make('restaurant_table_id')
                    ->label('رقم الطاولة')
                    ->numeric()
                    ->disabled(),
                Textarea::make('delivery_address')
                    ->label('عنوان التوصيل')
                    ->columnSpanFull(),
                TextInput::make('delivery_city')
                    ->label('المدينة'),
                TextInput::make('delivery_fee')
                    ->label('رسوم التوصيل')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('subtotal')
                    ->label('الإجمالي الفرعي')
                    ->required()
                    ->numeric(),
                TextInput::make('discount')
                    ->label('الخصم')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('total_amount')
                    ->label('الإجمالي')
                    ->required()
                    ->numeric(),
                TextInput::make('payment_status')
                    ->label('حالة الدفع')
                    ->required()
                    ->default('pending'),
                TextInput::make('payment_receipt')
                    ->label('إيصال الدفع'),
                TextInput::make('status')
                    ->label('الحالة')
                    ->required()
                    ->default('pending'),
                Textarea::make('notes')
                    ->label('الملاحظات')
                    ->columnSpanFull(),
                Textarea::make('rejection_reason')
                    ->label('سبب الرفض')
                    ->columnSpanFull(),
                TextInput::make('tracking_code')
                    ->label('رمز التتبع'),
                TextInput::make('rating')
                    ->label('التقييم')
                    ->numeric(),
                Textarea::make('review')
                    ->label('التعليق')
                    ->columnSpanFull(),
            ]);
    }
}
