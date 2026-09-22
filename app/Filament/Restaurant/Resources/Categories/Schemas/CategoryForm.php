<?php

namespace App\Filament\Restaurant\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('الاسم')
                    ->required(),
                TextInput::make('slug')
                    ->label('الرابط المختصر')
                    ->required(),
                Textarea::make('description')
                    ->label('الوصف')
                    ->columnSpanFull(),
                FileUpload::make('image')
                    ->label('الصورة')
                    ->image()
                    ->disk('public')
                    ->directory('categories')
                    ->maxSize(5120),
                TextInput::make('image_url')
                    ->label('أو رابط الصورة')
                    ->url()
                    ->maxLength(2048),
                TextInput::make('sort_order')
                    ->label('الترتيب')
                    ->required()
                    ->numeric()
                    ->default(0),
                Toggle::make('is_active')
                    ->label('مفعل')
                    ->required(),
            ]);
    }
}
