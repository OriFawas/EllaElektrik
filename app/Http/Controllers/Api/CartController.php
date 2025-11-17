<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    protected function transform($cart): array
    {
        $cart->loadMissing('items.product');
        return [
            'id' => $cart->id,
            'user_id' => $cart->user_id,
            'items' => $cart->items->map(function ($item) {
                return [
                    'id' => $item->id,
                    'product_id' => $item->product_id,
                    'name' => $item->product?->name,
                    'slug' => $item->product?->slug,
                    'image' => $item->product?->image_url ?: '/images/placeholder.png',
                    'qty' => $item->qty,
                    'unit_price' => (float) $item->unit_price_snapshot,
                    'line_total' => (float) ($item->qty * $item->unit_price_snapshot),
                ];
            }),
            'subtotal' => $cart->subtotal(),
            'item_count' => $cart->itemCount(),
        ];
    }

    public function show(Request $request)
    {
        $user = Auth::user();
        $cart = $this->cartService->getActiveCart($user)->load('items.product');
        return response()->json($this->transform($cart));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'qty' => 'sometimes|integer|min:1|max:99'
        ]);
        $user = Auth::user();
        $cart = $this->cartService->addItem($user, $data['product_id'], $data['qty'] ?? 1);
        return response()->json($this->transform($cart));
    }

    public function update(Request $request, int $itemId)
    {
        $data = $request->validate([
            'qty' => 'required|integer|min:0|max:99'
        ]);
        $user = Auth::user();
        $cart = $this->cartService->updateQty($user, $itemId, $data['qty']);
        return response()->json($this->transform($cart));
    }

    public function destroy(Request $request, int $itemId)
    {
        $user = Auth::user();
        $cart = $this->cartService->removeItem($user, $itemId);
        return response()->json($this->transform($cart));
    }

        public function count()
    {
        $user = Auth::user();
        $cart = $this->cartService->getActiveCart($user);

        return response()->json([
            'count' => $cart ? $cart->itemCount() : 0,
        ]);
    }

}
