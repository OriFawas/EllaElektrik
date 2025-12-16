<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class RecaptchaService
{
    protected $siteKey;
    protected $secretKey;
    protected $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';
    
    public function __construct()
    {
        $this->siteKey = config('recaptcha.site_key', '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI');
        $this->secretKey = config('recaptcha.secret_key', '6LeIxAcTAAAAAGG-vFI1TnRWxMZNFuojJ4WifJWe');
    }
    
    /**
     * Verify reCAPTCHA token
     */
    public function verify($token, $ip = null)
    {
        // Jika token kosong atau null, langsung return false
        if (empty($token) || $token === null) {
            Log::warning('reCAPTCHA token empty or null');
            return false;
        }
        
        // Jika di localhost dan token adalah test token, auto verify
        if (app()->environment('local') && $token === 'test-token') {
            Log::info('Local test reCAPTCHA token accepted');
            return true;
        }
        
        try {
            $response = Http::timeout(10)->asForm()->post($this->verifyUrl, [
                'secret' => $this->secretKey,
                'response' => $token,
                'remoteip' => $ip ?? request()->ip(),
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                
                Log::debug('reCAPTCHA response', [
                    'success' => $data['success'] ?? false,
                    'score' => $data['score'] ?? null,
                    'action' => $data['action'] ?? null,
                    'hostname' => $data['hostname'] ?? null,
                ]);
                
                return isset($data['success']) && $data['success'] === true;
            }
            
            Log::error('reCAPTCHA verification failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);
            
            return false;
            
        } catch (\Exception $e) {
            Log::error('reCAPTCHA verification exception', [
                'message' => $e->getMessage(),
                'token' => substr($token, 0, 50) . '...',
            ]);
            
            // Fallback: jika error, tetap izinkan (untuk development)
            if (app()->environment('local')) {
                return true;
            }
            
            return false;
        }
    }
    
    /**
     * Get site key for frontend
     */
    public function getSiteKey()
    {
        return $this->siteKey;
    }
    
    /**
     * Render widget HTML
     */
    public function renderWidget($attributes = [])
    {
        $defaults = [
            'data-sitekey' => $this->siteKey,
            'data-callback' => 'onRecaptchaSuccess',
            'data-expired-callback' => 'onRecaptchaExpired',
            'data-error-callback' => 'onRecaptchaError',
            'class' => 'g-recaptcha',
        ];
        
        $attributes = array_merge($defaults, $attributes);
        
        $html = '<div';
        foreach ($attributes as $key => $value) {
            $html .= ' ' . $key . '="' . e($value) . '"';
        }
        $html .= '></div>';
        
        return $html;
    }
}