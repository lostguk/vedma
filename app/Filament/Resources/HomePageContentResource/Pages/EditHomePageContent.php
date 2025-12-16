<?php

declare(strict_types=1);

namespace App\Filament\Resources\HomePageContentResource\Pages;

use App\Filament\Resources\HomePageContentResource;
use Filament\Resources\Pages\EditRecord;

final class EditHomePageContent extends EditRecord
{
    protected static string $resource = HomePageContentResource::class;

    protected array $categoriesData = [];

    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Загружаем категории с sort_order для Repeater
        $record = $this->record;
        $categories = $record->categories()->orderByPivot('sort_order')->get();

        $data['categories_data'] = $categories->map(function ($category) {
            return [
                'category_id' => $category->id,
            ];
        })->toArray();

        return $data;
    }

    protected function mutateFormDataBeforeSave(array $data): array
    {
        // Сохраняем данные категорий перед сохранением основной модели
        $this->categoriesData = $data['categories_data'] ?? [];
        // Удаляем categories_data из данных, так как это не поле модели
        unset($data['categories_data']);

        return $data;
    }

    protected function afterSave(): void
    {
        $record = $this->record;
        $categoriesData = $this->categoriesData ?? [];

        // Подготавливаем данные для синхронизации с sort_order
        $syncData = [];
        foreach ($categoriesData as $index => $item) {
            if (! empty($item['category_id'])) {
                $syncData[$item['category_id']] = ['sort_order' => $index + 1];
            }
        }

        // Синхронизируем категории с правильным порядком
        $record->categories()->sync($syncData);
    }
}
