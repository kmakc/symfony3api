<?php

namespace AppBundle\Resource\Filtering\Role;

use AppBundle\Resource\Filtering\Person\PersonFilterDefinition;
use Doctrine\ORM\QueryBuilder;
use AppBundle\Repository\PersonRepository;
use AppBundle\Resource\Filtering\ResourceFilterInterface;

class PersonResourceFilter implements ResourceFilterInterface
{
    /**
     * @var PersonRepository
     */
    private $repository;

    public function __construct(PersonRepository $repository)
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
        $qb->select('person');

        return $qb;
    }

    /**
     * @param $filter
     *
     * @return QueryBuilder
     */
    public function getResourcesCount($filter): QueryBuilder
    {
        $qb = $this->getQuery($filter);
        $qb->select('count(person)');

        return $qb;
    }

    /**
     * @param PersonFilterDefinition $filter
     *
     * @return QueryBuilder
     */
    private function getQuery(PersonFilterDefinition $filter): QueryBuilder
    {
        $qb = $this->repository->createQueryBuilder('person');

        if (null !== $filter->getFirstName()) {
            $qb->where(
                $qb->expr()->like('person.firstName', ':firstName')
            );
            $qb->setParameter('firstName', "%{$filter->getFirstName()}%");
        }

        if (null !== $filter->getLastName()) {
            $qb->andWhere(
                $qb->expr()->like('person.lastName', ':lastName')
            );
            $qb->setParameter('lastName', "%{$filter->getLastName()}%");
        }

        if (null !== $filter->getBirthFrom()) {
            $qb->andWhere(
                $qb->expr()->gte('person.dateOfBirth', ':birthFrom')
            );
            $qb->setParameter('birthFrom', "%{$filter->getBirthFrom()}%");
        }

        if (null !== $filter->getBirthTo()) {
            $qb->andWhere(
                $qb->expr()->lte('person.dateOfBirth', ':birthTo')
            );
            $qb->setParameter('birthTo', "%{$filter->getBirthTo()}%");
        }



        if (null !== $filter->getSortByArray()) {
            foreach ($filter->getSortByArray() as $by => $order) {
                $expr = 'desc' == $order
                    ? $qb->expr()->desc("person.$by")
                    : $qb->expr()->asc("v.$by");

                $qb->addOrderBy($expr);
            }
        }

        return $qb;
    }
}