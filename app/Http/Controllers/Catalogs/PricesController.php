<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Price;
use Illuminate\Http\{JsonResponse, Request, Response as HttpResponse};
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Number;

class PricesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        $prices = Price::all()->map(fn($price) => [
            'IdProducto' => $price->IdProducto,
            'Producto' => $price->Producto,
            'Precio' => '$' . Number::format((float) $price->Precio, 2),
        ]);

        return Response::json(
            $prices,
            HttpResponse::HTTP_OK
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
