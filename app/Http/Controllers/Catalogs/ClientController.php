<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\{Facades\Response, Str};

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * Note: vehicles are intentionally NOT eager loaded here, some clients
     * have thousands of vehicles which would bloat the payload. They are
     * fetched on demand and paginated via `vehicles()`.
     */
    public function index(): JsonResponse
    {
        $clients = Cliente::query()->get();

        return Response::json(
            $clients,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * Display a paginated listing of the vehicles that belong to a client.
     */
    public function vehicles(string $client, Request $request): JsonResponse
    {
        $perPage = (int) $request->query('perPage', 10);

        $vehicles = Cliente::findOrFail($client)
            ->vehiculos()
            ->with('marca')
            ->paginate($perPage)
            ->through(function ($vehiculo) {
                if ($vehiculo->marca) {
                    $vehiculo->marca->Marca = Str::trim($vehiculo->marca->Marca);
                }

                return $vehiculo;
            });

        return Response::json(
            $vehicles,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
