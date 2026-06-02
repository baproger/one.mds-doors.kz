<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gate;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GateController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Gate::with(['category', 'doorModel'])
            ->where('is_active', true)
            ->orderBy('sort_order');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where('name', 'ilike', '%' . $request->search . '%');
        }

        return response()->json($query->get());
    }

    public function show(Gate $gate): JsonResponse
    {
        $gate->load(['category']);

        return response()->json($gate);
    }
}
