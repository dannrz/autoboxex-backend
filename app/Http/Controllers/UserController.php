<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
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
}
