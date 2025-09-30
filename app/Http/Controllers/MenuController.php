<?php

namespace App\Http\Controllers;

use App\Models\PasswordRestore;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class MenuController extends Controller
{
    public function getMenu(): JsonResponse
    {
        $count = PasswordRestore::count();

        return Response::json([
            'count' => $count,
        ], JsonResponse::HTTP_OK);
    }

    public function getMenuPasswordsRestores(): JsonResponse
    {
        $data = PasswordRestore::all();

        return Response::json([
            'data' => $data,
        ], JsonResponse::HTTP_OK);
    }
}
