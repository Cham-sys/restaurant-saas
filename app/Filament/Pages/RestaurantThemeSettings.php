<?php

namespace App\Filament\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;

class RestaurantThemeSettings extends Page
{
    public string $view = 'filament.pages.restaurant-theme-settings';

    protected static ?string $title = 'إعدادات المظهر';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = 'إعدادات المظهر';

    protected static bool $shouldRegisterNavigation = true;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'theme-settings';
    }
}
