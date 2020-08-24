<?php

namespace App\Common\Bases;

/**
 * Class BaseService
 * @package App\Common\Bases
 */
class Service
{
    /**
     * @param array $data
     * @return string
     */
    final public function initFile(array $data): string
    {
        $name = time() . '_' . $data['file']->getClientOriginalName();

        return $data['type'] . '/' . $name;
    }
}
