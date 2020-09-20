<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class CommentController extends AbstractController
{

    /**
     * @return JsonResponse
     * @Route("/comments", name="comments", methods={"GET"})
     */
    public function getPosts(CommentRepository $commentRepository){
        $data = $commentRepository->findAll();
        return $this->response($data);
    }

    /**
     * @return JsonResponse
     * @throws \Exception
     * @Route("/comments", name="comments_add", methods={"POST"})
     */
    public function addComment(Request $request, EntityManagerInterface $entityManager){

        try{
            $request = $this->transformJsonBody($request);

            if (!$request ||
                !$request->get('text') ||
                !$request->get('user') ||
                !$request->get('recipe'))
            {
                throw new \Exception();
            }

            $comment = new Comment();
            $comment->setText($request->get('text'));
            $comment->setRecipe($request->get('user'));
            $comment->setRecipe($request->get('recipe'));
            $entityManager->persist($comment);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Comment added successfully",
            ];
            return $this->response($data);

        }catch (\Exception $e){
            $data = [
                'status' => 422,
                'errors' => "Data not valid",
            ];
            return $this->response($data, 422);
        }
    }

    /**
     * @param CommentRepository $commentRepository
     * @param $id
     * @return JsonResponse
     * @Route("/comments/{id}", name="comments_get", methods={"GET"})
     */
    public function getComments(CommentRepository $commentRepository, $id){
        $comment = $commentRepository->find($id);

        if (!$comment){
            $data = [
                'status' => 404,
                'errors' => "Comment not found",
            ];
            return $this->response($data, 404);
        }
        return $this->response($comment);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param CommentRepository $commentRepository
     * @param $id
     * @return JsonResponse
     * @Route("/comments/{id}", name="comments_put", methods={"PUT"})
     */
    public function updateComments(Request $request, EntityManagerInterface $entityManager, CommentRepository $commentRepository, $id){

        try{
            $comment = $commentRepository->find($id);

            if (!$comment){
                $data = [
                    'status' => 404,
                    'errors' => "Comment not found",
                ];
                return $this->response($data, 404);
            }

            $request = $this->transformJsonBody($request);

            if (!$request ||
                !$request->get('text') ||
                !$request->get('user') ||
                !$request->get('recipe'))
            {
                throw new \Exception();
            }

            $comment->setText($request->get('text'));
            $comment->setRecipe($request->get('user'));
            $comment->setRecipe($request->get('recipe'));
            $entityManager->flush();

            $data = [
                'status' => 200,
                'errors' => "Comment updated successfully",
            ];
            return $this->response($data);

        }catch (\Exception $e){
            $data = [
                'status' => 422,
                'errors' => "Data no valid",
            ];
            return $this->response($data, 422);
        }

    }

    /**
     * @param EntityManagerInterface $entityManager
     * @param CommentRepository $commentRepository
     * @param $id
     * @return JsonResponse
     * @Route("/comments/{id}", name="comments_delete", methods={"DELETE"})
     */
    public function deleteComments(EntityManagerInterface $entityManager, CommentRepository $commentRepository, $id){
        $comment = $commentRepository->find($id);

        if (!$comment){
            $data = [
                'status' => 404,
                'errors' => "Comment not found",
            ];
            return $this->response($data, 404);
        }

        $entityManager->remove($comment);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Comment deleted successfully",
        ];
        return $this->response($data);
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

    protected function transformJsonBody(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $request;
        }

        $request->request->replace($data);

        return $request;
    }
}