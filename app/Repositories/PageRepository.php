<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Page;
use Illuminate\Database\Eloquent\Collection;

final class PageRepository
{
    public function getAll(): Collection
    {
        return Page::all();
    }

    public function getById(int $id): Page
    {
        return Page::findOrFail($id);
    }
}
