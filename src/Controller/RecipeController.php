<?php


namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\CommentRepository;
use App\Repository\IngredientsRepository;
use App\Repository\RecipeRepository;
use App\Repository\UserRepository;
use Aws\Credentials\Credentials;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Aws\S3\S3Client;

require __DIR__.'/../../vendor/autoload.php';

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
        if ($request->headers->get('Content-Type') === 'application/json') {
            try{
                $request = $this->transformJsonBody($request);

                if (!$request ||
                    !$request->get('name') ||
                    !$request->get('text'))
                {
                    $data = [
                        'status' => 400,
                        'errors' => "Incorrect data",
                    ];
                    return $this->response($data, 400);
                }

                if($this->getUser()->getBanned() == true) {
                    $data = [
                        'status' => 403,
                        'errors' => "User is forbidden from posting",
                    ];
                    return $this->response($data, 403);
                }
                $recipe = new Recipe();
                $recipe->setName($request->get('name'));
                $recipe->setUser($this->getUser());
                $recipe->setText($request->get('text'));
                $recipe->setImage($request->get('image'));
                $entityManager->persist($recipe);
                $entityManager->flush();

                $id = $recipe->getId();

                $data = [
                    'status' => 201,
                    'success' => "Recipe added successfully",
                    'id' => $id
                ];
                return $this->response($data,201);

            }catch (\Exception $e){
                $data = [
                    'status' => 422,
                    'errors' => "Data not valid",
                ];
                return $this->response($data, 422);
            }
        } else {
//            $key = 'AKIAIVRLD2GLSR7NI73A';
//            $secret = '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';
//            $cred = new Credentials($key, $secret, NULL);
            $s3 = new S3Client([
                'version' => 'latest',
                'region'  => 'eu-north-1'
//                'credentials' => $cred
            ]);
            $file = $request->files->get('image');
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $newName = $originalFilename.'.'.uniqid().'.'.$file->guessExtension();
            try {
                $upload = $s3->putObject([
                    'Bucket' => 'receptai',
                    'Key'    => $newName,
                    'Body'   => fopen($file, 'r'),
                    'ACL'    => 'public-read'

                ]);
                $data = [
                    'status' => 201,
                    'success' => "Image uploaded successfully",
                    'link' => $upload->get('ObjectURL')
                ];
            return $this->response($data,201);

            } catch (Aws\S3\Exception\S3Exception $e) {
                $data = [
                    'status' => 422,
                    'errors' => "Error uploading the file",
                ];
                return $this->response($data, 422);
            }
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
     * @param RecipeRepository $recipeRepository
     * @param $id
     * @return JsonResponse
     * @Route("/recipes/{id}/comments", name="recipes_get_comemnts", methods={"GET"})
     */
    public function getRecipeComments(RecipeRepository $recipeRepository, CommentRepository $commentRepository, $id){
        $recipe = $recipeRepository->find($id);
        if (!$recipe){
            $data = [
                'status' => 404,
                'errors' => "Recipe not found",
            ];
            return $this->response($data, 404);
        }

        $comments = $commentRepository->findRecipeComments($id);

        return $this->response($comments);
    }
    /**
     * @param RecipeRepository $recipeRepository
     * @param $id
     * @return JsonResponse
     * @Route("/recipes/{id}/ingredients", name="recipes_get_ingredients", methods={"GET"})
     */
    public function getRecipeIngredients(RecipeRepository $recipeRepository, IngredientsRepository $ingredientsRepository, $id){
        $recipe = $recipeRepository->find($id);

        if (!$recipe){
            $data = [
                'status' => 404,
                'errors' => "Recipe not found",
            ];
            return $this->response($data, 404);
        }
        $ing = $ingredientsRepository->findRecipeIngredients($id);
        return $this->response($ing);
    }

    /**
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @param RecipeRepository $recipeRepository
     * @param $id
     * @return JsonResponse
     * @Route("/recipes/{id}", name="recipes_put", methods={"PATCH"})
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
            if ($request->get('image'))
                $recipe->setImage($request->get('image'));
            $entityManager->flush();

            $data = [
                'status' => 200,
                'success' => "Recipe updated successfully",
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
            'success' => "Recipe deleted successfully",
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