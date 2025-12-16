<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Fortify\Features;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login page.
     */
    public function create(Request $request): Response
    {
        // Ambil site key dari config untuk dikirim ke frontend
        $recaptchaSiteKey = config('services.recaptcha.site_key');
        
        return Inertia::render('auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => $request->session()->get('status'),
            'recaptcha_site_key' => $recaptchaSiteKey,
            'recaptcha_enabled' => config('services.recaptcha.enabled', true),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        try {
            // Validasi dan autentikasi dilakukan oleh LoginRequest
            $user = $request->validateCredentials();

            // Cek apakah user memiliki two-factor authentication
            if (Features::enabled(Features::twoFactorAuthentication()) && $user->hasEnabledTwoFactorAuthentication()) {
                $request->session()->put([
                    'login.id' => $user->getKey(),
                    'login.remember' => $request->boolean('remember'),
                ]);

                return to_route('two-factor.login');
            }

            // Login user
            Auth::login($user, $request->boolean('remember'));

            $request->session()->regenerate();

            // Log login sukses
            activity()
                ->causedBy($user)
                ->withProperties([
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'recaptcha_verified' => true,
                ])
                ->log('User logged in');

            // Determine intended URL; avoid redirecting to JSON API endpoints (e.g. /api/*)
            $intended = $request->session()->pull('url.intended');
            $intendedPath = $intended ? parse_url($intended, PHP_URL_PATH) : null;
            $isApiIntended = $intendedPath && str_starts_with($intendedPath, '/api/');

            // Choose a safe post-login landing page based on role
            $fallback = $user->role === 'admin' ? route('dashboard') : route('home');

            if ($intended && ! $isApiIntended) {
                return redirect()->to($intended);
            }

            return redirect()->to($fallback);

        } catch (\Exception $e) {
            // Log error
            \Log::error('Login failed', [
                'email' => $request->input('email'),
                'ip' => $request->ip(),
                'error' => $e->getMessage(),
            ]);
            
            // Re-throw the exception to be handled by Laravel
            throw $e;
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Log logout activity
        if (Auth::check()) {
            activity()
                ->causedBy(Auth::user())
                ->log('User logged out');
        }

        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}