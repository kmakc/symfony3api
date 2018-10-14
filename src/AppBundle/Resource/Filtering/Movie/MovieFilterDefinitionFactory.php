<?php

namespace AppBundle\Resource\Filtering\Movie;

use Symfony\Component\HttpFoundation\Request;

class MovieFilterDefinitionFactory
{
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
            $request->get('timeTo')
        );
    }
}