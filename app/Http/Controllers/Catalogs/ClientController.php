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
     */
    public function index(): JsonResponse
    {
        $clients = Cliente::query()
            ->with('vehiculos', function ($vehiculo) {
                $vehiculo->with('marca');
            })
            ->get()
            ->map(function ($client) {
                $client->vehiculos->each(function ($vehiculo) {
                    if ($vehiculo->marca) {
                        $vehiculo->marca->Marca = Str::trim($vehiculo->marca->Marca);
                    }
                });

                return $client;
            });

        return Response::json(
            $clients,
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
