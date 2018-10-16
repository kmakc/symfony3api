<?php

namespace AppBundle\Resource\Filtering;

interface FilterDefinitionInterface
{
    public function getQueryParameters(): array;
    public function getQueryParamsBlcaklist(): array;
    public function getParameters(): array;
}
