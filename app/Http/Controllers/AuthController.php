<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $data = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($data->fails()) {
            return Response::json([
                'message' => 'Validation failed',
                'errors' => $data->errors(),
            ], 422);
        }

        $validatedData = $data->validated();
        return Response::json([
            'user' => $validatedData['username'],
            'password' => $validatedData['password'],
            'message' => 'Login successful',
        ], 200);
    }
}
