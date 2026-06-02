<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoorHandle;
use Illuminate\Http\JsonResponse;

class HandleController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            DoorHandle::where('is_active', true)->orderBy('sort_order')->get()
        );
    }
}
