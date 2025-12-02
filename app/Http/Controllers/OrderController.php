<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function __construct(private CartService $cartService)
    {
    }

    /**
     * Display user's order list (active and completed orders)
     */
    public function index(): Response
    {
        $user = Auth::user();

        $activeOrders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'name' => $order->items->pluck('name_snapshot')->join(', '),
                'price' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                'date' => $order->created_at->format('d F Y'),
                'image' => $order->items->first()->product->image_url ?? '/images/products/default.jpg',
                'status' => 'Siap Diambil',
                'days_left' => $order->days_left,
            ]);

        $historyOrders = Order::with('items.product')
            ->where('user_id', $user->id)
            ->completed()
            ->orderBy('completed_at', 'desc')
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'name' => $order->items->pluck('name_snapshot')->join(', '),
                'price' => 'Rp ' . number_format($order->total, 0, ',', '.'),
                'date' => $order->completed_at ? $order->completed_at->format('d F Y') : $order->created_at->format('d F Y'),
                'image' => $order->items->first()->product->image_url ?? '/images/products/default.jpg',
            ]);

         $rejectedOrders = Order::with('items.product')
        ->where('user_id', $user->id)
        ->where('status', 'cancelled')
        ->orderBy('updated_at', 'desc')
        ->get()
        ->map(fn($order) => [
            'id' => $order->id,
            'name' => $order->items->pluck('name_snapshot')->join(', '),
            'price' => 'Rp ' . number_format($order->total, 0, ',', '.'),
            'date' => $order->updated_at->format('d F Y'),
            'image' => $order->items->first()->product->image_url ?? '/images/products/default.jpg',
            'reason' => $order->reject_reason ?? 'Pesanan ditolak oleh admin',
        ]);

        return Inertia::render('User/Order', [
            'activeOrders' => $activeOrders,
            'historyOrders' => $historyOrders,
            'rejectedOrders' => $rejectedOrders,
        ]);
    }

    /**
     * Show order status page
     */
    public function show(Order $order): Response|RedirectResponse
    {
        // Ensure user owns this order
        if ($order->user_id !== Auth::id()) {
            return redirect()->route('home');
        }

        return Inertia::render('StatusOrder', [
            'order' => [
                'id' => 'ORD-' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                'tanggal' => $order->created_at->format('Y-m-d'),
                'subtotal' => $order->subtotal,
                'biayaLayanan' => $order->service_fee,
                'total' => $order->total,
                'status' => $order->status === 'ready_for_pickup' ? 'ST1' : 'ST2',
                'days_left' => $order->days_left,
            ],
        ]);
    }

    /**
     * Create order from cart
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $user = Auth::user();
        // ✅ 1. Cek apakah user sudah upload KTP
        if (!$user->ktp_path) {
            $msg = 'Anda harus upload KTP dan menunggu verifikasi admin sebelum membuat pesanan.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 422);
            }
            // Redirect with a notice query param so the dashboard displays the standard inline notice (yellow/red depending on the notice)
            return redirect()->route('user.dashboard', ['notice' => 'verification_required']);
        }

        // ✅ 2. Cek apakah user sudah diverifikasi admin
        if ($user->verification_status !== 'verified') {
            $msg = 'Akun Anda belum diverifikasi admin. Tunggu persetujuan admin.';
            if ($request->expectsJson()) {
                return response()->json(['message' => $msg], 403);
            }
            // If user is pending verification, show pending notice, otherwise require upload
            $notice = $user->verification_status === 'pending' ? 'verification_pending' : 'verification_required';
            return redirect()->route('user.dashboard', ['notice' => $notice]);
        }

        $cart = $this->cartService->getActiveCart($user);
        $cart->load('items.product');

        if ($cart->items->isEmpty()) {
            return redirect()->route('checkout')->withErrors(['cart' => 'Keranjang Anda kosong.']);
        }

        DB::beginTransaction();
        try {
            $subtotal = $cart->items->sum(fn($item) => $item->qty * $item->product->price);
            $serviceFee = 2000;
            $total = $subtotal + $serviceFee;

            $order = Order::create([
                'user_id' => $user->id,
                'status' => 'ready_for_pickup',
                'subtotal' => $subtotal,
                'service_fee' => $serviceFee,
                'total' => $total,
                'pickup_deadline' => Carbon::now()->addDays(7),
            ]);

            foreach ($cart->items as $cartItem) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $cartItem->product_id,
                    'name_snapshot' => $cartItem->product->name,
                    'sku' => $cartItem->product->sku ?? null,
                    'unit_price_snapshot' => $cartItem->product->price,
                    'qty' => $cartItem->qty,
                    'subtotal' => $cartItem->qty * $cartItem->product->price,
                ]);
            }

            // Clear cart after successful order
            $this->cartService->clear($user);

            DB::commit();

                // For normal web requests redirect to the order status page and include a success flash + notice
                return redirect()->route('orders.show', ['order' => $order->id, 'notice' => 'order_success'])
                    ->with('success', 'Pesanan berhasil dibuat.');
        } catch (\Exception $e) {
            DB::rollBack();
            // Prefer flash error for consistency, and return JSON for AJAX requests
            $msg = 'Gagal membuat pesanan. Silakan coba lagi.';
            return $request->expectsJson()
                ? response()->json(['message' => $msg], 500)
                : redirect()->back()->with('error', $msg);
        }
    }
}