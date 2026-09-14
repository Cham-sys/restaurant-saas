<?php

namespace App\Filament\Pages;

use App\Services\RestaurantDashboardService;
use BackedEnum;
use Filament\Pages\Page;
use Filament\Panel;
use UnitEnum;

class RestaurantDashboard extends Page
{
    public string $view = 'filament.pages.restaurant-dashboard';

    protected static bool $shouldRegisterNavigation = true;

    protected static ?string $navigationLabel = 'لوحة التحكم';

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-home';

    protected static string|UnitEnum|null $navigationGroup = 'التشغيل';

    protected static ?int $navigationSort = 1;

    public static function getSlug(?Panel $panel = null): string
    {
        return '';
    }

    public function getViewData(): array
    {
        return app(RestaurantDashboardService::class)->forRestaurant(auth('web')->user()?->restaurant);
    }
}
