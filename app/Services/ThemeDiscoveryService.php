<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\File;

class ThemeDiscoveryService
{
    /**
     * مسار مجلد الثيمات
     */
    protected string $themesPath;

    public function __construct()
    {
        $this->themesPath = resource_path('views/themes');
    }

    /**
     * اكتشاف كل الثيمات الموجودة في المجلد
     */
    public function discover(): array
    {
        $discoveredThemes = [];

        if (!File::isDirectory($this->themesPath)) {
            return $discoveredThemes;
        }

        $directories = File::directories($this->themesPath);

        foreach ($directories as $directory) {
            $themeJsonPath = $directory . '/theme.json';

            // فقط المجلدات التي تحتوي على theme.json
            if (!File::exists($themeJsonPath)) {
                continue;
            }

            $themeJson = json_decode(File::get($themeJsonPath), true);

            if (!$themeJson) {
                continue;
            }

            $folderName = basename($directory);

            $discoveredThemes[] = [
                'folder_name' => $folderName,
                'name' => $themeJson['name'] ?? $folderName,
                'slug' => $themeJson['slug'] ?? $folderName,
                'author' => $themeJson['author'] ?? 'غير معروف',
                'version' => $themeJson['version'] ?? '1.0.0',
                'description' => $themeJson['description'] ?? '',
                'preview_image' => $themeJson['preview_image'] ?? null,
                'default_settings' => $themeJson['default_settings'] ?? [],
                'allowed_variables' => $themeJson['allowed_variables'] ?? [],
                'path' => $directory,
                'has_layout' => File::exists($directory . '/layout.blade.php'),
                'files_count' => count(File::files($directory)),
            ];
        }

        return $discoveredThemes;
    }

    /**
     * مزامنة الثيمات المكتشفة مع قاعدة البيانات
     */
    public function sync(): array
{
    $discovered = $this->discover();
    $synced = [];
    $created = 0;
    $updated = 0;
    $discoveredFolders = [];

    foreach ($discovered as $themeData) {
        $discoveredFolders[] = $themeData['folder_name'];

        $theme = Theme::where('folder_name', $themeData['folder_name'])->first();

        if ($theme) {
            $theme->update([
                'name' => $themeData['name'],
                'slug' => $themeData['slug'],
                'author' => $themeData['author'],
                'version' => $themeData['version'],
                'description' => $themeData['description'],
                'preview_image' => $themeData['preview_image'],
                'default_settings' => $themeData['default_settings'],
                'allowed_variables' => $themeData['allowed_variables'],
            ]);
            $updated++;
        } else {
            $theme = Theme::create([
                'folder_name' => $themeData['folder_name'],
                'name' => $themeData['name'],
                'slug' => $themeData['slug'],
                'author' => $themeData['author'],
                'version' => $themeData['version'],
                'description' => $themeData['description'],
                'preview_image' => $themeData['preview_image'],
                'default_settings' => $themeData['default_settings'],
                'allowed_variables' => $themeData['allowed_variables'],
                'is_active' => false, // 👈 جعل الحالة الافتراضية غير مفعلة
            ]);
            $created++;
        }

        $synced[] = $theme;
    }

    // 👈 حذف الثيمات من قاعدة البيانات إذا تم حذف مجلداتها من القرص (بشرط عدم ارتباطها بمطاعم)
    Theme::query()
        ->whereNotIn('folder_name', $discoveredFolders)
        ->doesntHave('restaurants')
        ->delete();

    return [
        'total_discovered' => count($discovered),
        'created' => $created,
        'updated' => $updated,
        'themes' => $synced,
    ];
}

    /**
     * التحقق من وجود ثيم معين
     */
    public function exists(string $folderName): bool
    {
        return File::isDirectory($this->themesPath . '/' . $folderName)
            && File::exists($this->themesPath . '/' . $folderName . '/theme.json');
    }

    /**
     * جلب بيانات theme.json لثيم معين
     */
    public function getThemeJson(string $folderName): ?array
    {
        $path = $this->themesPath . '/' . $folderName . '/theme.json';

        if (!File::exists($path)) {
            return null;
        }

        return json_decode(File::get($path), true);
    }
}