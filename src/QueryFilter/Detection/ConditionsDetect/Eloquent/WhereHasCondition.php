<?php

namespace eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent;

use eloquentFilter\QueryFilter\Detection\DetectorConditionsContract;
use eloquentFilter\QueryFilter\Queries\WhereHas;

/**
 * Class WhereHasCondition.
 */
class WhereHasCondition implements DetectorConditionsContract
{
    /**
     * @param $field
     * @param $params
     * @param bool $is_override_method
     *
     * @return string|null
     */
    public static function detect($field, $params, bool $is_override_method = false): ?string
    {
        if (stripos($field, '.')) {
            $method = WhereHas::class;
        }

        return $method ?? null;
    }
}
