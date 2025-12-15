<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class UserVerificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->string('status')->toString();
        $q = $request->string('q')->toString();
        $perPage = max(1, min(100, (int) $request->query('per_page', 10)));

        $query = User::query()
            ->select([
                'id','name','email','phone',
                'province','city','address',
                'nik','ktp_path',
                'verification_status','verification_note','verified_at'
            ])
            // exclude admin
            ->where(function ($q) {
                $q->whereNull('role')
                  ->orWhere('role', '!=', User::ROLE_ADMIN);
            })
            ->when(
                in_array($status, ['pending', 'verified', 'rejected', 'unverified']),
                fn ($q2) => $q2->where('verification_status', $status)
            )
            ->when($q !== '', function ($q2) use ($q) {
                $q2->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%$q%")
                       ->orWhere('email', 'like', "%$q%")
                       ->orWhere('phone', 'like', "%$q%")
                       ->orWhere('nik', 'like', "%$q%")
                       ->orWhere('address', 'like', "%$q%");
                });
            })
            ->orderByRaw("CASE WHEN verification_status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('id');

        $paginator = $query->paginate($perPage)->appends($request->query());

        $items = collect($paginator->items())->map(function (User $u) {
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
                'address' => $u->address,
                // ✅ MASKING
                'nik' => $u->nik ? substr($u->nik, 0, 6).'******' : null,
                // ✅ FLAG SAJA, BUKAN URL
                'has_ktp' => (bool) $u->ktp_path,
                'verification_status' => $u->verification_status ?: 'unverified',
                'verification_note' => $u->verification_note,
                'verified_at' => $u->verified_at,
            ];
        });

        return response()->json([
            'data' => $items,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'from' => $paginator->firstItem(),
                'to' => $paginator->lastItem(),
            ],
        ]);
    }

    public function approve(Request $request, User $user): JsonResponse
    {
        $user->verification_status = 'verified';
        $user->verification_note = null;
        $user->verified_at = now();
        $user->verified_by = Auth::id();
        $user->save();

        return response()->json([
            'message' => 'User verified',
            'id' => $user->id,
            'verification_status' => $user->verification_status,
            'verified_at' => $user->verified_at,
        ]);
    }

    public function reject(Request $request, User $user): JsonResponse
    {
        $data = $request->validate([
            'reason' => ['required', 'string', 'max:2000'],
        ]);

        $user->verification_status = 'rejected';
        $user->verification_note = $data['reason'];
        $user->verified_at = null;
        $user->verified_by = null;
        $user->save();

        return response()->json([
            'message' => 'User rejected',
            'id' => $user->id,
            'verification_status' => $user->verification_status,
            'verification_note' => $user->verification_note,
        ]);
    }

     public function viewKtp(User $user)
{
    abort_if(!$user->ktp_path, 404);

    abort_if(!Storage::disk('local')->exists($user->ktp_path), 404);

    $fullPath = Storage::disk('local')->path($user->ktp_path);

    return response()->file($fullPath, [
        'Cache-Control' => 'no-store, no-cache, must-revalidate, max-age=0',
        'Pragma' => 'no-cache',
        'X-Content-Type-Options' => 'nosniff',
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
