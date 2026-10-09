<?php

namespace App\Http\Controllers;

use App\Services\RestaurantRealtime;
use App\Services\RestaurantSnapshot;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RealtimeController extends Controller
{
    public function snapshot(Request $request, RestaurantSnapshot $snapshot): JsonResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'mesero']), 403);
        $data = $request->validate(['pedido' => ['nullable', 'integer', 'min:1']]);

        return response()->json($snapshot->get($request->user()->role === 'admin', $data['pedido'] ?? null))
            ->header('Cache-Control', 'no-store, private');
    }

    public function connection(Request $request, RestaurantRealtime $realtime): JsonResponse
    {
        abort_unless(in_array($request->user()->role, ['admin', 'mesero']), 403);

        return response()->json(['token' => $realtime->token($request->user())])
            ->header('Cache-Control', 'no-store, private');
    }
}
