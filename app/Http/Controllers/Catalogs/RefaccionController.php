<?php

namespace App\Http\Controllers\Catalogs;

use App\Http\Controllers\Controller;
use App\Models\Refaccion;
use Carbon\Carbon;
use Illuminate\Http\{JsonResponse, Request};
use Illuminate\Support\{Number, Str};
use Illuminate\Support\Facades\{Response, Validator};

class RefaccionController extends Controller
{
    /**
     * Display a listing of the resource.
     * @api {get} /refacciones Get refacciones
     * @return refacciones with mapped dates and quantities
     */
    public function index(): JsonResponse
    {
        $refacciones = Refaccion::all();

        $refacciones->map(function ($refaccion) {
            if (!is_null($refaccion->Fecha)) {
                $refaccion->Fecha = Carbon::parse($refaccion->Fecha)->isoFormat('DD [de] MMMM [de] YYYY');
                $refaccion->Cantidad = Number::format((int) $refaccion->Cantidad, 0, 1);
            }

            return $refaccion;
        });

        return Response::json(
            $refacciones,
            JsonResponse::HTTP_OK
        );
    }

    /**
     * @api {get} /refacciones/last-id Get last refaccion ID
     * @return last refaccion ID
     */
    public function getLastId(): JsonResponse
    {
        $max = Refaccion::max('IdRefaccion') + 1 ?? 0;

        return Response::json([
            'last' => $max,
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'Calidad' => ['required', 'string', 'max:255'],
            'Cantidad' => ['required', 'integer', 'min:1'],
            'Codigo' => ['required', 'string', 'unique:Refaccion,Codigo'],
            'Marca' => ['required', 'string', 'max:255'],
            'Precio' => ['required', 'numeric', 'min:0'],
            'PrecioIva' => ['required', 'numeric', 'min:0'],
            'Refacción' => ['required', 'string', 'max:255', 'unique:Refaccion,Refacción'],
            'Tipo' => ['required', 'string', 'max:255'],
            'Unidad' => ['required', 'string', 'max:255'],
        ], [
            'Codigo.unique' => "El código {$request->Codigo} ya existe en la base de datos.",
            'Refacción.unique' => "La refacción {$request->Refacción} ya existe en la base de datos.",
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        $refaccion = Refaccion::create([
            'IdRefaccion' => $request->IdRefaccion,
            'Calidad' => Str::trim($validated['Calidad']),
            'Cantidad' => $validated['Cantidad'],
            'Codigo' => Str::trim($validated['Codigo']),
            'Marca' => Str::trim($validated['Marca']),
            'Precio' => $validated['Precio'],
            'PrecioIva' => $validated['PrecioIva'],
            'Refacción' => Str::trim($validated['Refacción']),
            'Tipo' => Str::trim($validated['Tipo']),
            'Unidad' => Str::trim($validated['Unidad']),
        ]);

        return Response::json([
            $refaccion,
        ], JsonResponse::HTTP_CREATED);
    }

    /**
     * Update the specified resource in storage.
     * @api {put} /refacciones/{id} Update refaccion
     * @param Request $request
     * @return updated refaccion
     * TODO: validate request and update refaccion in database
     */
    public function update(Request $request)
    {
        return Response::json([
            'message' => 'Refacción actualizada',
        ], JsonResponse::HTTP_OK);
    }

    /**
     * Remove the specified resource from storage.
     * @api {delete} /refacciones Delete refaccion
     * @param Request $request
     * @return deleted refaccion
     */
    public function destroy(Request $request): JsonResponse
    {
        $validator = Validator::make(['id' => $request->id], [
            'id' => ['required', 'integer', 'exists:Refaccion,IdRefaccion'],
        ]);

        if ($validator->fails()) {
            return Response::json(
                $validator->errors(),
                JsonResponse::HTTP_UNPROCESSABLE_ENTITY
            );
        }

        $validated = $validator->validated();

        $refaccion = Refaccion::find($validated['id']);
        $refaccion->delete();

        return Response::json([
            'message' => 'Refacción eliminada',
        ], JsonResponse::HTTP_OK);
    }
}
