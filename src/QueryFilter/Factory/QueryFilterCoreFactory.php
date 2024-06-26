<?php

namespace eloquentFilter\QueryFilter\Factory;

use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCoreBuilder;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\DB\DBBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\Eloquent\MainBuilderQueryByCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\SpecialCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereBetweenCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereByOptCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereDateCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereHasCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereInCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereLikeCondition;
use eloquentFilter\QueryFilter\Detection\ConditionsDetect\TypeQueryConditions\WhereOrCondition;

/**
 * Class QueryFilterCoreFactory.
 */
class QueryFilterCoreFactory
{
    public function createQueryFilterCoreEloquentBuilder(): QueryFilterCore
    {
        $mainBuilderConditions = new MainBuilderQueryByCondition();
        return app(QueryFilterCoreBuilder::class, ['defaultSeriesInjected' => $this->getDefaultDetectorsEloquent(), 'detectInjected' => null, 'mainBuilderConditions' => $mainBuilderConditions]);
    }

    public function createQueryFilterCoreDBQueryBuilder(): QueryFilterCore
    {
        $mainBuilderConditions = new DBBuilderQueryByCondition();
        return app(QueryFilterCoreBuilder::class, ['defaultSeriesInjected' => $this->getDefaultDetectorsEloquent(), 'detectInjected' => null, 'mainBuilderConditions' => $mainBuilderConditions]);
    }

    /**
     * @return array
     * @note DON'T CHANGE ORDER THESE BASED ON FLIMSY REASON.
     */
    private function getDefaultDetectorsEloquent(): array
    {
        return [
            SpecialCondition::class,
            WhereBetweenCondition::class,
            WhereByOptCondition::class,
            WhereLikeCondition::class,
            WhereInCondition::class,
            WhereOrCondition::class,
            WhereHasCondition::class,
            WhereDateCondition::class,
            WhereCondition::class,
        ];
    }
}
