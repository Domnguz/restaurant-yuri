<?php

namespace App\Services;

use App\Models\Pedido;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class RestaurantRealtime
{
    public function token(User $user): string
    {
        $payload = rtrim(strtr(base64_encode(json_encode([
            'sub' => $user->id,
            'role' => $user->role,
            'exp' => now()->addMinutes(5)->timestamp,
        ], JSON_THROW_ON_ERROR)), '+/', '-_'), '=');

        return $payload.'.'.hash_hmac('sha256', $payload, (string) config('services.realtime.secret'));
    }

    public function publish(Pedido $pedido, string $action): void
    {
        // Notify only after the database transaction has committed successfully.
        $event = [
            'event_id' => (string) Str::uuid(),
            'action' => $action,
            'pedido' => ['id' => $pedido->id, 'mesa_id' => $pedido->mesa_id, 'estado' => $pedido->estado],
        ];

        DB::afterCommit(function () use ($event) {
            try {
                Http::withToken((string) config('services.realtime.secret'))
                    ->connectTimeout(1)->timeout(2)
                    ->post(rtrim(config('services.realtime.url'), '/').'/api/pedidos/actualizado', $event)
                    ->throw();
            } catch (\Throwable $exception) {
                // The order is already saved. Browsers reconcile through the authenticated snapshot.
                logger()->warning('No se pudo publicar la actualización en Node.', [
                    'pedido_id' => $event['pedido']['id'], 'error' => $exception->getMessage(),
                ]);
            }
        });
    }
}
