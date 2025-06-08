<?php

declare(strict_types=1);

namespace App\Services;

use App\Models\Page;
use App\Repositories\PageRepository;
use Illuminate\Database\Eloquent\Collection;

final class PageService
{
    public function __construct(private readonly PageRepository $repository) {}

    public function getAll(): Collection
    {
        return $this->repository->getAll();
    }

    public function getById(int $id): Page
    {
        return $this->repository->getById($id);
    }
}
