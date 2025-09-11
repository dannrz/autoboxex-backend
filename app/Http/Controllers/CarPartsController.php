<?php

namespace App\Http\Controllers;

use App\Models\Costo;
use App\Models\Insumo;
use App\Models\Precio;
use App\Models\Tool;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CarPartsController extends Controller
{
    public function all(): JsonResponse
    {
        $tools = Tool::all();
        $tools->map(function ($tool) {
            $tool->last_modify = Carbon::createFromDate($tool->last_modify)->format('d/m/Y H:i:s');
        });

        return Response::json(
            $tools,
            JsonResponse::HTTP_OK
        );
    }

    public function getInsumos(): JsonResponse
    {
        $insumos = Insumo::all();

        return Response::json(
            $insumos,
            JsonResponse::HTTP_OK
        );
    }

    public function getPrecios(): JsonResponse
    {
        $precios = Precio::all();

        return Response::json(
            $precios,
            JsonResponse::HTTP_OK
        );
    }

    public function getCostos(): JsonResponse
    {
        $costos = Costo::all();

        return Response::json(
            $costos,
            JsonResponse::HTTP_OK
        );
    }
}
