<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\GateCategory;
use Illuminate\Http\JsonResponse;

class GateCategoryController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = GateCategory::where('is_active', true)
            ->orderBy('sort_order')
            ->withCount(['gates' => fn($q) => $q->where('is_active', true)])
            ->get();

        return response()->json($categories);
    }

    public function show(GateCategory $gateCategory): JsonResponse
    {
        $gateCategory->load(['gates' => fn($q) => $q->where('is_active', true)->orderBy('sort_order')]);

        return response()->json($gateCategory);
    }
}
