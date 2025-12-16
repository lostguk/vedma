<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\HomePageContent;

final class HomePageContentRepository extends BaseRepository
{
    public function __construct(HomePageContent $model)
    {
        parent::__construct($model);
    }

    public function getSingle(): HomePageContent
    {
        /** @var HomePageContent $record */
        $record = $this->model->newQuery()
            ->with([
                'categories' => function ($query) {
                    $query->with('media');
                },
            ])
            ->firstOrFail();

        return $record;
    }
}
