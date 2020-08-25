<?php

namespace App\Common\Bases;

use App\Common\Contracts\RepositoryContract;
use App\Common\Exceptions\RepositoryException;
use App\Common\Tools\Setting;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use phpDocumentor\Reflection\Types\Mixed_;
use PhpParser\Node\Expr\AssignOp\Mod;

/**
 * Class Repository
 * @package App\Common\Bases
 */
abstract class Repository
{
    /**
     * @var array
     */
    protected array $fillable = [];

    /**
     * @var array
     */
    protected array $meta = [
        'perPage' => setting::PAGE_SIZE,
        'columns' => setting::COLUMNS,
        'orderType' => setting::DESC,
        'orderBy' => setting::DEFAULT_ORDER,
    ];

    /**
     * @var Builder
     */
    protected Builder $query;

    /**
     * Repository constructor.
     * @throws RepositoryException
     */
    public function __construct()
    {
        $this->makeQuery();
    }

    /**
     * Specify Model class name
     *
     * @return String
     */
    abstract protected function model(): string;

    /**
     * This method will fill the given $object by the given $array.
     * If the $fillable parameter is not available it will use the fillable
     * array of the class.
     *
     * @param array $data
     * @param Model $object
     * @param array $fillable
     * @return mixed
     */
    public function fill(array $data, Model $object, array $fillable = []): Model
    {
        if (empty($fillable)) {
            $fillable = $this->fillable;
        }
        if (!empty($fillable)) {
            // just fill when fillable array is not empty
            $object->fillable($fillable)->fill($data);
        }

        return $object;
    }

    /**
     * wrap object
     *
     * @param object $object
     * @return object
     */
    public function load(object $object): object
    {
        return $object;
    }

    /**
     * Return all rows from table.
     *
     * @param array|string[] $columns
     * @return Builder[]|Collection
     */
    public function all(array $columns = ['*']): Collection
    {
        return $this->query->get($columns);
    }

    /**
     * Return multi rows from table.
     *
     * @param int $perPage
     * @param array $columns
     * @return LengthAwarePaginator
     */
    public function paginate(int $perPage = Setting::PAGE_SIZE, array $columns = ['*']): LengthAwarePaginator
    {
        return $this->query->paginate($perPage, $columns);
    }

    /**
     * @param Model $entity
     * @return Model
     * @throws RepositoryException
     */
    public function save(Model $entity): Model
    {
        if (get_class($entity) != $this->model()) {
            throw new RepositoryException(
                'You can use save method on ' . static::class . ' for class of type ' . $this->model()
            );
        }
        $entity->save();

        return $entity;
    }

    /**
     * Save values in table.
     *
     * @param array $data
     * @param array $fillable
     * @return mixed
     * @throws RepositoryException
     */
    public function create(array $data, array $fillable = []): Model
    {
        $object = $this->fill($data, $this->makeModel(), $fillable);
        $object->save();

        return $object;
    }

    /**
     * Update values in table.
     *
     * @param array $data
     * @param Model|int|array $object
     * @param array $fillable
     * @return Model
     */
    public function update(array $data, $object, array $fillable = []): Model
    {
        $object = $this->fetch($object);
        $object = $this->fill($data, $object, $fillable);
        $object->save();

        return $object;
    }

    /**
     * Delete row from table.
     *
     * @param Model|array|int $object
     * @return mixed
     * @throws Exception
     */
    public function delete($object): Mixed_
    {
        $object = $this->fetch($object);

        return $object->delete();
    }

    /**
     * @param Model|array|int $object
     * @return Model|mixed|Mixed_
     */
    public function fetch($object): Model
    {
        if (!($object instanceof Model) && is_int($object)) {
            $object = $this->find($object);
        }
        if (is_array($object)) {
            $array_key = array_key_first($object);
            $object = $this->findBy([$array_key => $object[$array_key]]);
        }

        return $object;
    }

    /**
     * Find a row from table.
     *
     * @param int $id
     * @param array|string[] $columns
     * @param array $relations
     * @param bool $throwException
     * @return Builder|Builder[]|Collection|Model|Mixed_
     */
    public function find(
        int $id,
        array $columns = ['*'],
        array $relations = [],
        bool $throwException = true
    ): Mixed_ {
        return $throwException
           ? $this->query->with($relations)->findOrFail($id, $columns)
           : $this->query->with($relations)->find($id, $columns);
    }

    /**
     * Find by column and value from table.
     *
     * @param array $credentials
     * @param array|string[] $columns
     * @param array $relations
     * @param bool $throwException
     * @return Builder|Builder[]|Collection|Model|Mixed_
     */
    public function findBy(
        array $credentials,
        array $columns = ['*'],
        array $relations = [],
        bool $throwException = true
    ): Mixed_ {
        return $throwException
           ? $this->query->with($relations)->where($credentials)->findOrFail($columns)
           : $this->query->with($relations)->where($credentials)->first($columns);
    }

    /**
     * @param array $credentials
     * @return bool
     */
    public function existBy(array $credentials): bool
    {
        return $this->query->where($credentials)->exists();
    }

    /**
     * Make query
     *
     * @return Builder
     * @throws RepositoryException
     */
    public function makeQuery(): Builder
    {
        return $this->query = $this->makeModel()->newQuery();
    }

    /**
     * Make model
     *
     * @return Model
     * @throws RepositoryException
     */
    public function makeModel(): Model
    {
        $model_class = $this->model();
        $model = new $model_class();

        if (!$model instanceof Model) {
            throw new RepositoryException(
                "Class {$this->model()} must be an instance of Illuminate\\Database\\Eloquent\\Model",
            );
        }

        return $model;
    }

    /**
     * return paginated collection sorted by given attribute using scopes
     *
     * @param array $filters
     * @param array $meta
     * @return LengthAwarePaginator|mixed
     */
    public function sortPaginate(array $filters = [], array $meta = []): LengthAwarePaginator
    {
        $this->meta = array_merge($this->meta, $meta);
        $q = $this->query;

        foreach ($filters as $scope) {
            $this->attachScope($q, $scope);
        }

        $q = !array_key_exists('search', $meta) ? $q : $q->search(getVal($meta, 'search'));

        return $q->orderBy($this->meta['orderBy'], $this->meta['orderType'])
           ->paginate($this->meta['perPage'], $this->meta['columns']);
    }

    /**
     * attach scope to query
     *
     * @param $query
     * @param $scope
     * @return mixed
     */
    public function attachScope(Builder $query, string $scope): Builder
    {
        if (is_array($scope)) {
            foreach ($scope as $filter) {
                $query = $query->{$filter}();
            }
        } else {
            $query = $query->{$scope}();
        }

        return $query;
    }
}
