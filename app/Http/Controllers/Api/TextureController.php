<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Texture;
use Illuminate\Http\JsonResponse;

class TextureController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json(
            Texture::where('is_active', true)->orderBy('sort_order')->get()
        );
    }
}
