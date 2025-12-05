<?php

namespace App\Http\Controllers;

use App\Models\Brand;
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

    public function getModelos(): JsonResponse
    {
        $modelos = Brand::with('modelos')
            ->whereNot('IdMarca', 9999)
            ->whereNot('IdMarca', 42)
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
}
