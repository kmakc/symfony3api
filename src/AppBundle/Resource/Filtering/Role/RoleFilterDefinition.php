<?php

namespace AppBundle\Resource\Filtering\Role;

use AppBundle\Resource\Filtering\AbstractFilterDefinition;
use AppBundle\Resource\Filtering\FilterDefinitionInterface;
use AppBundle\Resource\Filtering\SortableFilterDefinitionInterface;

class RoleFilterDefinition extends AbstractFilterDefinition implements FilterDefinitionInterface, SortableFilterDefinitionInterface
{
    /**
     * @var string
     */
    private $playedName;

    /**
     * @var int
     */
    private $movie;

    /**
     * @var array
     */
    private $sortByy;

    /**
     * @var array
     */
    private $sortByArray;

    public function __construct(
        ?string $playedName,
        ?int    $movie,
        ?array  $sortBy,
        ?array  $sortByArray
    ) {

        $this->playedName  = $playedName;
        $this->movie       = $movie;
        $this->sortBy      = $sortBy;
        $this->sortByArray = $sortByArray;
    }

    /**
     * @return string
     */
    public function getPlayedName()
    {
        return $this->playedName;
    }

    /**
     * @return int
     */
    public function getMovie()
    {
        return $this->movie;
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
}
