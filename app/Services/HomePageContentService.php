<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\HomePageContent;
use App\Repositories\HomePageContentRepository;

final readonly class HomePageContentService
{
    public function __construct(
        private HomePageContentRepository $repository,
    ) {
    }

    public function getHomePageContent(): HomePageContent
    {
        return $this->repository->getSingle();
    }
}
