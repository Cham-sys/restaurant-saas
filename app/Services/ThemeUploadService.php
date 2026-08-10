<?php

namespace App\Services;

use App\Models\Theme;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use ZipArchive;

class ThemeUploadService
{
    protected string $themesPath;
    
    protected array $requiredFiles = [
        'theme.json',
        'layout.blade.php',
        'pages/home.blade.php',
        'pages/menu.blade.php',
        'pages/product.blade.php',
        'cart/cart.blade.php',
        'cart/checkout.blade.php',
        'cart/success.blade.php',
        'orders/track.blade.php',
        'orders/track-form.blade.php',
        'orders/invoice.blade.php',
        'reviews/reviews.blade.php',
        'reviews/review-form.blade.php',
    ];

    public function __construct()
    {
        $this->themesPath = resource_path('views/themes');
    }

    /**
     * رفع وفك ضغط ثيم جديد
     */
    public function upload(string $zipPath, ?string $customFolderName = null): array
    {
        // 1. التحقق من وجود الملف
        if (!File::exists($zipPath)) {
            return [
                'success' => false,
                'message' => 'ملف ZIP غير موجود',
            ];
        }

        // 2. إنشاء مجلد مؤقت لفك الضغط
        $tempDir = storage_path('app/temp/themes/' . Str::random(16));
        File::makeDirectory($tempDir, 0755, true);

        try {
            // 3. فك الضغط
            $zip = new ZipArchive();
            if ($zip->open($zipPath) !== true) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'فشل في فك ضغط الملف. تأكد من أن الملف ZIP صالح.',
                ];
            }

            $zip->extractTo($tempDir);
            $zip->close();

            // 4. البحث عن المجلد الرئيسي (قد يكون داخل مجلد فرعي)
            $themeDir = $this->findThemeDirectory($tempDir);
            
            if (!$themeDir) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'لم يتم العثور على ملفات الثيم داخل الـ ZIP. تأكد من أن الملفات في المجلد الرئيسي.',
                ];
            }

            // 5. التحقق من theme.json
            $themeJsonPath = $themeDir . '/theme.json';
            if (!File::exists($themeJsonPath)) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'ملف theme.json غير موجود. هذا الملف إلزامي لكل ثيم.',
                ];
            }

            $themeJson = json_decode(File::get($themeJsonPath), true);
            if (!$themeJson) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'ملف theme.json غير صالح. تأكد من أنه JSON صحيح.',
                ];
            }

            // 6. التحقق من الحقول الأساسية في theme.json
            $requiredJsonFields = ['name', 'slug', 'version'];
            foreach ($requiredJsonFields as $field) {
                if (!isset($themeJson[$field])) {
                    File::deleteDirectory($tempDir);
                    return [
                        'success' => false,
                        'message' => "الحقل '{$field}' مفقود في theme.json. هذا الحقل إلزامي.",
                    ];
                }
            }

            // 7. التحقق من الملفات الأساسية المطلوبة
            $missingFiles = [];
            foreach ($this->requiredFiles as $requiredFile) {
                if (!File::exists($themeDir . '/' . $requiredFile)) {
                    $missingFiles[] = $requiredFile;
                }
            }

            if (!empty($missingFiles)) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => 'الملفات التالية مفقودة من الثيم: ' . implode(', ', $missingFiles),
                ];
            }

            // 8. تحديد اسم المجلد
            $folderName = $customFolderName ?? $themeJson['slug'] ?? Str::slug($themeJson['name']);
            $folderName = Str::slug($folderName);
            $targetDir = $this->themesPath . '/' . $folderName;

            // 9. التحقق من عدم وجود ثيم بنفس الاسم
            if (File::isDirectory($targetDir)) {
                File::deleteDirectory($tempDir);
                return [
                    'success' => false,
                    'message' => "يوجد ثيم بالفعل بالمجلد '{$folderName}'. اختر اسماً مختلفاً أو احذف الثيم القديم.",
                ];
            }

            // 10. نقل الملفات إلى المجلد النهائي
            File::moveDirectory($themeDir, $targetDir);
            
            // 11. حذف المجلد المؤقت
            File::deleteDirectory($tempDir);

            // 12. تسجيل الثيم في قاعدة البيانات
            $theme = Theme::create([
                'name' => $themeJson['name'],
                'slug' => $themeJson['slug'],
                'folder_name' => $folderName,
                'author' => $themeJson['author'] ?? 'غير معروف',
                'version' => $themeJson['version'],
                'description' => $themeJson['description'] ?? '',
                'preview_image' => $themeJson['preview_image'] ?? null,
                'default_settings' => $themeJson['default_settings'] ?? [],
                'allowed_variables' => $themeJson['allowed_variables'] ?? [],
                'is_active' => true,
                'is_default' => false,
            ]);

            return [
                'success' => true,
                'message' => 'تم رفع الثيم بنجاح',
                'theme' => $theme,
                'folder_name' => $folderName,
            ];

        } catch (\Exception $e) {
            // في حالة الخطأ، حذف كل شيء
            if (File::isDirectory($tempDir)) {
                File::deleteDirectory($tempDir);
            }
            
            return [
                'success' => false,
                'message' => 'حدث خطأ أثناء رفع الثيم: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * البحث عن مجلد الثيم داخل المجلد المؤقت
     */
    protected function findThemeDirectory(string $tempDir): ?string
    {
        // إذا كان theme.json في المجلد الرئيسي
        if (File::exists($tempDir . '/theme.json')) {
            return $tempDir;
        }

        // البحث في المجلدات الفرعية
        $directories = File::directories($tempDir);
        foreach ($directories as $dir) {
            if (File::exists($dir . '/theme.json')) {
                return $dir;
            }
        }

        return null;
    }

    /**
     * حذف ثيم بالكامل
     */
    public function delete(Theme $theme): array
    {
        $themeDir = $this->themesPath . '/' . $theme->folder_name;

        // حذف الملفات
        if (File::isDirectory($themeDir)) {
            File::deleteDirectory($themeDir);
        }

        // حذف من قاعدة البيانات
        $theme->delete();

        return [
            'success' => true,
            'message' => 'تم حذف الثيم بنجاح',
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
}