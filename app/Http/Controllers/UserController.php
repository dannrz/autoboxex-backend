<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\PasswordRestore;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * @param Request $request contains the current and new password from an authenticated user via Sanctum
     * @return JsonResponse with success or error message
     */
    public function changePassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'currentPassword' => 'required|string',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = Auth::user();

        if (!Hash::check($request->currentPassword, $user->password)) {
            return Response::json([
                'message' => 'La contraseña actual es incorrecta'
            ], JsonResponse::HTTP_FORBIDDEN);
        }

        $user->password = Hash::make($request->password);

        User::query()
            ->where('id', $user->id)
            ->update(['password' => $user->password]);

        return Response::json([
            'message' => 'La contraseña se ha cambiado correctamente, la sesión se cerrará y podrá acceder con su nueva contraseña.'
        ], JsonResponse::HTTP_OK);
    }

    /**
     * @return JsonResponse with all users including roles
     */
    public function getAll(): JsonResponse
    {
        $users = User::with('role')
            ->get();

        return Response::json(
            $users,
            JsonResponse::HTTP_OK
        );
    }

    /** 
     * @param Request $request contains the username requesting a password restore
     * @return JsonResponse with a password restore request created for the user
     * @throws Exception if the user is not found or if there is an error creating the password restore request
     */
    public function requestPasswordRestore(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $user = User::query()
            ->where('username', $request->username)
            ->first();

        try {
            PasswordRestore::create([
                'user_id' => $user->id,
                'password' => Hash::make($request->password),
                'requested_at' => now(),
            ]);

            return Response::json([
                'message' => 'Se ha solicitado la restauración de la contraseña.'
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {

            return Response::json([
                'message' => 'Error al procesar la solicitud.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
