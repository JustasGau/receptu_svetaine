<?php


namespace App\Controller;


use App\Entity\Comment;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @Route("/api", name="comments_api")
 */
class CommentController extends AbstractController
{
    /**
     * @param CommentRepository $commentRepository
     * @return JsonResponse
     * @Route("/comments", name="comments", methods={"GET"})
     */
    public function getPosts(CommentRepository $commentRepository){
        $data = $commentRepository->findAll();
        return $this->response($data);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param RecipeRepository $recipeRepository
     * @return JsonResponse
     * @Route("/comments", name="comments_add", methods={"POST"})
     */
    public function addComment(Request $request, EntityManagerInterface $entityManager, RecipeRepository $recipeRepository){

        try{
            $request = $this->transformJsonBody($request);

            if (!$request ||
                !$request->get('text') ||
                !$request->get('recipe'))
            {
                $data = [
                    'status' => 400,
                    'errors' => "Incorrect data",
                ];
                return $this->response($data, 400);
            }

            $recipe = $recipeRepository->find($request->get('recipe'));
            if (!$recipe){
                $data = [
                    'status' => 404,
                    'errors' => "Recipe not found",
                ];
                return $this->response($data, 404);
            }

            $comment = new Comment();
            $comment->setText($request->get('text'));
            $comment->setUser($this->getUser());
            $comment->setRecipe($recipe);
            $recipe->addComment($comment);
            $entityManager->persist($comment);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'success' => "Comment added successfully",
            ];
            return $this->response($data, 201);

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
     * @Route("/comments/{id}", name="comments_put", methods={"PATCH"})
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

            if (!$request || !$request->get('text'))
            {
                throw new \Exception();
            }
            if ($comment->setText($request->get('text')))
                $comment->setText($request->get('text'));
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Comment updated successfully",
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
            'success' => "Comment deleted successfully",
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