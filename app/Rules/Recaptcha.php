<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Recaptcha implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Jika di environment local dan recaptcha disabled, skip validation
        if (app()->environment('local') && !config('services.recaptcha.enabled', true)) {
            return;
        }

        // Jika value kosong, langsung fail
        if (empty($value)) {
            $fail('Silakan verifikasi bahwa Anda bukan robot.');
            return;
        }

        $secretKey = config('services.recaptcha.secret_key');
        
        if (empty($secretKey)) {
            Log::error('reCAPTCHA secret key not configured');
            $fail('Konfigurasi keamanan tidak valid. Silakan hubungi administrator.');
            return;
        }

        try {
            $response = Http::asForm()
                ->timeout(10)
                ->post('https://www.google.com/recaptcha/api/siteverify', [
                    'secret' => $secretKey,
                    'response' => $value,
                    'remoteip' => request()->ip(),
                ]);

            $data = $response->json();

            Log::info('reCAPTCHA verification result', [
                'success' => $data['success'] ?? false,
                'score' => $data['score'] ?? null,
                'action' => $data['action'] ?? null,
                'hostname' => $data['hostname'] ?? null,
                'error_codes' => $data['error-codes'] ?? [],
                'ip' => request()->ip(),
            ]);

            if (!($data['success'] ?? false)) {
                $errorMessage = $this->getErrorMessage($data['error-codes'] ?? []);
                $fail($errorMessage);
                return;
            }

            // Optional: Check score for reCAPTCHA v3
            if (isset($data['score'])) {
                $threshold = config('services.recaptcha.score_threshold', 0.5);
                if ($data['score'] < $threshold) {
                    $fail('Aktivitas mencurigakan terdeteksi. Silakan coba lagi.');
                    return;
                }
            }

        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification failed', [
                'error' => $e->getMessage(),
                'ip' => request()->ip(),
            ]);
            
            $fail('Verifikasi keamanan gagal. Silakan coba lagi dalam beberapa saat.');
        }
    }

    /**
     * Get human readable error message from error codes.
     */
    private function getErrorMessage(array $errorCodes): string
    {
        if (empty($errorCodes)) {
            return 'Verifikasi reCAPTCHA gagal. Silakan coba lagi.';
        }

        $messages = [
            'missing-input-secret' => 'Konfigurasi keamanan server tidak valid.',
            'invalid-input-secret' => 'Kunci keamanan tidak valid.',
            'missing-input-response' => 'Harap verifikasi bahwa Anda bukan robot.',
            'invalid-input-response' => 'Respon verifikasi tidak valid.',
            'bad-request' => 'Permintaan tidak valid.',
            'timeout-or-duplicate' => 'Waktu verifikasi habis. Silakan coba lagi.',
        ];

        foreach ($errorCodes as $errorCode) {
            if (isset($messages[$errorCode])) {
                return $messages[$errorCode];
            }
        }

        return 'Verifikasi keamanan gagal. Kode error: ' . implode(', ', $errorCodes);
    }
}