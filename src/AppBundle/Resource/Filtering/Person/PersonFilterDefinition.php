<?php

namespace AppBundle\Resource\Filtering\Person;

use AppBundle\Resource\Filtering\AbstractFilterDefinition;
use AppBundle\Resource\Filtering\FilterDefinitionInterface;
use AppBundle\Resource\Filtering\SortableFilterDefinitionInterface;

class PersonFilterDefinition extends AbstractFilterDefinition implements FilterDefinitionInterface, SortableFilterDefinitionInterface
{
    /**
     * @var null|string
     */
    private $firstName;

    /**
     * @var null|string
     */
    private $lastName;

    /**
     * @var null|string
     */
    private $birthFrom;

    /**
     * @var null|string
     */
    private $birthTo;

    /**
     * @var null|string
     */
    private $sortBy;

    /**
     * @var null|string
     */
    private $sortByArray;

    public function __construct(
        ?string $firstName,
        ?string $lastName,
        ?string $birthFrom,
        ?string $birthTo,
        ?string $sortByQuery,
        ?string $sortByArray
    ) {
        $this->firstName   = $firstName;
        $this->lastName    = $lastName;
        $this->birthFrom   = $birthFrom;
        $this->birthTo     = $birthTo;
        $this->sortBy      = $sortByQuery;
        $this->sortByArray = $sortByArray;
    }

    /**
     * @return array
     */
    public function getSortByQuery(): ?string
    {
        return $this->sortBy;
    }

    /**
     * @return array
     */
    public function getSortByArray(): ?array
    {
        return $this->sortByArray;
    }

    public function getParameters(): array
    {
        return get_object_vars($this);
    }

    /**
     * @return null|string
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @return null|string
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @return null|string
     */
    public function getBirthFrom()
    {
        return $this->birthFrom;
    }

    /**
     * @return null|string
     */
    public function getBirthTo()
    {
        return $this->birthTo;
    }
}
