<?php

namespace App\Http\Controllers;

use App\Exports\ServiciosExport;
use App\Models\{InOut, Service};
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\{Request, Response as HttpResponse};
use Illuminate\Support\Facades\{Response, Validator};
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ExportController extends Controller
{
    /**
     * Exporta servicios a Excel.
     * Query params opcionales: folio (FolioOE), idCliente
     */
    public function excel(Request $request): BinaryFileResponse
    {
        $validator = Validator::make($request->all(), [
            'folio'     => ['sometimes', 'integer'],
            'idCliente' => ['sometimes', 'integer'],
        ]);

        if ($validator->fails()) {
            abort(422, $validator->errors()->first());
        }

        $folio     = $request->integer('folio')     ?: null;
        $idCliente = $request->integer('idCliente') ?: null;

        $filename = $folio
            ? "orden_servicio_{$folio}.xlsx"
            : ($idCliente ? "servicios_cliente_{$idCliente}.xlsx" : 'servicios_todos.xlsx');

        return Excel::download(new ServiciosExport($folio, $idCliente), $filename);
    }

    /**
     * Genera PDF de una orden de servicio específica.
     * Requiere query param: folio (FolioOE) o id (IdMovimiento)
     */
    public function pdf(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'folio' => ['sometimes', 'integer'],
            'id'    => ['sometimes', 'integer'],
        ]);

        if ($validator->fails()) {
            abort(422, $validator->errors()->first());
        }

        $query = Service::with(['cliente', 'vehiculo.marca']);

        if ($request->has('id')) {
            $servicio = $query->findOrFail($request->integer('id'));
        } elseif ($request->has('folio')) {
            $servicio = $query->where('FolioOE', $request->integer('folio'))->firstOrFail();
        } else {
            abort(422, 'Se requiere folio o id.');
        }

        $insumos = InOut::with('refaccion')
            ->where('IdMovimiento', $servicio->IdMovimiento)
            ->get();

        $pdf = Pdf::loadView('pdf.servicio', compact('servicio', 'insumos'))
            ->setPaper('letter', 'portrait');

        $filename = "orden_servicio_{$servicio->FolioOE}_{$servicio->IdMovimiento}.pdf";

        return $pdf->download($filename);
    }

    /**
     * Exporta TODAS las órdenes a Excel (sin filtro).
     */
    public function excelAll(): BinaryFileResponse
    {
        return Excel::download(new ServiciosExport(), 'servicios_todos.xlsx');
    }
}
