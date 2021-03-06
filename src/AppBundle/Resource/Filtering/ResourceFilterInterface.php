<?php

namespace AppBundle\Resource\Filtering;

use Doctrine\ORM\QueryBuilder;

interface ResourceFilterInterface
{
    public function getResources($filter): QueryBuilder;
    public function getResourcesCount($filter): QueryBuilder;
}
