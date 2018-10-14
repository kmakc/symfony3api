<?php

namespace AppBundle\Resource\Filtering\Movie;

class MovieFilterDefinition
{
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

    public function __construct(
        ?string $title,
        ?int $yearFrom,
        ?int $yearTo,
        ?int $timeFrom,
        ?int $timeTo
    )
    {
        $this->title    = $title;
        $this->yearFrom = $yearFrom;
        $this->yearTo   = $yearTo;
        $this->timeFrom = $timeFrom;
        $this->timeTo   = $timeTo;
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
}
