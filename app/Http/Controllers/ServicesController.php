<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\InOut;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    /**
     * @param Request $request with optional 'id' query parameter of id client
     * @return JsonResponse with clients and their services or all clients if no id is provided
     */
    public function getClients(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => ['sometimes', 'integer', 'exists:clientes,IdCliente'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        if ($request->has('id')) {
            $clients = Cliente::with('servicios')
                ->with('servicios.vehiculo', function ($query) use ($request) {
                    $query->with('marca')->where('IdCliente', $request->query('id'));
                })
                ->where('idCliente', $request->query('id'))
                ->first();

            $clients->servicios->map(function ($service) {
                $service->vehiculo->marca->Marca = trim($service->vehiculo->marca->Marca);
                return $service;
            });
        }

        if (!$request->has('id')) {
            $clients = Cliente::all(['IdCliente', 'Nombre']);
        }

        return Response::json(
            $clients,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @param Request $request with required 'id' query parameter of id service
     * @return JsonResponse with insumos related to the service
     */
    public function getInsumos(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', 'integer', 'exists:EntSalDet,IdMovimiento'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $inouts = InOut::with('refaccion')
            ->where('IdMovimiento', $request->query('id'))
            ->get();

        return Response::json(
            $inouts,
            JsonResponse::HTTP_OK
        );
    }
}
