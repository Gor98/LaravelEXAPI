<?php

namespace App\Common\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Mixed_;

/**
 * Interface RepositoryContract
 * @package App\Common\Contracts
 */
interface RepositoryContract
{
    public function fill(array $data, Model $object, array $fillable = []): Model;
    public function all(array $columns): Collection;
    public function update(array $data, $object, array $fillable = []): Model;
    public function save(Model $object): Model;
    public function create(array $data, array $fillable = []): Model;
    public function delete($object);
    public function fetch($object): Model;
    public function find(int $id, array $columns, array $relations, bool $throwException): Mixed_;
    public function findBy(array $credentials, array $columns, array $relations, bool $throwException): Mixed_;
    public function makeQuery(): Builder;
    public function makeModel(): Model;
    public function sortPaginate(array $filters, array $meta): LengthAwarePaginator;
    public function attachScope(Builder $query, string $scope): Builder;
}
