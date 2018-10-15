<?php

namespace AppBundle\Resource\Filtering;

abstract  class AbstractFilterDefinition implements FilterDefinitionInterface
{
    private const QUERY_PARAMS_BLACKLIST = ['sortByArray'];

    public function getQueryParameters(): array
    {
        return array_diff_key(
            get_object_vars($this),
            array_flip($this->getQueryParamsBlcaklist())
        );
    }

    public function getQueryParamsBlcaklist():array
    {
        return self::QUERY_PARAMS_BLACKLIST;
    }
}