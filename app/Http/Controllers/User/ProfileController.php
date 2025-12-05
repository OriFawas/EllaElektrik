<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function me(Request $request): JsonResponse
    {
        $u = $request->user();
        return response()->json([
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'phone' => $u->phone,
            'province' => $u->province,
            'city' => $u->city,
            'address' => $u->address,
            'nik' => $u->nik,
            'verification_status' => $u->verification_status ?: 'unverified',
            'verification_note' => $u->verification_note,
            'ktp_path' => $u->ktp_path ? Storage::url($u->ktp_path) : null,
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:32'],
            'province' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'address' => ['nullable', 'string', 'max:2000'],
            'nik' => ['nullable', 'string', 'regex:/^\d{16}$/'],
        ], [
            'nik.regex' => 'NIK harus 16 digit angka.',
        ]);

        $user->fill($data)->save();

        return response()->json([
            'message' => 'Profile updated',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'province' => $user->province,
                'city' => $user->city,
                'address' => $user->address,
                'nik' => $user->nik,
            ],
        ]);
    }
}
