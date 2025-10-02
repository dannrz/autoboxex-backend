<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CatalogsController extends Controller
{
    public function getServices(): JsonResponse
    {
        $services = DB::table('services_catalog')->get(['name', 'code']);

        return Response::json(
            $services,
            JsonResponse::HTTP_OK
        );
    }

    public function getStates(): JsonResponse
    {
        $states = DB::table('states_catalog')->get(['name', 'code']);

        return Response::json(
            $states,
            JsonResponse::HTTP_OK
        );
    }

    public function getClients(Request $request): JsonResponse
    {
        if ($request->has('id')) {
            $clients = Cliente::query()
                ->where('idCliente', $request->query('id'))
                ->first();
        } else {
            $clients = Cliente::all(['IdCliente', 'Nombre']);
        }

        return Response::json(
            $clients,
            JsonResponse::HTTP_OK
        );
    }
}
