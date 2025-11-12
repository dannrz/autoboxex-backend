<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\InOut;
use App\Models\Service;
use App\Models\Vehicles;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;

class ServicesController extends Controller
{
    /**
     * @api {get} /services/clients Get Clients and their Services
     * @param Request $request with optional 'id' query parameter of id client
     * @return JsonResponse with clients and their services or all clients if no id is provided
     */
    public function getClients(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'id' => ['sometimes', 'integer', 'exists:Cliente,IdCliente'],
            'inOrder' => ['sometimes', 'integer', 'exists:Servicio,FolioOE'],
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

            $clients->direccionFull = Str::trim("{$clients->Direccion} {$clients->Colonia} {$clients->Poblacion} {$clients->Estado} {$clients->CP}");

            $clients->servicios->map(function ($service) {
                $service->vehiculo->marca->Marca = Str::trim($service->vehiculo->marca->Marca);
                return $service;
            });
        }

        if (!$request->has('id')) {
            $clients = Cliente::all(['IdCliente', 'Nombre']);
        }

        if (!$request->has('id') && $request->has('inOrder')) {
            $clients = Cliente::query()
                ->with('servicios', function ($query) use ($request) {
                    $query->where('FolioOE', $request->query('inOrder'))->with('vehiculo', function ($queryVeh) {
                        $queryVeh->with('marca');
                    });
                })
                ->whereHas('servicios', function ($query) use ($request) {
                    $query->where('FolioOE', $request->query('inOrder'));
                })
                ->firstOrFail();

            $clients->direccionFull = Str::trim("{$clients->Direccion} {$clients->Colonia} {$clients->Poblacion} {$clients->Estado} {$clients->CP}");

            $clients->servicios->map(function ($service) {
                $service->vehiculo->marca->Marca = Str::trim($service->vehiculo->marca->Marca);
                return $service;
            });
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
            'id' => ['required', 'integer'],
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

    public function getInOrders(): JsonResponse
    {
        $orders = Service::select('FolioOE')
            ->distinct()
            ->orderBy('FolioOE', 'desc')
            ->get();

        return Response::json(
            $orders,
            JsonResponse::HTTP_OK
        );
    }

    public function getPlates(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idCliente' => ['required', 'string', 'exists:ClienteVeh,IdCliente'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $plates = Vehicles::select('Placas')
            ->where('IdCliente', $request->query('idCliente'))
            ->get();

        return Response::json(
            $plates,
            JsonResponse::HTTP_OK
        );
    }
}
