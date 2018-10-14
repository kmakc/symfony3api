<?php

namespace AppBundle\Resource\Filtering\Movie;

use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\MovieRepository;

class MovieResourceFilter
{
    /**
     * @var MovieRepository
     */
    private $repository;

    /*
     * MovieResourceFilter constructor.
     *
     * @param MovieRepository $repository
     */
    public function __construct(MovieRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param $filter
     *
     * @return QueryBuilder
     */
    public function getResources($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter);
        $qb->select('movie');

        return $qb;
    }

    public function getResourcesCount($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter);
        $qb->select('count(movie)');

        return $qb;
    }

    /**
     * @param MovieFilterDefinition $filter
     * @return QueryBuilder
     */
    private function getQuery(MovieFilterDefinition $filter): QueryBuilder
    {
        $qb = $this->repository->createQueryBuilder('movie');

        if (null !== $filter->getTitle()) {
            $qb->where(
                $qb->expr()->like('movie.title', ':title')
            );

            $qb->setParameter('title', "%{$filter->getTitle()}%");
        }

        if (null !== $filter->getYearFrom()) {
            $qb->where(
                $qb->expr()->gte('movie.year', ':yearFrom')
            );

            $qb->setParameter('yearFrom', "%{$filter->getYearFrom()}%");
        }

        if (null !== $filter->getYearTo()) {
            $qb->where(
                $qb->expr()->lte('movie.year', ':yearTo')
            );

            $qb->setParameter('yearTo', "%{$filter->getYearTo()}%");
        }

        if (null !== $filter->getTimeFrom()) {
            $qb->where(
                $qb->expr()->gte('movie.time', ':timeFrom')
            );

            $qb->setParameter('timeFrom', "%{$filter->getTimeFrom()}%");
        }

        if (null !== $filter->getTimeTo()) {
            $qb->where(
                $qb->expr()->lte('movie.time', ':timeTo')
            );

            $qb->setParameter('timeTo', "%{$filter->getTimeTo()}%");
        }

        return $qb;
    }
}
