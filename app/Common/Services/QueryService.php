<?php

namespace App\Common\Services;

use Illuminate\Config\Repository;
use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Eloquent\Model;

/**
 * Class QueryService
 * @package App\Common\Service
 */
class QueryService
{
    /**
     * @var DatabaseManager
     */
    protected $db;

    /**
     * QueryHelper constructor.
     * @param DatabaseManager $db
     */
    public function __construct(DatabaseManager $db)
    {
        $this->db = $db;
    }

    /**
     * @param string $target
     * @param array $values
     * @param string|null $index
     * @param bool $raw
     * @return bool
     * @throws BindingResolutionException
     */
    public function updateMany(string $target, array $values, string $index = null, bool $raw = false)
    {
        $model = app()->make($target);
        $driver = $this->getDriver();
        $final = [];
        $ids = [];

        if (!count($values)) {
            return false;
        } elseif (!isset($index) || empty($index)) {
            $index = $model->getKeyName();
        }

        foreach ($values as $key => $val) {
            $ids[] = $val[$index];
            foreach (array_keys($val) as $field) {
                if ($field !== $index) {
                    $finalField = $raw ? escape($val[$field]) : "'" . escape($val[$field]) . "'";
                    $value = is_null($val[$field]) ? 'NULL' : $finalField;
                    $final[$field][] = 'WHEN "' . $index . '" = \'' . $val[$index] . '\' THEN ' . $value . ' ';
                }
            }
        }

        if ($driver == 'pgsql') {
            $cases = '';
            foreach ($final as $k => $v) {
                $cases .= '"' . $k . '" = (CASE ' . implode("\n", $v) . "\n" . 'ELSE "' . $k . '" END), ';
            }
            $query =
                "UPDATE \"" .
                $model->getTable() .
                '" SET ' .
                substr($cases, 0, -2) .
                " WHERE \"$index\" IN('" .
                implode("','", $ids) .
                "');";
        } else {
            $cases = '';
            foreach ($final as $k => $v) {
                $cases .= '`' . $k . '` = (CASE ' . implode("\n", $v) . "\n" . 'ELSE `' . $k . '` END), ';
            }
            $query =
                'UPDATE `' .
                $model->getTable() .
                '` SET ' .
                substr($cases, 0, -2) .
                " WHERE `$index` IN(" .
                '"' .
                implode('","', $ids) .
                '"' .
                ');';
        }

        return $this->execute($model, $query);
    }

    /**
     * @return Repository|Application|mixed
     */
    private function getDriver()
    {
        $connection = config('database.default');
        return config("database.connections.{$connection}.driver");
    }

    /**
     * @param Model $model
     * @param string $query
     * @return bool
     */
    public function execute(Model $model, string $query)
    {
        return $this->db->connection($model->getConnectionName())->statement($query);
    }
}
