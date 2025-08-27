<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class CatalogsController extends Controller
{
    public function getServices(): JsonResponse
    {
        $services = DB::table('services_catalog')->get(['name', 'code']);

        return Response::json(
            $services,
            JsonResponse::HTTP_OK
        );
    }

    public function getStates(): JsonResponse
    {
        $states = DB::table('states_catalog')->get(['name', 'code']);

        return Response::json(
            $states,
            JsonResponse::HTTP_OK
        );
    }
}
