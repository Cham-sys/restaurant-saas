<?php

namespace App\Filament\Restaurant\Pages;

use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use UnitEnum;

class RestaurantThemeSettings extends Page
{
    public string $view = 'filament.pages.restaurant-theme-settings';

    protected static ?string $title = 'إعدادات المظهر';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-paint-brush';

    protected static ?string $navigationLabel = 'إعدادات المظهر';

    protected static string|UnitEnum|null $navigationGroup = 'الإعدادات';

    protected static ?int $navigationSort = 1;

    protected static bool $shouldRegisterNavigation = true;

    public static function getSlug(?Panel $panel = null): string
    {
        return 'theme-settings';
    }
}
