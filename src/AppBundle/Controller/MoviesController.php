<?php

namespace AppBundle\Controller;

use AppBundle\Entity\EntityMerger;
use AppBundle\Entity\Role;
use AppBundle\Entity\Movie;
use AppBundle\Exception\ValidationException;
use AppBundle\Repository\MovieRepository;
use FOS\RestBundle\Controller\ControllerTrait;
use Hateoas\Representation\CollectionRepresentation;
use Hateoas\Representation\PaginatedRepresentation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Validator\ConstraintViolationListInterface;

/**
 * @Security("is_anonymous() or is_authenticated()")
 */
class MoviesController extends AbstractController
{
    use ControllerTrait;

    /**
     * @var EntityMerger
     */
    private $entityMerger;

    public function __construct(EntityMerger $entityMerger)
    {
        $this->entityMerger = $entityMerger;
    }

    /**
     * @Rest\View()
     */
    public function getMoviesAction(Request $request)
    {
        $limit = $request->get('limit', 5);
        $page  = $request->get('page', 1);

        $offset = ($page - 1) * $limit;

        /** @var MovieRepository $repository */
        $repository = $this->getDoctrine()->getRepository('AppBundle:Movie');

        $movies     = $repository->findBy([], [], $limit, $offset);
        $movieCount = $repository->countMovies();

        $pageCount  = (int)ceil($movieCount / $limit);

        $collection = new CollectionRepresentation($movies);
        $paginated  = new PaginatedRepresentation(
            $collection,
            'get_movies',
            [],
            $page,
            $limit,
            $pageCount
        );

        return $paginated;
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("movie", converter="fos_rest.request_body")
     * @Rest\NoRoute()
     */
    public function postMoviesAction(Movie $movie, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($movie);
        $em->flush();

        return $movie;
    }

    /**
     * @Rest\View()
     */
    public function deleteMovieAction(?Movie $movie)
    {
        if (null === $movie) {
            return $this->view(null, 404);
        }

        $em = $this->getDoctrine()->getManager();
        $em->remove($movie);
        $em->flush();
    }

    /**
     * @Rest\View()
     */
    public function getMovieAction(?Movie $movie)
    {
        if (null === $movie) {
            return $this->view(null, 404);
        }

        return $movie;
    }

    /**
     * @Rest\View()
     */
    public function getMovieRolesAction(Request $request, Movie $movie)
    {
        $roles = $movie->getRoles();

        $limit = $request->get('limit', 3);
        $page  = $request->get('page', 1);

        $offset    = ($page - 1) * $limit;
        $pageCount = (int)ceil(count($roles) / $limit);

        $collection = new CollectionRepresentation(array_slice($roles->toArray(), $offset, $limit));
        $paginated  = new PaginatedRepresentation(
            $collection,
            'get_movie_roles',
            ['movie' => $movie->getId()],
            $page,
            $limit,
            $pageCount
        );

        return $paginated;
    }

    /**
     * @Rest\View(statusCode=201)
     * @ParamConverter("role", converter="fos_rest.request_body", options={"deserializationContext"={"groups"={"Deserialize"}}})
     * @Rest\NoRoute()
     */
    public function postMovieRolesAction(Movie $movie, Role $role, ConstraintViolationListInterface $validationErrors)
    {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $role->setMovie($movie);

        $em = $this->getDoctrine()->getManager();

        $em->persist($role);
        $movie->getRoles()->add($role);

        $em->persist($movie);
        $em->flush();

        return $role;
    }


    /**
     * @Rest\NoRoute()
     * @ParamConverter("modifiedMovie", converter="fos_rest.request_body",
     *     options={"validator" = {"groups" = {"Patch"}}}
     * )
     * @Security("is_authenticated()")
     */
    public function patchMovieAction(?Movie $movie, Movie $modifiedMovie, ConstraintViolationListInterface $validationErrors)
    {
        if (null === $movie) {
            return $this->view(null, 404);
        }

        if (count($validationErrors)) {
            throw new ValidationException($validationErrors);
        }

        // Merge entities
        $this->entityMerger->merge($movie, $modifiedMovie);

        // Persist
        $em = $this->getDoctrine()->getManager();
        $em->persist($movie);
        $em->flush();
        // Return
        return $movie;
    }
}
