<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoorModel;
use Illuminate\Http\JsonResponse;

class DoorModelController extends Controller
{
    public function index(): JsonResponse
    {
        $models = DoorModel::with(['gates' => function ($q) {
                $q->where('is_active', true)->orderBy('sort_order');
            }])
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->get();

        return response()->json($models);
    }

    public function show(DoorModel $doorModel): JsonResponse
    {
        $doorModel->load(['gates' => function ($q) {
            $q->where('is_active', true)->orderBy('sort_order');
        }]);

        return response()->json($doorModel);
    }
}
