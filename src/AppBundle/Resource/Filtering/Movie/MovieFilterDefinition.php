<?php

namespace AppBundle\Resource\Filtering\Movie;

class MovieFilterDefinition
{
    private const QUERY_PARAMS_BLACKLIST = ['sortByArray'];

    /**
     * @var null|string
     */
    private $title;

    /**
     * @var null|int
     */
    private $yearFrom;

    /**
     * @var null|int
     */
    private $yearTo;

    /**
     * @var null|int
     */
    private $timeFrom;

    /**
     * @var null|int
     */
    private $timeTo;
    /**
     * @var array
     */
    private $sortByArray;
    /**
     * @var string
     */
    private $sortByQuery;

    public function __construct(
        ?string $title,
        ?int    $yearFrom,
        ?int    $yearTo,
        ?int    $timeFrom,
        ?int    $timeTo,
        ?string $sortBy,
        ?array  $sortByArray
    )
    {
        $this->title       = $title;
        $this->yearFrom    = $yearFrom;
        $this->yearTo      = $yearTo;
        $this->timeFrom    = $timeFrom;
        $this->timeTo      = $timeTo;
        $this->sortByArray = $sortByArray;
        $this->sortBy      = $sortBy;
    }

    /**
     * @return null|string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * @return int|null
     */
    public function getYearFrom()
    {
        return $this->yearFrom;
    }

    /**
     * @return int|null
     */
    public function getYearTo()
    {
        return $this->yearTo;
    }

    /**
     * @return int|null
     */
    public function getTimeFrom()
    {
        return $this->timeFrom;
    }

    /**
     * @return int|null
     */
    public function getTimeTo()
    {
        return $this->timeTo;
    }

    public function getQueryParameters(): array
    {
        return array_diff_key(
            get_object_vars($this),
            array_flip(self::QUERY_PARAMS_BLACKLIST)
        );
    }

    /**
     * @return array
     */
    public function getSortByArray(): ?array
    {
        return $this->sortByArray;
    }

    /**
     * @return string
     */
    public function getSortBy(): ?string
    {
        return $this->sortBy;
    }
}
