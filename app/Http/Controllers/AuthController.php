<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Env;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    /**
     * @param Request $request with username and password
     * @return JsonResponse with user information and access token or error message
     */
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

        $user = User::with('role')
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

        if ($user->status == 0) {
            return Response::json([
                'message' => 'Usuario inactivo, contacte al administrador',
            ], JsonResponse::HTTP_UNAUTHORIZED);
        }

        $token = $user->createToken('auth_token');
        $expiration = now()->addMinutes((int) Env::get('SANCTUM_EXPIRATION', 120))
            ->toIso8601String();

        $token->accessToken->expires_at = $expiration;
        $token->accessToken->save();

        return Response::json([
            'user' => $user,
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_at' => $expiration,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @use Auth
     * @return JsonResponse with success message after destroying the tokens of the authenticated user
     */
    public function logout(): JsonResponse
    {
        $user = Auth::user();

        foreach ($user->tokens as $token) {
            $token->delete();
        }

        return Response::json([
            'message' => 'Sesion finalizada',
        ], JsonResponse::HTTP_OK);
    }
}
