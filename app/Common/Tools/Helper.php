<?php

use App\Common\Tools\Setting;
use Carbon\Carbon;

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

/**
 * @param object $object
 * @return string
 */
function getClassName(object $object): string
{
    return substr(strrchr(get_class($object), "\\"), 1);
}

/**
 * @param int $time
 * @return string
 */
function toDate($time): string
{
    return Carbon::now()->addSeconds($time)->format(Setting::DATE_TIME_FORMAT);
}
