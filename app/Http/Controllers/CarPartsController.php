<?php

namespace App\Http\Controllers;

use App\Models\Tool;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

class CarPartsController extends Controller
{
    public function all(): JsonResponse
    {
        $tools = Tool::all();

        return Response::json(
            $tools,
            JsonResponse::HTTP_OK
        );
    }
}
