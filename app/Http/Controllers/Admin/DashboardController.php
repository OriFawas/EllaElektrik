<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DashboardController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $period = $request->query('period', 'monthly');
        [$from, $to] = $this->resolveRange($period);

        // Statistics
        $totalOrders = Order::count();
        $activeOrders = Order::where('status', 'ready_for_pickup')->count();
        $completedOrders = Order::where('status', 'completed')->count();
        $canceledOrders = Order::where('status', 'cancelled')->count();
        
        $totalUsers = User::whereNull('role')->orWhere('role', '!=', User::ROLE_ADMIN)->count();
        $pendingVerifications = User::where('verification_status', 'pending')->count();
        $verifiedUsers = User::where('verification_status', 'verified')->count();
        
        $totalProducts = Product::count();

        // Active orders (ready for pickup - latest 10)
        $pendingOrders = Order::with('items', 'user')
            ->where('status', 'ready_for_pickup')
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($order) {
                return [
                    'id' => $order->id,
                    'nik' => $order->user ? $order->user->nik : '-',
                    'nama' => $order->user ? $order->user->name : 'Unknown',
                    'products' => $order->items->pluck('name_snapshot')->filter()->join(', '),
                    'totalPrice' => (float) $order->total,
                    'totalPriceFormatted' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                    'createdAt' => $order->created_at ? $order->created_at->format('d M Y, H:i') : '-',
                    'status' => 'ready_for_pickup',
                ];
            });

        // Pending verifications (latest 10)
        $pendingVerificationUsers = User::where('verification_status', 'pending')
            ->orderByDesc('updated_at')
            ->limit(10)
            ->get()
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone ?? '-',
                    'nik' => $user->nik ?? '-',
                    'ktp_url' => $user->ktp_path ? (str_starts_with($user->ktp_path, 'images/') ? asset($user->ktp_path) : \Storage::url($user->ktp_path)) : null,
                    'verification_status' => $user->verification_status ?? 'unverified',
                    'updated_at' => $user->updated_at ? $user->updated_at->format('d M Y, H:i') : '-',
                ];
            });

        return response()->json([
            'stats' => [
                'totalOrders' => $totalOrders,
                'activeOrders' => $activeOrders,
                'completedOrders' => $completedOrders,
                'canceledOrders' => $canceledOrders,
                'totalUsers' => $totalUsers,
                'pendingVerifications' => $pendingVerifications,
                'verifiedUsers' => $verifiedUsers,
                'totalProducts' => $totalProducts,
            ],
            'pendingOrders' => $pendingOrders,
            'pendingVerifications' => $pendingVerificationUsers,
        ]);
    }

    private function resolveRange(string $period): array
    {
        $now = Carbon::now();
        switch ($period) {
            case 'weekly':
                return [$now->copy()->startOfWeek(), $now->copy()->endOfWeek()];
            case 'yearly':
                return [$now->copy()->startOfYear(), $now->copy()->endOfYear()];
            case 'monthly':
            default:
                return [$now->copy()->startOfMonth(), $now->copy()->endOfMonth()];
        }
    }
}
