<?php

namespace App\Filament\Sham\Resources\Themes\Pages;

use App\Filament\Resources\Concerns\HandlesImageUrl;
use App\Filament\Sham\Resources\Themes\ThemeResource;
use App\Services\ThemeUploadService;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;

class CreateTheme extends CreateRecord
{
    use HandlesImageUrl;

    protected static string $resource = ThemeResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        return $this->mergeImageUrls($data, ['preview_image']);
    }

    protected function beforeCreate(): void
    {
        $zipFile = $this->data['theme_zip'] ?? null;

        if ($zipFile) {
            // تحويل ملف Filament المؤقت إلى مسار محلي حقيقي
            $zipPath = $zipFile->getRealPath();

            $service = app(ThemeUploadService::class);
            $result = $service->upload($zipPath);

            if ($result['success']) {
                // ملء بيانات النموذج من الثيم الذي تم إنشاؤه في الـ Service
                $this->data['name'] = $result['theme']->name;
                $this->data['slug'] = $result['theme']->slug;
                $this->data['folder_name'] = $result['theme']->folder_name;
            } else {
                Notification::make()
                    ->danger()
                    ->title('فشل رفع الثيم')
                    ->body($result['message'])
                    ->send();

                $this->halt();
            }
        }
    }
}
