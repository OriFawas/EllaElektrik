<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class UserVerificationController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $status = $request->string('status')->toString();
        $q = $request->string('q')->toString();
        $perPage = max(1, min(100, (int) $request->query('per_page', 10)));

        $query = User::query()
            ->select(['id','name','email','phone','province','city','address','nik','ktp_path','verification_status','verification_note','verified_at'])
            // Exclude admins from the list
            ->where(function($q){
                $q->whereNull('role')->orWhere('role', '!=', User::ROLE_ADMIN);
            })
            ->when(in_array($status, ['pending','verified','rejected','unverified']), function ($q2) use ($status) {
                $q2->where('verification_status', $status);
            })
            ->when($q !== '', function ($q2) use ($q) {
                $q2->where(function ($qq) use ($q) {
                    $qq->where('name', 'like', "%$q%")
                       ->orWhere('email', 'like', "%$q%")
                       ->orWhere('phone', 'like', "%$q%")
                       ->orWhere('nik', 'like', "%$q%")
                       ->orWhere('address', 'like', "%$q%");
                });
            })
            // Put pending on top, then newest
            ->orderByRaw("CASE WHEN verification_status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('id');

        $paginator = $query->paginate($perPage)->appends($request->query());

        $items = collect($paginator->items())->map(function ($u) {
            $ktpUrl = null;
            if ($u->ktp_path) {
                if (str_starts_with($u->ktp_path, 'images/')) {
                    $candidate = public_path($u->ktp_path);
                    if (is_file($candidate)) {
                        $ktpUrl = asset($u->ktp_path);
                    }
                } else {
                    // legacy storage path: prefer migrating/copying to public images for direct access
                    $disk = \Illuminate\Support\Facades\Storage::disk('public');
                    $basename = basename($u->ktp_path);
                    $publicTarget = 'images/ktp/' . $basename;
                    $publicPath = public_path($publicTarget);
                    if (!is_file($publicPath) && $disk->exists($u->ktp_path)) {
                        @mkdir(dirname($publicPath), 0755, true);
                        @copy($disk->path($u->ktp_path), $publicPath);
                    }
                    if (is_file($publicPath)) {
                        $ktpUrl = asset($publicTarget);
                    } elseif ($disk->exists($u->ktp_path)) {
                        // fallback to storage URL if copy failed
                        $ktpUrl = $disk->url($u->ktp_path);
                    }
                }
            }
            return [
                'id' => $u->id,
                'name' => $u->name,
                'email' => $u->email,
                'phone' => $u->phone,
                'address' => $u->address,
                'nik' => $u->nik,
                'ktp_url' => $ktpUrl,
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
            'reason' => ['required','string','max:2000'],
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
}
