<?php

namespace App\Observers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cookie;

class AuditObserver {
    /**
     * Handle the Audit "created" event.
     *
     * @param Audit $audit
     * @return void
     */
    public function created(Audit $audit) {
        $payload = $this->transformAuditData($audit);

        $authCookie = Cookie::get('auth_and_user');
        if (!$authCookie) {
            Log::error('No se encontró la cookie "auth_and_user".');
            return;
        }

        $authData = json_decode($authCookie, true);
        $token = $authData['auth_token'] ?? null;

        if (!$token) {
            Log::error('No se encontró el token en la cookie.');
            return;
        }

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ])->post('https://seri-api-utn-2024.fly.dev/api/audit', $payload);

        if ($response->failed()) {
            Log::error(
                'Error al enviar el registro de auditoría a la API de seguridad',
                [
                    'status' => $response->status(),
                    'headers' => $response->headers(),
                    'response' => $response->body(),
                    'payload' => $payload,
                ]
            );
        }
    }

    /**
     * Transforma los datos de auditoría al formato esperado por la API de seguridad.
     *
     * @param Audit $audit
     * @return array
     */
    protected function transformAuditData(Audit $audit) {
        $eventMap = [
            'created' => 'INSERT',
            'updated' => 'UPDATE',
            'deleted' => 'DELETE',
        ];

        $event = $eventMap[$audit->event] ?? 'UNKNOWN';

        $description = "Se realizó la acción {$audit->event} en el modelo {$audit->auditable_type} con ID {$audit->auditable_id}.";

        if ($audit->event === 'updated') {
            $changes = [];
            if ($audit->old_values && $audit->new_values) {
                $oldValues = json_decode($audit->old_values, true);
                $newValues = json_decode($audit->new_values, true);

                foreach ($oldValues as $key => $oldValue) {
                    $newValue = $newValues[$key] ?? null;
                    $changes[] = "{$key}: {$oldValue} -> {$newValue}";
                }
            }
            if (!empty($changes)) {
                $description .= ' Cambios: ' . implode(', ', $changes);
            }
        }

        return [
            'date' => $audit->created_at->toIso8601String(),
            'description' => $description,
            'event' => $event,
            'origin_service' => 'FACTURACION',
            'user_id' => $userInfo['id'] ?? 'system',
        ];
    }
}
