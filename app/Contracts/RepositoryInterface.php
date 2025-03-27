<?php

namespace App\Contracts;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    /**
     * Get all records
     */
    public function all(array $columns = ['*'], array $relations = []): Collection;

    /**
     * Get all trashed records
     */
    public function allTrashed(): Collection;

    /**
     * Find record by id
     */
    public function findById(
        int $modelId,
        array $columns = ['*'],
        array $relations = [],
        array $appends = [],
    ): ?Model;

    /**
     * Find trashed record by id
     */
    public function findTrashedById(int $modelId): ?Model;

    /**
     * Find only trashed record by id
     */
    public function findOnlyTrashedById(int $modelId): ?Model;

    /**
     * Create a record
     */
    public function create(array $payload): ?Model;

    /**
     * Update existing record
     */
    public function update(int $modelId, array $payload): bool;

    /**
     * Delete record by id
     */
    public function deleteById(int $modelId): bool;

    /**
     * Restore record by id
     */
    public function restoreById(int $modelId): bool;

    /**
     * Permanently delete record by id
     */
    public function permanentlyDeleteById(int $modelId): bool;
}
