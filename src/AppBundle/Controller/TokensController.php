<?php

namespace AppBundle\Controller;

use AppBundle\Security\TokenStorage;
use FOS\RestBundle\Controller\ControllerTrait;
use Lexik\Bundle\JWTAuthenticationBundle\Encoder\JWTEncoderInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\Security\Core\Exception\BadCredentialsException;

/**
 * @Security("is_anonymous() or is_authenticated()")
 */
class TokensController extends AbstractController
{
    use ControllerTrait;

    /**
     * @var UserPasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * @var JWTEncoderInterface
     */
    private $encoder;

    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    /**
     * TokensController constructor.
     *
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param JWTEncoderInterface $encoder
     */
    public function __construct(UserPasswordEncoderInterface $passwordEncoder, JWTEncoderInterface $encoder, TokenStorage $tokenStorage)
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->encoder = $encoder;
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Rest\View(statusCode=201)
     */
    public function postTokenAction(Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository('AppBundle:User')
            ->findOneBy(['username' => $request->getUser()]);

        if (!$user) {
            throw new BadCredentialsException();
        }

        $isPasswordVaild = $this->passwordEncoder->isPasswordValid($user, $request->getPassword());

        if (!$isPasswordVaild) {
            throw new BadCredentialsException();
        }

        $token = $this->encoder->encode([
            'username' => $user->getUsername(),
            'exp' => time() + 3600
        ]);

        $this->tokenStorage->storeToken($user->getUsername(), $token);

        return new JsonResponse(['token' => $token]);
    }
}
