<?php

namespace eloquentFilter\QueryFilter\Queries\Eloquent;

use eloquentFilter\QueryFilter\Queries\BaseClause;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class WhereOr.
 */
class WhereOr extends BaseClause
{
    /**
     * @param $query
     *
     * @return Builder
     */
    public function apply($query)
    {
        $field = key($this->values);
        $value = reset($this->values);

        return $query->orWhere($field, $value);
    }
}
