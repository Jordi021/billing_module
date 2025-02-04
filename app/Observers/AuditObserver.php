<?php

namespace App\Observers;

use OwenIt\Auditing\Models\Audit;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cookie;

class AuditObserver {
    public function created(Audit $audit) {
        $authCookie = Cookie::get('auth_and_user');

        $authData = json_decode($authCookie, true);

        $token = $authData['auth_token'] ?? null;

        $url = 'https://seri-api-utn-2024.fly.dev/api/audit';

        $data = [
            'date' => Carbon::parse($audit->created_at)->toIso8601String(),
            'description' => $this->generateDescription($audit),
            'event' => strtoupper($audit->event),
            'origin_service' => 'FACTURACION',
            'user_id' => auth()->id() ? (string) auth()->id() : '1',
        ];

        try {
            #$response = Http::post($url, $data);
            $response = Http::withHeaders([
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $token,
            ])->post($url, $data);

            if ($response->successful()) {
                Log::info('Auditoría enviada con éxito: ', $data);
            } else {
                Log::error(
                    'Error al enviar auditoría. Código: ' . $response->status(),
                    [
                        'response' => $response->body(),
                        'data' => $data,
                    ]
                );
            }
        } catch (\Exception $e) {
            Log::error('Excepción al enviar auditoría: ' . $e->getMessage(), [
                'data' => $data,
            ]);
        }
    }

    private function generateDescription(Audit $audit): string {
        return "Se realizó un evento {$audit->event} en {$audit->auditable_type} con ID {$audit->auditable_id}.";
    }
}
