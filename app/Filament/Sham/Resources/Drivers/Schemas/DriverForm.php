<?php

namespace App\Filament\Sham\Resources\Drivers\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class DriverForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('name')
                ->label('اسم المندوب')
                ->required(),
            TextInput::make('email')
                ->label('البريد الإلكتروني')
                ->email()
                ->required()
                ->unique(ignoreRecord: true),
            TextInput::make('phone')
                ->label('رقم الهاتف')
                ->tel()
                ->required(),
            TextInput::make('password')
                ->label('كلمة المرور')
                ->password()
                ->required(fn (string $operation): bool => $operation === 'create')
                ->dehydrated(fn (?string $state): bool => filled($state)),
            Toggle::make('is_active')
                ->label('الحساب نشط')
                ->default(true),
            TextInput::make('role')
                ->default('driver')
                ->dehydrated()
                ->hidden(),
            TextInput::make('restaurant_id')
                ->default(fn (): mixed => auth()->user()->restaurant_id)
                ->dehydrated()
                ->hidden(),
        ]);
    }
}
