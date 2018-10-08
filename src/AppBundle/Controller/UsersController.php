<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Entity\EntityMerger;
use AppBundle\Security\TokenStorage;
use AppBundle\Exception\ValidationException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\RestBundle\Controller\ControllerTrait;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;

/**
 * @Security("is_anonymous() or is_authenticated()")
 */
class UsersController extends AbstractController
{

    use ControllerTrait;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var JWTEncoderInterface
     */
    private $JWTEncoder;

    /**
     * @var EntityMerger
     */
    private $entityMerger;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * UsersController constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param JWTEncoderInterface          $JWTEncoder
     * @param EntityMerger                 $entityMerger
     * @param TokenStorage                 $tokenStorage
     */
    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        JWTEncoderInterface          $JWTEncoder,
        EntityMerger                 $entityMerger,
        TokenStorage                 $tokenStorage
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->JWTEncoder      = $JWTEncoder;
        $this->entityMerger    = $entityMerger;
        $this->tokenStorage    = $tokenStorage;
    }

    /**
     * @Rest\View()
     * @Security("is_granted('show', theUser)", message="Access denied")
     */
    public function getUserAction(?User $theUser)
    {
        if (null === $theUser) {
            throw new NotFoundHttpException();
        }

        return $theUser;
    }

    /**
     * @Rest\View(statusCode=201)
     * @Rest\NoRoute()
     * @ParamConverter("user", converter="fos_rest.request_body",
     *     options={"deserializationContext"={"groups"={"Deserialize"}}})
     */
    public function postUserAction(
        User $user,
        ConstraintViolationListInterface $validationErrors
    ) {
        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        $this->encodePassword($user);
        $user->setRoles([User::ROLE_USER]);
        $this->persistUser($user);

        return $user;
    }

    /**
     * @Rest\NoRoute()
     * @ParamConverter("modifiedUser", converter="fos_rest.request_body",
     *     options={
     *          "validator"={"groups"={"Patch"}},
     *          "deserializationContext"={"groups"={"Deserialize"}}
     *     }
     * )
     * @Security("is_granted('edit', theUser)", message="Access denied")
     */
    public function patchUserAction(
        ?User $theUser,
        User $modifiedUser,
        ConstraintViolationListInterface $validationErrors
    ) {
        if (null === $theUser) {
            throw new NotFoundHttpException();
        }

        if (count($validationErrors) > 0) {
            throw new ValidationException($validationErrors);
        }

        if (empty($modifiedUser->getPassword())) {
            $modifiedUser->setPassword(null);
        }

        $this->entityMerger->merge($theUser, $modifiedUser);
        $this->encodePassword($theUser);
        $this->persistUser($theUser);

        if ($modifiedUser->getPassword()) {
            $this->tokenStorage->invalidateToken($theUser->getUsername());
        }

        return $theUser;
    }

    /**
     * @param User $user
     */
    protected function encodePassword(User $user): void
    {
        $user->setPassword($this->passwordEncoder->encodePassword($user, $user->getPassword()));
    }

    /**
     * @param User $user
     */
    protected function persistUser(User $user): void
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();
    }
}
