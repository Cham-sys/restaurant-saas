<?php

namespace App\Filament\Restaurant\Resources\Coupons;

use App\Filament\Restaurant\Resources\Coupons\Pages\CreateCoupon;
use App\Filament\Restaurant\Resources\Coupons\Pages\EditCoupon;
use App\Filament\Restaurant\Resources\Coupons\Pages\ListCoupons;
use App\Filament\Restaurant\Resources\Coupons\Schemas\CouponForm;
use App\Filament\Restaurant\Resources\Coupons\Tables\CouponsTable;
use App\Models\Coupon;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CouponResource extends Resource
{
    protected static ?string $model = Coupon::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationLabel = 'كوبونات الخصم';

    protected static string|UnitEnum|null $navigationGroup = 'التسويق';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'كوبون';

    protected static ?string $pluralModelLabel = 'كوبونات الخصم';

    protected static ?string $recordTitleAttribute = 'Coupon';

    public static function form(Schema $schema): Schema
    {
        return CouponForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CouponsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        if (auth()->user()->restaurant_id) {
            $query->where('restaurant_id', auth()->user()->restaurant_id);
        }

        return $query;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCoupons::route('/'),
            'create' => CreateCoupon::route('/create'),
            'edit' => EditCoupon::route('/{record}/edit'),
        ];
    }
}
