<?php

namespace App\Http\Controllers;

use App\Models\{Brand, Cliente, InOut, Modelo, Service, User, Vehicles};
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Auth, Response, Validator};
use Illuminate\Support\Str;

class ServicesController extends Controller
{
    /**
     * @api {post} /services Create vehicle + service record
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'IdCliente'   => ['required', 'integer', 'exists:Cliente,IdCliente'],
            'Placas'      => ['required', 'string', 'max:40'],
            'Marca'       => ['nullable', 'string', 'max:40'],
            'Modelo'      => ['nullable', 'string', 'max:40'],
            'Año'         => ['nullable', 'integer'],
            'Color'       => ['nullable', 'string', 'max:40'],
            'Serie'       => ['nullable', 'string', 'max:40'],
            'Kms'         => ['nullable', 'numeric'],
            'FolioOE'     => ['nullable', 'integer', 'max:2147483647'],
            'TipMov'      => ['nullable', 'integer'],
            'Estado'      => ['nullable', 'string', 'max:40'],
            'FEntrada'    => ['nullable', 'date'],
            'FSalida'     => ['nullable', 'date'],
            'Autoriza'    => ['nullable', 'string', 'max:40'],
            'Ingreso'     => ['nullable', 'string', 'max:500'],
            'Observación' => ['nullable', 'string', 'max:500'],
            'DiasPS'      => ['nullable', 'integer'],
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();

        // 1. Buscar o crear el vehículo por placa + cliente
        $vehiculo = Vehicles::where('IdCliente', $data['IdCliente'])
            ->where('Placas', $data['Placas'])
            ->first();

        if (!$vehiculo) {
            $idMarca = null;
            if (!empty($data['Marca'])) {
                $marca = \App\Models\Brand::whereRaw('LTRIM(RTRIM(Marca)) = ?', [Str::trim($data['Marca'])])->first();
                $idMarca = $marca?->IdMarca;
            }

            $nextIdVehiculo = (Vehicles::max('IdVehiculo') ?? 0) + 1;

            $vehiculo = Vehicles::create([
                'IdCliente'  => $data['IdCliente'],
                'IdVehiculo' => $nextIdVehiculo,
                'Placas'     => Str::trim($data['Placas']),
                'IdMarca'    => $idMarca,
                'Modelo'     => isset($data['Modelo']) ? Str::trim($data['Modelo']) : null,
                'Año'        => $data['Año'] ?? null,
                'Color'      => isset($data['Color']) ? Str::trim($data['Color']) : null,
                'Serie'      => isset($data['Serie']) ? Str::trim($data['Serie']) : null,
            ]);
        }

        // 2. Crear el registro de servicio
        $servicio = Service::create([
            'IdCliente'   => $data['IdCliente'],
            'IdVehiculo'  => $vehiculo->IdVehiculo,
            'FolioOE'     => $data['FolioOE'] ?? null,
            'TipMov'      => $data['TipMov'] ?? null,
            'Estado'      => isset($data['Estado']) ? Str::trim($data['Estado']) : null,
            'FEntrada'    => $data['FEntrada'] ?? null,
            'FSalida'     => $data['FSalida'] ?? null,
            'Kms'         => $data['Kms'] ?? null,
            'Ingreso'     => isset($data['Ingreso']) ? Str::trim($data['Ingreso']) : null,
            'DiasPS'      => $data['DiasPS'] ?? null,
            'Observación' => isset($data['Observación']) ? Str::trim($data['Observación']) : null,
            'Autoriza'    => isset($data['Autoriza']) ? Str::trim($data['Autoriza']) : null,
            'IdUsuario'   => Auth::user()->username ?? Auth::id(),
        ]);

        return Response::json([
            'vehiculo' => $vehiculo,
            'servicio' => $servicio,
        ], JsonResponse::HTTP_CREATED);
    }

    public function search(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'folio'     => ['sometimes', 'integer'],
            'placas'    => ['sometimes', 'string'],
            'idCliente' => ['sometimes', 'integer'],
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Service::with(['cliente', 'vehiculo.marca'])
            ->orderBy('FolioOE', 'desc')
            ->orderBy('IdMovimiento', 'desc');

        if ($request->filled('folio')) {
            $query->where('FolioOE', $request->integer('folio'));
        }
        if ($request->filled('placas')) {
            $query->whereHas('vehiculo', fn($q) => $q->where('Placas', 'like', '%' . $request->string('placas') . '%'));
        }
        if ($request->filled('idCliente')) {
            $query->where('IdCliente', $request->integer('idCliente'));
        }

        return Response::json($query->limit(200)->get(), JsonResponse::HTTP_OK);
    }

    public function getMarcas(): JsonResponse
    {
        $marcas = Brand::select('IdMarca', 'Marca')
            ->orderBy('Marca')
            ->get()
            ->map(fn($m) => ['IdMarca' => $m->IdMarca, 'Marca' => Str::trim($m->Marca)]);

        return Response::json($marcas, JsonResponse::HTTP_OK);
    }

    public function getModelos(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'idMarca' => ['sometimes', 'integer'],
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $query = Modelo::select('IdMarca', 'Modelo')->orderBy('Modelo');

        if ($request->has('idMarca')) {
            $query->where('IdMarca', $request->query('idMarca'));
        }

        return Response::json($query->get(), JsonResponse::HTTP_OK);
    }

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
