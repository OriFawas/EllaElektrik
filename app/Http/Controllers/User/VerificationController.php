<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VerificationController extends Controller
{
    public function submit(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'ktp' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:5120', // 5MB
            ],
        ]);

        // ✅ (Optional) Hapus KTP lama kalau user upload ulang
        if ($user->ktp_path && Storage::exists($user->ktp_path)) {
            Storage::delete($user->ktp_path);
        }

        $file = $data['ktp'];

        // ✅ Nama file random & aman
        $filename = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();

        // ✅ Simpan ke STORAGE PRIVATE (BUKAN PUBLIC)
        $path = $file->storeAs(
            'private/ktp',
            $filename
        );

        // ✅ Update status verifikasi
        $user->ktp_path = $path; // SIMPAN PATH INTERNAL, BUKAN URL
        $user->verification_status = 'pending';
        $user->verification_note = null;
        $user->verified_at = null;
        $user->verified_by = null;
        $user->save();

        return response()->json([
            'message' => 'Verification submitted successfully',
            'verification_status' => $user->verification_status,
            'ktp_path' => route('user.ktp.view', ['_ts' => now()->timestamp]),
            'ktp_filetype' => strtolower((string) $file->getClientOriginalExtension()),
        ]);
    }

    public function viewMyKtp(Request $request)
    {
        $user = $request->user();

        abort_if(!$user->ktp_path, 404);
        abort_if(!Storage::disk('local')->exists($user->ktp_path), 404);

        $fullPath = Storage::disk('local')->path($user->ktp_path);

        return response()->file($fullPath, [
            'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
            'Pragma' => 'no-cache',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }
}
