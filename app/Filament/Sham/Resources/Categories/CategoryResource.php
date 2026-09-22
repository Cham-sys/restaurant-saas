<?php

namespace App\Filament\Sham\Resources\Categories;

use App\Filament\Sham\Resources\Categories\Pages\CreateCategory;
use App\Filament\Sham\Resources\Categories\Pages\EditCategory;
use App\Filament\Sham\Resources\Categories\Pages\ListCategories;
use App\Filament\Sham\Resources\Categories\Schemas\CategoryForm;
use App\Filament\Sham\Resources\Categories\Tables\CategoriesTable;
use App\Models\Category;
use App\Models\User;
use App\Policies\CategoryPolicy;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use UnitEnum;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|BackedEnum|null $navigationIcon = 'heroicon-o-tag';

    protected static ?string $modelPolicy = CategoryPolicy::class;

    protected static ?string $navigationLabel = 'التصنيفات';

    protected static string|UnitEnum|null $navigationGroup = 'القائمة';

    protected static ?int $navigationSort = 2;

    protected static ?string $modelLabel = 'تصنيف';

    protected static ?string $pluralModelLabel = 'التصنيفات';

    protected static ?string $recordTitleAttribute = 'Category';

    public static function form(Schema $schema): Schema
    {
        return CategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CategoriesTable::configure($table);
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
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
