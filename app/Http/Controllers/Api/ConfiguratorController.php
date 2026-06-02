<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoorHandle;
use App\Models\Gate;
use App\Models\Segment;
use App\Models\Texture;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ConfiguratorController extends Controller
{
    /**
     * Разрешить конфигурацию из URL-параметров, проверить совместимость, рассчитать цену.
     * GET /api/configurator/resolve?gate_id=&segment_id=&frame_type=&direction=&texture_id=&handle_id=
     */
    public function resolve(Request $request): JsonResponse
    {
        $gateId      = $request->input('gate_id');
        $segmentId   = $request->input('segment_id');
        $frameType   = $request->input('frame_type');
        $textureId   = $request->input('texture_id');
        $handleId    = $request->input('handle_id');
        $direction   = $request->input('direction', 'left');

        $gate    = $gateId    ? Gate::with(['category', 'segment'])->find($gateId)      : null;
        $segment = $segmentId ? Segment::find($segmentId)                               : null;
        $texture = $textureId ? Texture::find($textureId)                               : null;
        $handle  = $handleId  ? DoorHandle::find($handleId)                             : null;

        $warnings      = [];
        $lockedOptions = [];

        // ── Совместимость: сегменты для модели ──────────────────────────────
        $allowedSegmentIds  = null;
        $allowedFrameTypes  = null;

        if ($gate) {
            $allowedSegmentIds = $gate->allowed_segment_ids ?: null;
            $allowedFrameTypes = $gate->allowed_frame_types ?: null;

            if ($allowedSegmentIds && $segment && !in_array($segment->id, $allowedSegmentIds)) {
                $warnings[] = "Серия «{$segment->name}» недоступна для модели «{$gate->name}».";
                $segment = null;
            }

            if ($allowedFrameTypes && $frameType && !in_array($frameType, $allowedFrameTypes)) {
                $warnings[] = "Каркас «{$frameType}» недоступен для модели «{$gate->name}».";
                $frameType = null;
            }
        }

        // ── Расчёт цены ─────────────────────────────────────────────────────
        $priceBreakdown = [];
        $totalPrice     = 0;

        if ($segment) {
            $totalPrice += $segment->base_price;
            $priceBreakdown[] = [
                'label'    => "Сегмент: {$segment->name}",
                'price'    => $segment->base_price,
                'included' => false,
            ];
        }

        if ($texture) {
            $totalPrice += $texture->price;
            $priceBreakdown[] = [
                'label'    => "Цвет: {$texture->name}",
                'price'    => $texture->price,
                'included' => $texture->price === 0,
            ];
        }

        if ($handle) {
            $totalPrice += $handle->price;
            $priceBreakdown[] = [
                'label'    => "Ручка: {$handle->name}",
                'price'    => $handle->price,
                'included' => $handle->price === 0,
            ];
        }

        // ── Конфигурация каркаса из модели ──────────────────────────────────
        $resolvedConfiguration = null;
        if ($gate && $frameType && $gate->configurations) {
            foreach ($gate->configurations as $cfg) {
                if ($cfg['type'] === $frameType) {
                    $resolvedConfiguration = $cfg;
                    break;
                }
            }
        }

        return response()->json([
            'gate'          => $gate,
            'segment'       => $segment,
            'configuration' => $resolvedConfiguration,
            'frame_type'    => $frameType,
            'texture'       => $texture,
            'handle'        => $handle,
            'direction'     => $direction,

            'price'          => $totalPrice,
            'price_breakdown' => $priceBreakdown,
            'price_formatted' => number_format($totalPrice, 0, '.', ' ') . ' ₸',

            'allowed_segment_ids' => $allowedSegmentIds,
            'allowed_frame_types' => $allowedFrameTypes,

            'warnings'      => $warnings,
            'is_valid'      => empty($warnings) && $gate !== null,

            // Параметры для URL/sharing
            'params' => array_filter([
                'gate_id'    => $gate?->id,
                'segment_id' => $segment?->id,
                'frame_type' => $frameType,
                'direction'  => $direction,
                'texture_id' => $texture?->id,
                'handle_id'  => $handle?->id,
            ]),
        ]);
    }
}
