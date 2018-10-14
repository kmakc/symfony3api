<?php

namespace AppBundle\Resource\Filtering\Movie;

use Symfony\Component\HttpFoundation\Request;

class MovieFilterDefinitionFactory
{
    private const ACCEPTED_SORT_FIELDS = ['id', 'title', 'year', 'time'];

    /**
     * @param Request $request
     *
     * @return MovieFilterDefinition
     */
    public function factory(Request $request): MovieFilterDefinition
    {
        return new MovieFilterDefinition(
            $request->get('title'),
            $request->get('yearFrom'),
            $request->get('yearTo'),
            $request->get('timeFrom'),
            $request->get('timeTo'),
            $request->get('sortBy'),
            $this->sortQueryToArray($request->get('sortBy'))
        );
    }

    private function sortQueryToArray(?string $sortByQuery): ?array
    {
        if (null === $sortByQuery) {
            return null;
        }

        return array_intersect_key(array_reduce(
            explode(',', $sortByQuery),
            function ($carry, $item) {
                list($by, $order) = array_replace(
                    [1 => "desc"],
                    explode(' ', preg_replace('/\s+/', ' ', $item))
                    );
                $carry[$by] = $order;

                return $carry;
            },
            []
        ), array_flip(self::ACCEPTED_SORT_FIELDS));
    }
}
