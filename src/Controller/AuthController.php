<?php

namespace App\Controller;


use App\Entity\User;
use App\Repository\UserRepository;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\HttpFoundation\Response;


class AuthController extends ApiController
{

    public function register(Request $request, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $request = $this->transformJsonBody($request);
        $username = $request->get('username');
        $password = $request->get('password');
        $email = $request->get('email');

        if (empty($username) || empty($password) || empty($email)){
            return $this->respondValidationError("Invalid Username or Password or Email");
        }

        $check_user = $userRepository->findBy(['username' => $username]);

        if (count($check_user) > 0){
            return $this->respondForbidden(sprintf('User %s already exists', $username));
        }

        $user = new User($username);
        $user->setPassword($encoder->encodePassword($user, $password));
        $user->setEmail($email);
        $user->setUsername($username);
        $em->persist($user);
        $em->flush();
        return $this->respondCreated(sprintf('User %s successfully created', $user->getUsername()));
    }


    /**
     * @param Request $request
     * @param UserInterface|null $user
     * @param JWTTokenManagerInterface $JWTManager
     * @return JsonResponse
     */
    public function getTokenUser(Request $request, UserInterface $user = null, JWTTokenManagerInterface $JWTManager)
    {
        if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
            return new JsonResponse(['token' => $JWTManager->create($user)]);
        }else{
            return new Response('Invalid api request. Please use json format', Response::HTTP_NOT_FOUND);
        }
    }

}