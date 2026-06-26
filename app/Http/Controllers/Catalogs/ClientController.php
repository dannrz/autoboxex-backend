<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Cliente;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\{Facades\Response, Str};
use Illuminate\Validation\Rule;

class ClientController extends Controller
{
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

        return Response::json($clients, JsonResponse::HTTP_OK);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'Nombre'    => ['required', 'string', 'max:200', Rule::unique('Cliente', 'Nombre')],
            'RFC'       => ['nullable', 'string', 'max:26'],
            'CP'        => ['nullable', 'string', 'max:10'],
            'eMail'     => ['nullable', 'email', 'max:200'],
            'Direccion' => ['nullable', 'string', 'max:200'],
            'Colonia'   => ['nullable', 'string', 'max:200'],
            'Poblacion' => ['nullable', 'string', 'max:100'],
            'Estado'    => ['nullable', 'string', 'max:100'],
            'Contacto'  => ['nullable', 'string', 'max:100'],
            'Sucursal'  => ['nullable', 'string', 'max:40'],
            'Credito'   => ['nullable', 'integer', 'min:0', 'max:32767'],
            'Telefono'  => ['nullable', 'string', 'max:100'],
            'Telefono2' => ['nullable', 'string', 'max:100'],
            'Telefono3' => ['nullable', 'string', 'max:100'],
            'Descuento' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ManoObra'  => ['nullable', 'numeric', 'min:0'],
        ], [
            'Nombre.unique' => "Ya existe un cliente con el nombre '{$request->Nombre}'.",
            'eMail.email'   => 'El correo electrónico no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = collect($validator->validated())->map(fn($v) => is_string($v) ? Str::trim($v) : $v)->toArray();
        $data['IdCliente'] = (Cliente::max('IdCliente') ?? 0) + 1;

        $client = Cliente::create($data);

        return Response::json($client->fresh(), JsonResponse::HTTP_CREATED);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        $client = Cliente::find($id);

        if (!$client) {
            return Response::json(['message' => 'Cliente no encontrado'], JsonResponse::HTTP_NOT_FOUND);
        }

        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'Nombre'    => ['required', 'string', 'max:200', Rule::unique('Cliente', 'Nombre')->ignore($id, 'IdCliente')],
            'RFC'       => ['nullable', 'string', 'max:26'],
            'CP'        => ['nullable', 'string', 'max:10'],
            'eMail'     => ['nullable', 'email', 'max:200'],
            'Direccion' => ['nullable', 'string', 'max:200'],
            'Colonia'   => ['nullable', 'string', 'max:200'],
            'Poblacion' => ['nullable', 'string', 'max:100'],
            'Estado'    => ['nullable', 'string', 'max:100'],
            'Contacto'  => ['nullable', 'string', 'max:100'],
            'Sucursal'  => ['nullable', 'string', 'max:40'],
            'Credito'   => ['nullable', 'integer', 'min:0', 'max:32767'],
            'Telefono'  => ['nullable', 'string', 'max:100'],
            'Telefono2' => ['nullable', 'string', 'max:100'],
            'Telefono3' => ['nullable', 'string', 'max:100'],
            'Descuento' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'ManoObra'  => ['nullable', 'numeric', 'min:0'],
        ], [
            'Nombre.unique' => "Ya existe un cliente con el nombre '{$request->Nombre}'.",
            'eMail.email'   => 'El correo electrónico no tiene un formato válido.',
        ]);

        if ($validator->fails()) {
            return Response::json($validator->errors(), JsonResponse::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = collect($validator->validated())->map(fn($v) => is_string($v) ? Str::trim($v) : $v);

        $client->update($data->toArray());

        return Response::json($client->fresh(), JsonResponse::HTTP_OK);
    }
}
