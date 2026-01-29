<?php

namespace App\Http\Controllers;

use App\Models\{Brand, Modelo, Refaccion};
use Carbon\Carbon;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Response, Validator};
use Illuminate\Support\Str;

class CatalogsController extends Controller
{
    public function getBrands(): JsonResponse
    {
        $brands = Brand::all();

        $brands->map(function ($brand) {
            $brand->Marca = Str::trim($brand->Marca);

            return $brand;
        });

        return Response::json(
            $brands,
            JsonResponse::HTTP_OK
        );
    }

    public function saveBrand(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'Marca' => ['required', 'string', 'max:255', 'unique:Marca,Marca'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $lastId = Brand::where('IdMarca', '!=', 9999)->max('IdMarca');
        $newId = $lastId + 1;

        $brand = Brand::create([
            'IdMarca' => $newId,
            'Marca' => Str::trim($request->Marca),
        ]);

        return Response::json(
            $brand,
            JsonResponse::HTTP_CREATED
        );
    }

    /**
     * @api {delete} /brands/{id} Delete brand
     * @param id brand
     * @return success message
     * @throws error message
     */
    public function deleteBrand(Request $request): JsonResponse
    {
        $validator = Validator::make(['id' => $request->id], [
            'id' => ['required', 'integer', 'exists:Marca,IdMarca'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }
        $brand = Brand::find($request->id);
        $brand->delete();


        return Response::json(
            ['message' => 'Marca eliminada'],
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @api {put} /brands/{id} Update brand
     * @param id brand
     * @return updated brand
     * @throws error message
     */
    public function updateBrand(Request $request): JsonResponse
    {
        $validator = Validator::make(array_merge($request->all(), ['id' => $request->id]), [
            'id' => ['required', 'integer', 'exists:Marca,IdMarca'],
            'brand' => ['required', 'string', 'max:255', 'unique:Marca,Marca,' . $request->id . ',IdMarca'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_INTERNAL_SERVER_ERROR
            );
        }


        $brand = Brand::find($request->id);
        $brand->Marca = Str::trim($request->brand);
        $brand->save();

        return Response::json(
            $brand,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @api {get} /models Get models
     * @return models
     */
    public function getModelos(): JsonResponse
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
     * @api {post} /models Create model
     * @param Request $request
     * @return created model
     */
    public function createModel(Request $request): JsonResponse
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

    public function deleteModel(Request $request): JsonResponse
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

    public function updateModel(Request $request): JsonResponse
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

    public function getRefacciones(): JsonResponse
    {
        $refacciones = Refaccion::query()
            ->get();

        $refacciones->map(function ($refaccion) {
            if (!is_null($refaccion->Fecha)) {
                $refaccion->Fecha = Carbon::parse($refaccion->Fecha)->isoFormat('DD [de] MMMM [de] YYYY');
            }

            return $refaccion;
        });

        return Response::json(
            $refacciones,
            JsonResponse::HTTP_OK
        );
    }
}
