<?php


namespace App\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;

class ErrorController
{

    public function show()
    {
        $data = [
            'error' => "Page not found",
        ];
        return $this->response($data, 404);
    }

    public function response($data, $status = 200, $headers = [])
    {
        return new JsonResponse($data, $status, $headers);
    }
}