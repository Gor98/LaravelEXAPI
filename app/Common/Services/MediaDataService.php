<?php

namespace App\Common\Services;

use App\Common\Bases\BaseService;
use App\Common\Constant\EntityName;
use App\Common\Exception\MediaDataException;
use App\Modules\Game\Entity\CheckpointData;
use App\Modules\Game\Repo\CheckpointDataRepo;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\DatabaseManager as DB;
use Throwable;

/**
 * Class MediaDataService
 * @package App\Modules\Game\Service
 */
class MediaDataService extends BaseService
{
    /**
     * @var CheckpointDataRepo
     */
    private CheckpointDataRepo $checkpointDataRepo;

    /**
     * @var QueryService
     */
    private QueryService $queryService;

    /**
     * @var StorageService
     */
    private StorageService $storageService;

    /**
     * @var DB
     */
    private DB $db;

    /**
     * CheckpointDataService constructor.
     * @param CheckpointDataRepo $checkpointDataRepo
     * @param QueryService $queryService
     * @param StorageService $storageService
     * @param DB $db
     */
    public function __construct(
        DB $db,
        CheckpointDataRepo $checkpointDataRepo,
        QueryService $queryService,
        StorageService $storageService
    ) {
        $this->db = $db;
        $this->checkpointDataRepo = $checkpointDataRepo;
        $this->queryService = $queryService;
        $this->storageService = $storageService;
    }

    /**
     * Store Game Instance.
     *
     * @param string $target
     * @param array $data
     * @return mixed
     * @throws BindingResolutionException
     * @throws Throwable
     */
    public function create(string $target, array $data): Model
    {
        $repo = app()->make($target);
        $this->db->beginTransaction();
        try {
            if (isset($data['media'])) {
                $data['type'] = $data['media']['type'];
                $data['media_location'] = $this->initFile($data['media']);
                $this->storageService->add($data['media_location'], file_get_contents($data['media']['file']));
            }

            $model = $repo->create($data);
            $model->translations()->createMany($data['translations']);
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw new MediaDataException();
        }
        $this->db->commit();

        return $model->fresh();
    }

    /**
     * @param string $target
     * @param array $data
     * @param Model $model
     * @return CheckpointData
     * @throws BindingResolutionException|Throwable
     */
    public function update(string $target, array $data, Model $model): Model
    {
        $repo = app()->make($target);

        $this->db->beginTransaction();
        try {
            if (isset($data['media'])) {
                $data['type'] = $data['media']['type'];
                $data['media_location'] = $this->initFile($data['media']);
                $this->storageService->remove($model->media_location);
                $this->storageService->add($data['media_location'], file_get_contents($data['media']['file']));
            }

            if (isset($data['translations']) &&
                !$this->containsTrans($model, collect($data['translations'])->pluck('id'))
            ) {
                $this->queryService->updateMany(EntityName::TRANSLATION, $data['translations'], 'id');
            }
            $repo->update($data, $model);
        } catch (\Exception $e) {
            $this->db->rollBack();
            throw new MediaDataException();
        }
        $this->db->commit();

        return $model;
    }

    /**
     * @param string $target
     * @param Model $model
     * @return bool|null
     * @throws BindingResolutionException
     */
    public function delete(string $target, Model $model)
    {
        $repo = app()->make($target);
        if ($model->media_location) {
            $this->storageService->remove($model->media_location);
        }

        return $repo->delete($model);
    }
}
