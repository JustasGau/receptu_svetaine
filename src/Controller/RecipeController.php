<?php


namespace App\Controller;


use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @Route("/api", name="recipe_api")
 */
class RecipeController extends AbstractController
{
    /**
     * @param RecipeRepository $recipeRepository
     * @return JsonResponse
     * @Route("/recipes", name="recipes", methods={"GET"})
     */
    public function getRecipes(RecipeRepository $recipeRepository){
        $data = $recipeRepository->findAll();
        return $this->response($data);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param $userRepository
     * @return JsonResponse
     * @throws \Exception
     * @Route("/recipes", name="recipes_add", methods={"POST"})
     */
    public function addRecipe(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository){

        try{
            $request = $this->transformJsonBody($request);

            if (!$request ||
                !$request->get('name') ||
                !$request->get('text'))
            {
                throw new \Exception();
            }

//            $user = $userRepository->find($request->get('user'));
//            if (!$user){
//                $data = [
//                    'status' => 404,
//                    'errors' => "User not found",
//                ];
//                return $this->response($data, 404);
//            }
            $recipe = new Recipe();
            $recipe->setName($request->get('name'));
            $recipe->setUser($this->getUser());
            $recipe->setText($request->get('text'));
            $entityManager->persist($recipe);
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Recipe added successfully",
            ];
            return $this->response($data);

        }catch (\Exception $e){
            dd($e);
            $data = [
                'status' => 422,
                'errors' => "Data not valid",
            ];
            return $this->response($data, 422);
        }
    }

    /**
     * @param RecipeRepository $recipeRepository
     * @param $id
     * @return JsonResponse
     * @Route("/recipes/{id}", name="recipes_get", methods={"GET"})
     */
    public function getRecipe(RecipeRepository $recipeRepository, $id){
        $recipe = $recipeRepository->find($id);

        if (!$recipe){
            $data = [
                'status' => 404,
                'errors' => "Recipe not found",
            ];
            return $this->response($data, 404);
        }
        return $this->response($recipe);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param RecipeRepository $recipeRepository
     * @param $id
     * @return JsonResponse
     * @Route("/recipes/{id}", name="recipes_put", methods={"PUT"})
     */
    public function updateRecipe(Request $request, EntityManagerInterface $entityManager, UserRepository $userRepository,
                                 RecipeRepository $recipeRepository, $id){

        try{
            $recipe = $recipeRepository->find($id);

            if (!$recipe){
                $data = [
                    'status' => 404,
                    'errors' => "Recipe not found",
                ];
                return $this->response($data, 404);
            }

            $request = $this->transformJsonBody($request);

            if ($request->get('name'))
                $recipe->setName($request->get('name'));
            if ($request->get('text'))
                $recipe->setText($request->get('text'));
            $entityManager->flush();

            $data = [
                'status' => 200,
                'errors' => "Recipe updated successfully",
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
     * @param RecipeRepository $recipeRepository
     * @param $id
     * @return JsonResponse
     * @Route("/recipes/{id}", name="recipes_delete", methods={"DELETE"})
     */
    public function deleteRecipe(EntityManagerInterface $entityManager, RecipeRepository $recipeRepository, $id){
        $recipe = $recipeRepository->find($id);

        if (!$recipe){
            $data = [
                'status' => 404,
                'errors' => "Recipe not found",
            ];
            return $this->response($data, 404);
        }

        $entityManager->remove($recipe);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'errors' => "Recipe deleted successfully",
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