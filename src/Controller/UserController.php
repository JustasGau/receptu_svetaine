<?php


namespace App\Controller;


use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

class UserController extends AbstractController
{

    /**
     * @return JsonResponse
     * @Route("/users", name="users", methods={"GET"})
     */
    public function getPosts(UserRepository $userRepository){
        $data = $userRepository->findAll();
        return $this->response($data);
    }

}