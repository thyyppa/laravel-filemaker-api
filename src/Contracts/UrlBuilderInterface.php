<?php

namespace Hyyppa\Filemaker\Contracts;

use Hyyppa\Filemaker\Connector\UrlBuilder;

interface UrlBuilderInterface
{
    /**
     * @param mixed $value
     *
     * @return \Hyyppa\Filemaker\Connector\UrlBuilder
     */
    public function append($value = null): UrlBuilder;

    /**
     * @param QueryStringInterface $params
     */
    public function queryString(QueryStringInterface $params): void;
}
