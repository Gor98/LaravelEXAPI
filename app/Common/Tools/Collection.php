<?php


namespace App\Common\Tools;

use Illuminate\Http\Resources\Json\ResourceCollection;

/**
 * Class Collection
 * @package App\Common\Tools
 */
class Collection extends ResourceCollection
{
    /**
     * @var array
     */
    protected array $pagination;

    /**
     * @var
     */
    public $resource;

    /**
     * Collection constructor.
     * @param $resource
     */
    public function __construct($resource)
    {
        $this->resource = $resource;
        $this->pagination = $this->constructPaginate();
        $resource = $resource->getCollection();
        parent::__construct($resource);
    }

    /**
     * @return array
     */
    public function constructPaginate()
    {
        return [
            'total' => $this->resource->total(),
            'count' => $this->resource->count(),
            'per_page' => $this->resource->perPage(),
            'current_page' => $this->resource->currentPage(),
            'total_pages' => $this->resource->lastPage()
        ];
    }

    /**
     * @return array
     */
    public function makeResponse(): array
    {
        return [
            'data' => $this->collection,
            'pagination' => $this->pagination
        ];
    }
}
