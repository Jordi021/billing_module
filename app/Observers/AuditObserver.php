<?php

namespace App\Observers;

use App\Models\OwenIt\Auditing\Models\Audit;

class AuditObserver {
    /**
     * Handle the Audit "created" event.
     *
     * @param Audit $audit
     * @return void
     */
    public function created(Audit $audit) {
        $payload = $this->transformAuditData($audit);

        $response = Http::post(
            'https://seri-api-utn-2024.fly.dev/api/audit-logs',
            $payload
        );

        if ($response->failed()) {
            Log::error(
                'Error al enviar el registro de auditoría a la API de seguridad',
                [
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
            foreach ($audit->old_values as $key => $oldValue) {
                $newValue = $audit->new_values[$key] ?? null;
                $changes[] = "{$key}: {$oldValue} -> {$newValue}";
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
            'user_id' => $audit->user_id,
        ];
    }
}
