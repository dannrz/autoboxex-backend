<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $validatedData = $data->validated();

        $user = User::query()
            ->where('username', '=', $validatedData['username'])
            ->first();

        if (!$user) {
            return Response::json([
                'mismatch' => 'username',
                'message' => 'Usuario no encontrado',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        if (!Hash::check($validatedData['password'], $user->password)) {
            return Response::json([
                'mismatch' => 'password',
                'message' => 'Contraseña incorrecta',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }
        $token = $user->createToken('auth_token');
        $token->accessToken->expires_at = now()->addMinutes(config('sanctum.expiration'));
        $token->accessToken->save();

        Auth::login($user);

        return Response::json([
            'user' => $user,
            'token' => $token->plainTextToken,
            'type' => 'bearer',
            'expires_at' => $token->accessToken->expires_at,
        ], JsonResponse::HTTP_OK);
    }

    public function logout(Request $request): JsonResponse
    {
        $user = Auth::user();

        return Response::json([
            'message' => $user,
        ], JsonResponse::HTTP_OK);
    }
}
