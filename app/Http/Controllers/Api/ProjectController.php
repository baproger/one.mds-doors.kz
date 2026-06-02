<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'house_photo' => 'required|image|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $path = $request->file('house_photo')->store('uploads/houses', 'public');

        $project = Project::create([
            'user_id' => $request->user()?->id,
            'session_id' => \Illuminate\Support\Str::uuid(),
            'source_image' => $path,
        ]);

        return response()->json($project, 201);
    }

    public function saveCanvas(Request $request, Project $project): JsonResponse
    {
        $request->validate([
            'canvas_state' => 'required|array',
        ]);

        $project->update(['canvas_state' => $request->canvas_state]);

        return response()->json($project);
    }

    public function saveResult(Request $request, Project $project): JsonResponse
    {
        $request->validate([
            'image' => 'required|string',
        ]);

        $imageData = $request->input('image');

        if (str_contains($imageData, ',')) {
            $imageData = explode(',', $imageData)[1];
        }

        $filename = 'uploads/renders/render_' . $project->id . '_' . time() . '.png';
        Storage::disk('public')->put($filename, base64_decode($imageData));

        $project->update(['result_image' => $filename]);

        return response()->json($project);
    }
}
