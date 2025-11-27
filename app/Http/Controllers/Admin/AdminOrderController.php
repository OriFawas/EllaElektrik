<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class AdminOrderController extends Controller
{
    /**
     * Display admin order dashboard
     */
    public function index(): Response
    {
        $pesananUser = Order::with(['user', 'items'])
            ->active()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'nik' => $order->user->nik ?? '-',
                'nama' => $order->user->name,
                'products' => $order->items->pluck('name_snapshot')->join(', '),
                'totalPrice' => $order->total,
                'createdAt' => $order->created_at->format('Y-m-d'),
            ]);

        $riwayat = Order::with(['user', 'items'])
            ->whereIn('status', ['completed', 'cancelled'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(fn($order) => [
                'id' => $order->id,
                'nik' => $order->user->nik ?? '-',
                'nama' => $order->user->name,
                'products' => $order->items->pluck('name_snapshot')->join(', '),
                'totalPrice' => $order->total,
                'createdAt' => $order->completed_at ? $order->completed_at->format('Y-m-d') : $order->created_at->format('Y-m-d'),
                'status' => match ($order->status) {
            'completed' => 'Selesai',
            'cancelled' => 'Ditolak',
        }
            ]);

        return Inertia::render('OrderDashboard', [
            'pesananUser' => $pesananUser,
            'riwayat' => $riwayat,
        ]);
    }

    /**
     * Mark order as completed (customer picked up and paid)
     */
    public function complete(Order $order): RedirectResponse
    {
        $order->update([
            'status' => 'completed',
            'completed_at' => Carbon::now(),
        ]);

        return redirect()->back()->with('success', 'Pesanan berhasil diselesaikan.');
    }

    /**
     * Delete/cancel order
     */
    public function destroy(Order $order): RedirectResponse
    {
        $order->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Pesanan berhasil dibatalkan.');
    }

    public function reject(Order $order, Request $request): RedirectResponse
{
    $order->update([
        'status' => 'cancelled',
        'completed_at' => null,
    ]);

    return redirect()->back()->with('success', 'Pesanan berhasil ditolak.');
}

}
