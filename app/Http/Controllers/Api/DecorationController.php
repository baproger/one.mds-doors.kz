<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Decoration;
use Illuminate\Http\JsonResponse;

class DecorationController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Decoration::where('is_active', true)->orderBy('sort_order')->get()
        );
    }
}
