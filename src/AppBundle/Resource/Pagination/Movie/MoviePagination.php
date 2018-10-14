<?php

namespace AppBundle\Resource\Pagination\Movie;

use AppBundle\Resource\Pagination\Page;
use Doctrine\ORM\UnexpectedResultException;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use AppBundle\Resource\Filtering\Movie\MovieFilterDefinition;
use AppBundle\Resource\Filtering\Movie\MovieResourceFilter;

class MoviePagination
{
    /**
     * @var MovieResourceFilter
     */
    private $resourceFilter;

    public function __construct(MovieResourceFilter $resourceFilter)
    {
        $this->resourceFilter = $resourceFilter;
    }

    public function paginate(Page $page, MovieFilterDefinition $filter): PaginatedRepresentation
    {
        $resources = $this->resourceFilter->getResources($filter)
            ->setFirstResult($page->getOffset())
            ->setMaxResults($page->getLimit())
            ->getQuery()
            ->getResult();

        $resourceCount = $pages = null;

        try {
            $resourceCount = $this->resourceFilter->getResourcesCount($filter)
                ->getQuery()
                ->getSingleScalarResult();

            $pages = ceil($resourceCount / $page->getLimit());
        } catch(UnexpectedResultException $e) {

        }

        return new PaginatedRepresentation(
            new CollectionRepresentation($resources),
            'get_movies',
            $filter->getQueryParameters(),
            $page->getPage(),
            $page->getLimit(),
            $pages,
            null,
            null,
            false,
            $resourceCount
        );
    }
}
