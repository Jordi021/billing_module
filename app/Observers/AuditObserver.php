<?php

namespace App\Observers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cookie;
use Carbon\Carbon;

class AuditObserver {
    public function created(Audit $audit) {
        $authCookie = Cookie::get('auth_and_user');
        if (!$authCookie) {
            return;
        }

        $authData = json_decode($authCookie, true);
        $token = $authData['auth_token'] ?? null;

        if (!$token) {
            return;
        }

        $data = [
            'date' => Carbon::parse($audit->created_at)->toIso8601String(),
            'description' => $this->generateDescription($audit),
            'event' => strtoupper($audit->event),
            'origin_service' => 'INVENTARIO',
            'user_id' => $audit->user_id,
        ];

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Accept' => 'application/json',
        ])->post('https://seri-api-utn-2024.fly.dev/api/audit', $data);

        if ($response->successful()) {
        } else {
            \Log::error('Error al enviar auditoría a la API de seguridad', [
                'response' => $response->body(),
            ]);
        }
    }

    /**
     * Generar una descripción para la auditoría.
     *
     * @param Audit $audit
     * @return string
     */
    protected function generateDescription(Audit $audit): string {
        $description = "Se realizó la acción '{$audit->event}' en el recurso '{$audit->auditable_type}' (ID: {$audit->auditable_id}).";

        if (!empty($audit->old_values) || !empty($audit->new_values)) {
            $description .= ' Detalles: ';
            if (!empty($audit->old_values)) {
                $description .=
                    'Valores antiguos: ' .
                    json_encode($audit->old_values) .
                    '. ';
            }
            if (!empty($audit->new_values)) {
                $description .=
                    'Valores nuevos: ' . json_encode($audit->new_values) . '.';
            }
        }

        return $description;
    }
}
