<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\InOut;
use App\Models\Service;
use App\Models\User;
use App\Models\Vehicles;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ServicesController extends Controller
{
    /**
     * @api {get} /services/clients Get Clients and their Services
     * @param Request $request with optional 'id' query parameter of id client
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
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
            $clients = $this->getClientByIdCliente($request);
        }

        if (!$request->has('id')) {
            $clients = Cliente::all(['IdCliente', 'Nombre']);
        }

        if (!$request->has('id') && $request->has('inOrder')) {
            $clients = $this->getClientByInOrder($request);
        }

        return Response::json(
            $clients,
            JsonResponse::HTTP_OK
        );
    }

    private function getClientByInOrder(Request $request): Cliente
    {
        try {
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
                $name = Auth::user()->name;

                if (!Str::lower($service->IdUsuario) == Str::lower(Auth::user()->username)) {
                    $user = User::query()
                        ->where('username', $service->IdUsuario)
                        ->first();

                    $name = $user ? $user->name : $service->IdUsuario;
                }
                $service->Autoriza = $name;

                return $service;
            });
        } catch (ModelNotFoundException $e) {
            throw $e;
        }

        return $clients;
    }

    private function getClientByIdCliente(Request $request): Cliente
    {
        try {
            $clients = Cliente::with('servicios')
                ->with('servicios.vehiculo', function ($query) use ($request) {
                    $query->with('marca')->where('IdCliente', $request->query('id'));
                })
                ->where('idCliente', $request->query('id'))
                ->firstOrFail();

            $clients->direccionFull = Str::trim("{$clients->Direccion} {$clients->Colonia} {$clients->Poblacion} {$clients->Estado} {$clients->CP}");

            $clients->servicios->map(function ($service) {
                $service->vehiculo->marca->Marca = Str::trim($service->vehiculo->marca->Marca);

                return $service;
            });
        } catch (ModelNotFoundException $e) {
            throw $e;
        }

        return $clients;
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

    /**
     * @api {get} /services/in-orders Get Distinct In Orders
     * @return JsonResponse with distinct in orders from services
     */
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

    /** 
     * @api {get} /services/plates Get Vehicle Plates by Client ID
     * @param Request $request with required 'idCliente' query parameter of id client
     * @return JsonResponse with vehicle plates related to the client
     */
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
