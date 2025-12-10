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
            'ktp' => ['required', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ]);

        // Store file under public/images/ktp for direct access
        $file = $data['ktp'];
        $filename = Str::uuid()->toString().'.'.strtolower($file->getClientOriginalExtension());
        $publicDir = public_path('images/ktp');
        if (!is_dir($publicDir)) {
            @mkdir($publicDir, 0755, true);
        }
        $file->move($publicDir, $filename);
        $path = 'images/ktp/'.$filename;

        // Update user verification state
        $user->ktp_path = $path;
        $user->verification_status = 'pending';
        $user->verification_note = null;
        $user->save();

        return response()->json([
            'message' => 'Verification submitted',
            'verification_status' => $user->verification_status,
            'ktp_path' => $user->ktp_path ? asset($user->ktp_path) : null,
        ]);
    }
}
