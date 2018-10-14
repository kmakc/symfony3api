<?php

namespace AppBundle\Resource\Pagination;

class Page
{
    /**
     * @var int
     */
    private $page;

    /**
     * @var int
     */
    private $limit;

    /**
     * @var int
     */
    private $offset;

    public function __construct(int $page, int $limit)
    {

        $this->page   = $page;
        $this->limit  = $limit;
        $this->offset = ($page - 1) * $limit;
    }

    /**
     * @return int
     */
    public function getPage()
    {
        return $this->page;
    }

    /**
     * @return int
     */
    public function getLimit()
    {
        return $this->limit;
    }

    /**
     * @return int
     */
    public function getOffset()
    {
        return $this->offset;
    }
}
