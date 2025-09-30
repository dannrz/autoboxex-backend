<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\PasswordRestore;
use App\Models\User;
use Carbon\Carbon;
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
            ->with('passwordRestores')
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
            'newPassword' => ['required', 'string', "regex:/^[A-Za-z0-9!@#$%^&*()_+\-=\[\]{};':\"\\\\|,.<>\/?]+$/", 'min:6'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        $user = User::query()
            ->where('username', $validated['username'])
            ->first();

        if (!$user) {
            return Response::json([
                'message' => 'Usuario no encontrado. Verifique el nombre de usuario e intente nuevamente.',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $existingRequest = PasswordRestore::query()
            ->where('user_id', $user->id)
            ->first();

        if ($existingRequest) {
            return Response::json([
                'message' => 'Ya existe una solicitud de restauración de contraseña para este usuario. Por favor, contacte al administrador para su aprobación.',
            ], JsonResponse::HTTP_CONFLICT);
        }

        try {
            PasswordRestore::query()->insert([
                'user_id' => $user->id,
                'password' => Hash::make($validated['newPassword']),
                'requested_at' => Carbon::now(),
            ]);
            User::query()
                ->where('id', $user->id)
                ->update(['status' => 0]);

            return Response::json([
                'message' => 'Se ha solicitado la restauración de la contraseña.'
            ], JsonResponse::HTTP_CREATED);
        } catch (Exception $e) {

            return Response::json([
                'message' => 'Error al procesar la solicitud.'
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @return JsonResponse with all password change requests including user details
     */
    public function getPasswordChangeRequests(Request $request): JsonResponse
    {
        $requests = PasswordRestore::with('user')->get();

        if ($request->query('filter') == 'notifications') {
            $requests->map(function ($req) {
                $req->requested_at_formatted = Carbon::parse($req->requested_at)->diffForHumans();
            });
        } else {
            $requests->map(function ($request) {
                $request->requested_at_formatted = Carbon::parse($request->requested_at)->isoFormat('dddd, DD [de] MMMM [de] YYYY [-] HH:mm:ss');
            });
        }

        return Response::json(
            $requests,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @param Request $request contains the user_id and accept boolean to approve or reject a password change request
     * @return JsonResponse with success or error message
     */
    public function respondPasswordRequest(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => ['required', 'integer', 'exists:users,id'],
            'accept' => ['required', 'boolean'],
        ]);


        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        $passwordRequest = PasswordRestore::with('user')
            ->where('user_id', $validated['user_id'])
            ->first();


        if (!$passwordRequest) {
            return Response::json([
                'message' => 'No se encontró una solicitud de cambio de contraseña para este usuario.',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        if ($validated['accept']) {
            User::query()
                ->where('id', $validated['user_id'])
                ->update([
                    'password' => $passwordRequest->password,
                    'status' => 1,
                ]);
        }

        PasswordRestore::query()
            ->where('user_id', $validated['user_id'])
            ->delete();

        return Response::json([
            'message' => $validated['accept'] ? "La contraseña ha sido actualizada y el usuario de {$passwordRequest->user->name} ha sido reactivado." : 'La solicitud de cambio de contraseña ha sido rechazada.',
            'status' => $validated['accept'] ? 'accepted' : 'rejected',
        ], JsonResponse::HTTP_OK);
    }

    /** 
     * @param Request $request contains the username of the user to change status
     * @return json with change status of a user (active/inactive) by username
     */
    public function changeStatusUser(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => ['required', 'string', 'exists:users,username'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        $user = User::query()
            ->where('username', $validated['username'])
            ->first();

        if (!$user) {
            return Response::json([
                'message' => 'Usuario no encontrado.',
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return Response::json([
            'message' => 'El estado del usuario ha sido actualizado correctamente.',
            'status_user' => $user->status,
        ], JsonResponse::HTTP_OK);
    }
}
