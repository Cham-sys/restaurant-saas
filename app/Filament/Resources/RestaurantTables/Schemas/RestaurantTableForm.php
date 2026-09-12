<?php

namespace App\Filament\Resources\RestaurantTables\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RestaurantTableForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('number')->label('رقم الطاولة')->required()->maxLength(50),
                TextInput::make('name')->label('اسم الطاولة')->maxLength(255),
                TextInput::make('seats')->label('عدد المقاعد')->numeric()->minValue(1)->required()->default(2),
                Toggle::make('is_active')->label('مفعلة')->default(true),
                TextInput::make('restaurant_id')
                    ->default(fn (): mixed => auth()->user()->restaurant_id)
                    ->hidden()
                    ->dehydrated(),
            ]);
    }
}
