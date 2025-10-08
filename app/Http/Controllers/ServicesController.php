<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class ServicesController extends Controller
{
    public function getClients(Request $request): JsonResponse
    {
        if ($request->has('id')) {
            $clients = Cliente::with('servicios')
                ->with('servicios.vehiculo', function ($query) use ($request) {
                    $query->with('marca')->where('IdCliente', $request->query('id'));
                })
                ->where('idCliente', $request->query('id'))
                ->first();
        }

        if (!$request->has('id')) {
            $clients = Cliente::all(['IdCliente', 'Nombre']);
        }

        return Response::json(
            $clients,
            JsonResponse::HTTP_OK
        );
    }
}
