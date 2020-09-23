<?php


namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @Route("/api", name="user_api")
 */
class UserController extends AbstractController
{
    /**
     * @param UserRepository $userRepository
     * @return JsonResponse
     * @Route("/users", name="users", methods={"GET"})
     */
    public function getUsers(UserRepository $userRepository){
        $data = $userRepository->findAll();
        return $this->response($data);
    }

    /**
     * @param Request $request
     * @param UserRepository $userRepository
     * @param $id
     * @return JsonResponse
     * @Route("/users/ban/{id}", name="ban_users", methods={"POST"})
     */
    public function banUser(UserRepository $userRepository, EntityManagerInterface $entityManager, $id){
        $user = $userRepository->find($id);
        if (!$user){
            $data = [
                'status' => 404,
                'errors' => "User not found",
            ];
            return $this->response($data, 404);
        }
        $banned = $user->getBanned();
        $user->setBanned($banned ? false : true );
        $entityManager->persist($user);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'success' => "User banned status changed",
        ];

        return $this->response($data, 200);
    }

    /**
     * Returns a JSON response
     *
     * @param $data
     * @param $status
     * @param array $headers
     * @return JsonResponse
     */
    public function response($data, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }
}