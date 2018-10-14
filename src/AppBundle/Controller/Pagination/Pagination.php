<?php

namespace AppBundle\Controller\Pagination;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Symfony\Component\HttpFoundation\Request;

class Pagination
{
    private const KEY_LIMIT     = 'limit';
    private const KEY_PAGE      = 'page';
    private const DEFAULT_LIMIT = 5;
    private const DEFAULT_PAGE  = 1;

    /**
     * @var Registry
     */
    private $doctrineRegistry;

    public function __construct(Registry $doctrineRegistry)
    {
        $this->doctrineRegistry = $doctrineRegistry;
    }

    /**
     * @param Request $request
     * @param string  $entityName
     * @param array   $criteria
     * @param string  $countMethod
     * @param array   $countMethodParameters
     * @param string  $route
     * @param array   $routeParameters
     *
     * @return PaginatedRepresentation
     */
    public function paginate(
        Request $request,
        string  $entityName,
        array   $criteria,
        string  $countMethod,
        array   $countMethodParameters,
        string  $route,
        array   $routeParameters
    ): PaginatedRepresentation
    {
        $limit  = $request->get(self::KEY_LIMIT, self::DEFAULT_LIMIT);
        $page   = $request->get(self::KEY_PAGE, self::DEFAULT_PAGE);
        $offset = ($page - 1) * $limit;

        $repository = $this->doctrineRegistry->getRepository($entityName);
        $resources  = $repository->findBy($criteria, null, $limit, $offset);

        if (!method_exists($repository, $countMethod)) {
            throw new \InvalidArgumentException("Entity repository method $countMethod does not exists");
        }

        $resourceCount = $repository->{$countMethod}(...$countMethodParameters);
        $pageCount     = (int)ceil($resourceCount / $limit);

        $collection = new CollectionRepresentation($resources);

        return new PaginatedRepresentation(
            $collection,
            $route,
            $routeParameters,
            $page,
            $limit,
            $pageCount
        );
    }
}
