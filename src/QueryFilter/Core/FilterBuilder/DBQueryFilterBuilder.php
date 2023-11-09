<?php

namespace eloquentFilter\QueryFilter\Core\FilterBuilder;

use eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper;
use eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter;
use eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter;
use eloquentFilter\QueryFilter\Core\HelperEloquentFilter;
use eloquentFilter\QueryFilter\Core\ResolverDetections;
use eloquentFilter\QueryFilter\Factory\QueryBuilderWrapperFactory;

/**
 * Class DBQueryFilterBuilder.
 */
class DBQueryFilterBuilder
{
    use HelperEloquentFilter;

    protected $queryBuilderWrapper;

    /**
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\core\QueryFilterCore $queryFilterCore
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\IO\RequestFilter $requestFilter
     * @param \eloquentFilter\QueryFilter\Core\FilterBuilder\IO\ResponseFilter $responseFilter
     */
    public function __construct(public QueryFilterCore $queryFilterCore, public RequestFilter $requestFilter, public ResponseFilter $responseFilter)
    {
    }

    /**
     * @param \eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper $queryBuilderWrapper
     */
    public function setQueryBuilderWrapper(QueryBuilderWrapper $queryBuilderWrapper): void
    {
        $this->queryBuilderWrapper = $queryBuilderWrapper;
    }

    /**
     * @return \eloquentFilter\QueryFilter\Core\EloquentBuilder\QueryBuilderWrapper
     */
    public function getQueryBuilderWrapper(): QueryBuilderWrapper
    {
        return $this->queryBuilderWrapper;
    }

    /**
     * @param $builder
     * @param array|null $request
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @param array|null $detections_injected
     * @param array|null $black_list_detections
     *
     * @return void
     */
    public function apply($builder, array $request = null, array $ignore_request = null, array $accept_request = null, array $detections_injected = null, array $black_list_detections = null)
    {
        $this->setQueryBuilderWrapper(QueryBuilderWrapperFactory::createQueryBuilder($builder));

        $this->handleRequest(
            ignore_request: $ignore_request,
            accept_request: $accept_request
        );

        $this->resolveDetections($detections_injected, $black_list_detections);

        return $this->responseFilter->getResponse();
    }

    /**
     * @return void
     */
    private function resolveDetections($detections_injected, $black_list_detections)
    {
        $this->queryFilterCore->unsetDetection($black_list_detections);
        // $this->queryFilterCore->reload();
        $this->queryFilterCore->setDetectionsDbInjected($detections_injected);

        /** @see ResolverDetections */
        app()->bind('ResolverDetections', function () {
            return new ResolverDetections(
                $this->getQueryBuilderWrapper()->getBuilder(),
                $this->requestFilter->getRequest(),
                $this->queryFilterCore->getDetectFactory(),
                $this->queryFilterCore->getMainBuilderConditions()
            );
        });

        /** @see ResolverDetections::getResolverOut() */
        $responseResolver = app('ResolverDetections')->getResolverOut();


        $this->responseFilter->setResponse($responseResolver);
    }

    /**
     * @param array|null $ignore_request
     * @param array|null $accept_request
     * @return void
     */
    private function handleRequest(?array $ignore_request, ?array $accept_request): void
    {

        $serialize_request_filter = $this->requestFilter->getRequest();

        $this->requestFilter->requestAlter(
            ignore_request: $ignore_request,
            accept_request: $accept_request,
            serialize_request_filter: $serialize_request_filter,
            alias_list_filter: $alias_list_filter ?? [],
            model: $this->getQueryBuilderWrapper()->getModel(),
        );
    }

}
