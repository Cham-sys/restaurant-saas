<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Theme extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'author',
        'version',
        'description',
        'folder_name',
        'preview_image',
        'default_settings',
        'allowed_variables',
        'is_active',
        'is_default',
    ];

    protected $casts = [
        'default_settings' => 'array',
        'allowed_variables' => 'array',
        'is_active' => 'boolean',
        'is_default' => 'boolean',
    ];

    // العلاقات
    public function restaurants(): HasMany
    {
        return $this->hasMany(Restaurant::class);
    }

    public function themeSettings(): HasMany
    {
        return $this->hasMany(RestaurantThemeSetting::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    // Helper Methods
    public function getDefaultSetting(string $key, $default = null)
    {
        return $this->default_settings[$key] ?? $default;
    }

    public function getAllowedVariable(string $key, $default = null)
    {
        return $this->allowed_variables[$key] ?? $default;
    }

    public function getThemePath(): string
    {
        return resource_path("views/themes/{$this->folder_name}");
    }

    public function themeExists(): bool
    {
        return is_dir($this->getThemePath());
    }

    public function hasThemeJson(): bool
    {
        return file_exists($this->getThemePath().'/theme.json');
    }

    public function loadThemeJson(): ?array
    {
        $path = $this->getThemePath().'/theme.json';

        if (! file_exists($path)) {
            return null;
        }

        $content = file_get_contents($path);

        return json_decode($content, true);
    }

    public function activate(): bool
    {
        $this->update(['is_active' => true]);

        static::query()->whereKeyNot($this->getKey())->update(['is_active' => false]);

        return true;
    }

    public function deactivate(): bool
    {
        $this->update(['is_active' => false]);

        return true;
    }

    public function resetSettingsForRestaurants(): int
    {
        return RestaurantThemeSetting::query()
            ->where('theme_id', $this->getKey())
            ->delete();
    }

    public function duplicate(?string $name = null): ?self
    {
        $sourcePath = $this->getThemePath();

        if (! is_dir($sourcePath)) {
            return null;
        }

        $newName = trim((string) ($name ?: $this->name.' نسخة'));
        $folderName = Str::slug($newName ?: $this->folder_name.'-copy');

        if ($folderName === '' || $folderName === $this->folder_name) {
            $folderName = $this->folder_name.'-copy';
        }

        $targetPath = resource_path('views/themes/'.$folderName);
        $attempt = 1;

        while (is_dir($targetPath)) {
            $targetPath = resource_path('views/themes/'.$folderName.'-'.$attempt);
            $attempt++;
        }

        $folderName = basename($targetPath);

        if (! File::copyDirectory($sourcePath, $targetPath)) {
            return null;
        }

        $theme = $this->replicate();
        $theme->name = $newName ?: $this->name.' نسخة';
        $theme->slug = $folderName;
        $theme->folder_name = $folderName;
        $theme->is_active = false;
        $theme->is_default = false;
        $theme->save();

        return $theme;
    }
}
