<?php

namespace App\Providers\Filament;

use App\Filament\Restaurant\Pages\RestaurantDashboard;
use App\Filament\Restaurant\Pages\RestaurantThemeSettings;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Coupons\CouponResource;
use App\Filament\Resources\Drivers\DriverResource;
use App\Filament\Resources\Invoices\InvoiceResource;
use App\Filament\Resources\Offers\OfferResource;
use App\Filament\Resources\Orders\OrderResource;
use App\Filament\Resources\Products\ProductResource;
use App\Filament\Resources\RestaurantTables\RestaurantTableResource;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets\AccountWidget;
use Filament\Widgets\FilamentInfoWidget;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\PreventRequestForgery;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class RestaurantPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->id('restaurant')
            ->path('restaurant')
            ->homeUrl(fn () => url('/restaurant'))
            ->login()
            ->brandName('لوحة المطاعم')
            ->colors([
                'primary' => Color::Orange,
                'gray' => Color::Zinc,
                'danger' => Color::Red,
                'info' => Color::Blue,
                'success' => Color::Emerald,
                'warning' => Color::Amber,
            ])
            ->font('Tajawal')
            ->spa() // تفعيل SPA
            ->sidebarCollapsibleOnDesktop()
            ->collapsibleNavigationGroups()
            ->navigationGroups([
                NavigationGroup::make('التشغيل')->icon('heroicon-o-command-line'),
                NavigationGroup::make('القائمة')->icon('heroicon-o-clipboard-document-list'),
                NavigationGroup::make('التسويق')->icon('heroicon-o-megaphone'),
                NavigationGroup::make('الإعدادات')->icon('heroicon-o-cog-6-tooth'),
            ])
            ->darkMode(false)
            ->viteTheme('resources/css/filament/restaurant/theme.css')
            ->resources([
                CategoryResource::class,
                ProductResource::class,
                OrderResource::class,
                InvoiceResource::class,
                OfferResource::class,
                CouponResource::class,
                DriverResource::class,
                RestaurantTableResource::class,
            ])
            ->discoverResources(in: app_path('Filament/Restaurant/Resources'), for: 'App\\Filament\\Restaurant\\Resources')
            ->discoverPages(in: app_path('Filament/Restaurant/Pages'), for: 'App\\Filament\\Restaurant\\Pages')
            ->pages([
                RestaurantDashboard::class,
                RestaurantThemeSettings::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                AccountWidget::class,
                FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                PreventRequestForgery::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
