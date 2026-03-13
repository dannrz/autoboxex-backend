<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\{Response, Validator};
use Illuminate\Support\Str;

class BrandController extends Controller
{
    /**
     * Display a listing of the resource.
     * @api {get} /brands Get brands
     * @return brands
     */
    public function index(): JsonResponse
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

    /**
     * Store a newly created resource in storage.
     * @api {post} /brands Create brand
     * @param Request $request
     * @return created brand
     */
    public function store(Request $request)
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
     * Update the specified resource in storage.
     * @api {put} /brands/{id} Update brand
     * @param id brand
     * @return updated brand
     * @throws error message
     */
    public function update(Request $request, string $id)
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
     * Remove the specified resource from storage.
     * @api {delete} /brands/{id} Delete brand
     * @param id brand
     * @return success message
     * @throws error message
     */
    public function destroy(Request $request)
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
}
