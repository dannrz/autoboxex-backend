<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\Facades\Response;
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
}
