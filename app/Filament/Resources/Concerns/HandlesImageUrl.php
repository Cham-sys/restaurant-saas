<?php

namespace App\Filament\Resources\Concerns;

trait HandlesImageUrl
{
    protected function mergeImageUrls(array $data, array $fields): array
    {
        foreach ($fields as $field) {
            $urlField = "{$field}_url";

            if (filled($data[$urlField] ?? null)) {
                $data[$field] = $data[$urlField];
            }

            unset($data[$urlField]);
        }

        return $data;
    }
}
