<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\{Brand, Modelo};
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Response, Validator};
use Illuminate\Support\Str;

class ModelsController extends Controller
{
    /**
     * Display a listing of the resource.
     * @api {get} /models Get models
     * @return models
     */
    public function index(): JsonResponse
    {
        $modelos = Brand::with('modelos')
            ->whereNotIn('IdMarca', [9999, 42, 94])
            ->get()
            ->flatMap(function ($brand) {
                return $brand->modelos->map(function ($modelo) use ($brand) {
                    return [
                        'Marca' => Str::trim($brand->Marca),
                        'Modelo' => Str::trim($modelo->Modelo),
                    ];
                });
            });

        return Response::json(
            $modelos,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * Store a newly created resource in storage.
     * @api {post} /models Create model
     * @param Request $request
     * @return created model
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'marca' => ['required', 'integer',  'exists:Marca,IdMarca'],
            'Modelo' => ['required', 'string', 'max:255', 'unique:Modelo,Modelo'],
        ], [
            'Modelo.unique' => "El modelo {$request->Modelo} ya existe en la base de datos.",
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        $modelo = Modelo::create([
            'IdMarca' => $validated['marca'],
            'Modelo' => Str::trim($validated['Modelo']),
        ]);

        return Response::json(
            $modelo,
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * Update the specified resource in storage.
     * @api {put} /models Update model
     * @param Request $request
     * @return updated model
     */
    public function update(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'oldMarca' => ['required', 'string', 'exists:Marca,Marca'],
            'oldModelo' => ['required', 'string', 'exists:Modelo,Modelo'],
            'marca' => ['required', 'string', 'exists:Marca,Marca'],
            'Modelo' => ['required', 'string', 'max:255', 'unique:Modelo,Modelo'],
        ], [
            'Modelo.unique' => "El modelo {$request->Modelo} ya existe en la base de datos.",
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        $modelo = Modelo::query()
            ->whereHas('brand', function ($query) use ($validated) {
                $query->where('Marca', $validated['oldMarca']);
            })
            ->where('Modelo', $validated['oldModelo'])
            ->update([
                'IdMarca' => Brand::where('Marca', $validated['marca'])->first()->IdMarca,
                'Modelo' => Str::trim($validated['Modelo']),
            ]);

        return Response::json([
            'modelo' => $modelo,
            'message' => 'Modelo actualizado',
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     * @api {delete} /models Delete model
     * @param Request $request
     * @return deleted model
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'Marca' => ['required', 'string', 'exists:Marca,Marca'],
            'Modelo' => ['required', 'string', 'exists:Modelo,Modelo'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        $modelo = Modelo::query()
            ->whereHas('brand', function ($query) use ($validated) {
                $query->where('Marca', $validated['Marca']);
            })
            ->where('Modelo', $validated['Modelo'])
            ->delete();

        return Response::json(
            [
                'message' => 'Modelo eliminado',
                'modelo' => $modelo,
            ],
            JsonResponse::HTTP_OK
        );
    }
}
