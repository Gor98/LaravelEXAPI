<?php

/**
 * return array value or null
 *
 * @param array $data
 * @param string $target
 * @return mixed|null
 */
function getVal(array $data, string $target)
{
    return isset($data[$target]) ? $data[$target] : null;
}
