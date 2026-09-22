<?php

namespace App\Filament\Restaurant\Resources\Products;

use App\Filament\Restaurant\Resources\Products\Pages\CreateProduct;
use App\Filament\Restaurant\Resources\Products\Pages\EditProduct;
use App\Filament\Restaurant\Resources\Products\Pages\ListProducts;
use App\Filament\Restaurant\Resources\Products\Schemas\ProductForm;
use App\Filament\Restaurant\Resources\Products\Tables\ProductsTable;
use App\Models\Product;
use App\Models\User;
use App\Policies\ProductPolicy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-bolt';

    protected static ?string $modelPolicy = ProductPolicy::class;

    protected static ?string $navigationLabel = 'المنتجات';

    protected static string|UnitEnum|null $navigationGroup = 'القائمة';

    protected static ?int $navigationSort = 1;

    protected static ?string $modelLabel = 'منتج';

    protected static ?string $pluralModelLabel = 'المنتجات';

    protected static ?string $recordTitleAttribute = 'name';

    public static function form(Schema $schema): Schema
    {
        return ProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ProductsTable::configure($table);
    }

    public static function getEloquentQuery(): Builder
    {
        $query = parent::getEloquentQuery();

        $user = auth('web')->user();

        if ($user instanceof User && filled($user->restaurant_id)) {
            $query->where('restaurant_id', $user->restaurant_id);
        }

        return $query;
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
