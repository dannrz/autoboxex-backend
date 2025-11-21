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
}
