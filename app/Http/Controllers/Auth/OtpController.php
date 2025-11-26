<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Inertia\Inertia;
use Inertia\Response;

class OtpController extends Controller
{
    public function show(Request $request): Response|RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        return Inertia::render('auth/VerifyOtp', [
            'email' => $user->email,
        ]);
    }

    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if (! $user->otp_code_hash || ! $user->otp_expires_at || Carbon::now()->greaterThan($user->otp_expires_at)) {
            return back()->withErrors(['otp' => 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.']);
        }

        if ($user->otp_attempts >= 5) {
            return back()->withErrors(['otp' => 'Terlalu banyak percobaan gagal. Silakan minta kode baru.']);
        }

        $user->otp_attempts++;
        $user->save();

        if (! Hash::check($request->input('otp'), $user->otp_code_hash)) {
            return back()->withErrors(['otp' => 'Kode OTP tidak valid.']);
        }

        // OTP valid: verifikasi email
        $user->forceFill([
            'email_verified_at' => Carbon::now(),
            'otp_code_hash' => null,
            'otp_expires_at' => null,
            'otp_attempts' => 0,
            'otp_sent_count' => 0,
        ])->save();

        return redirect()->route('dashboard')->with('status', 'Email berhasil diverifikasi.');
    }

    public function resend(Request $request): RedirectResponse
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('dashboard');
        }

        if ($user->otp_sent_count >= 5) {
            return back()->withErrors(['otp' => 'Batas pengiriman ulang OTP tercapai.']);
        }

        // Optional: simple cooldown based on expiry window (e.g. 1 minute)
        if ($user->otp_expires_at && Carbon::now()->lt($user->otp_expires_at->copy()->subMinutes(9))) {
            return back()->withErrors(['otp' => 'Silakan tunggu sebelum meminta OTP lagi.']);
        }

        $this->generateAndSendOtp($user);

        return back()->with('status', 'Kode OTP baru telah dikirim.');
    }

    public function generateAndSendOtp(User $user): void
    {
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user->otp_code_hash = Hash::make($code);
        $user->otp_expires_at = Carbon::now()->addMinutes(10);
        $user->otp_attempts = 0;
        $user->otp_sent_count = ($user->otp_sent_count ?? 0) + 1;
        $user->save();

        // For now, send a very simple email using default mailer (Postmark-configured)
        Mail::raw("Kode OTP Anda: {$code} (berlaku 10 menit)", function ($message) use ($user) {
            $message->to($user->email)
                ->subject('Kode Verifikasi OTP EllaElektrik');
        });
    }
}
