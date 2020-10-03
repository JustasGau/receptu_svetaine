<?php


namespace App\Controller;


use App\Entity\Ingredients;
use App\Repository\IngredientsRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @package App\Controller
 * @Route("/api", name="ingredients_api")
 */
class IngredientsController extends AbstractController
{
    /**
     * @return JsonResponse
     * @Route("/ingredients", name="ingredients", methods={"GET"})
     */
    public function getPosts(IngredientsRepository $ingredientsRepository){
        $data = $ingredientsRepository->findAll();
        return $this->response($data);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param RecipeRepository $recipeRepository
     * @return JsonResponse
     * @Route("/ingredients", name="ingredients_add", methods={"POST"})
     */
    public function addIngredient(Request $request, EntityManagerInterface $entityManager, RecipeRepository $recipeRepository){

        try{
            $request = $this->transformJsonBody($request);

            if (!$request ||
                !$request->get('name') ||
                !$request->get('recipe')||
                !$request->get('amount'))
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

            $ingredient = new Ingredients();
            $ingredient->setName($request->get('name'));
            $ingredient->setRecipe($recipe);
            $ingredient->setAmount($request->get('amount'));
            if ($request->get('calories'))
                $ingredient->setCalories($request->get('calories'));
            if ($request->get('sugar'))
                $ingredient->setSugar($request->get('sugar'));
            $entityManager->persist($ingredient);
            $entityManager->flush();

            $data = [
                'status' => 201,
                'success' => "Ingredient added successfully",
            ];
            return $this->response($data, 201);

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
     * @param IngredientsRepository $ingredientsRepository
     * @param $id
     * @return JsonResponse
     * @Route("/ingredients/{id}", name="ingredients_get", methods={"GET"})
     */
    public function getIngredients(IngredientsRepository $ingredientsRepository, $id){
        $ingredient = $ingredientsRepository->find($id);

        if (!$ingredient){
            $data = [
                'status' => 404,
                'errors' => "Ingredient not found",
            ];
            return $this->response($data, 404);
        }
        return $this->response($ingredient);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param IngredientsRepository $ingredientsRepository
     * @param $id
     * @return JsonResponse
     * @Route("/ingredients/{id}", name="ingredients_put", methods={"PATCH"})
     */
    public function updateIngredients(Request $request, EntityManagerInterface $entityManager, IngredientsRepository $ingredientsRepository, $id){

        try{
            $ingredient = $ingredientsRepository->find($id);

            if (!$ingredient){
                $data = [
                    'status' => 404,
                    'errors' => "Ingredient not found",
                ];
                return $this->response($data, 404);
            }

            $request = $this->transformJsonBody($request);

            if ($request->get('name'))
                $ingredient->setName($request->get('name'));
            if ($request->get('amount'))
                $ingredient->setAmount($request->get('amount'));
            if ($request->get('calories'))
                $ingredient->setCalories($request->get('calories'));
            if ($request->get('sugar'))
                $ingredient->setSugar($request->get('sugar'));
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Ingredient updated successfully",
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
     * @param IngredientsRepository $ingredientsRepository
     * @param $id
     * @return JsonResponse
     * @Route("/ingredients/{id}", name="ingredients_delete", methods={"DELETE"})
     */
    public function deleteIngredients(EntityManagerInterface $entityManager, IngredientsRepository $ingredientsRepository, $id){
        $ingredients = $ingredientsRepository->find($id);

        if (!$ingredients){
            $data = [
                'status' => 404,
                'errors' => "Ingredient not found",
            ];
            return $this->response($data, 404);
        }

        $entityManager->remove($ingredients);
        $entityManager->flush();
        $data = [
            'status' => 200,
            'success' => "Ingredient deleted successfully",
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