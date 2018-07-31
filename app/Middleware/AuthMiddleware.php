<?php

namespace App\Middleware;

use Symfony\Component\HttpFoundation\Request;

class AuthMiddleware extends AbstractMiddleware
{

    protected function handle(Request $request)
    {
//        return new JsonResponse(["test" => "asdafasdf"], JsonResponse::HTTP_OK);
        return 1;
    }
}