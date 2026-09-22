<?php

namespace App\Filament\Restaurant\Resources\Products\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('category_id')
                    ->label('التصنيف')
                    ->relationship(
                        name: 'category',
                        titleAttribute: 'name',
                        modifyQueryUsing: fn ($query) => $query->where('restaurant_id', auth()->user()->restaurant_id)
                    )
                    ->required()
                    ->searchable()
                    ->preload()
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),
                TextInput::make('slug')
                    ->label('الرابط المختصر')
                    ->required(),
                Textarea::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),
                TextInput::make('price')
                    ->label('السعر')
                    ->required()
                    ->numeric()
                    ->prefix('$'),
                TextInput::make('old_price')
                    ->label('السعر القديم')
                    ->numeric()
                    ->prefix('$'),
                FileUpload::make('image')
                    ->label('الصورة')
                    ->image()
                    ->disk('public')
                    ->directory('products')
                    ->maxSize(5120),
                TextInput::make('image_url')
                    ->label('أو رابط الصورة')
                    ->url()
                    ->maxLength(2048)
                    ->helperText('اتركه فارغاً إذا رفعت صورة من جهازك.'),
                Toggle::make('is_available')
                    ->label('متوفر')
                    ->required(),
                Toggle::make('is_featured')
                    ->label('مميز')
                    ->required(),
                TextInput::make('sort_order')
                    ->label('الترتيب')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
