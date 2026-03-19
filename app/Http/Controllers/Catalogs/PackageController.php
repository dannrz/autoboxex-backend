<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\{Facades\Response, Str};

class PackageController extends Controller
{
    /**
     * Display a listing of the resource.
     * @api {get} /packages Get packages
     * @return packages
     */
    public function index(): JsonResponse
    {
        $packages = Package::query()
            ->with('refacciones.refaccion')
            ->get()
            ->flatMap(function ($package) {
                return $package->refacciones->map(function ($refaccion) use ($package) {
                    return [
                        'IdPaquete' => $package->IdPaquete,
                        'Paquete' => Str::trim($package->Paquete),
                        'IdRefaccion' => $refaccion->IdRefaccion,
                        'Cantidad' => (int) $refaccion->Cantidad,
                        'refaccion' => $refaccion->refaccion ? Str::trim($refaccion->refaccion->Refacción) : null,
                    ];
                });
            });

        return Response::json(
            $packages,
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
