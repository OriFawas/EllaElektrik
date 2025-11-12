<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CartService
{
    const MAX_QTY = 99;

    public function getActiveCart(User $user): Cart
    {
        return Cart::firstOrCreate([
            'user_id' => $user->id,
            'status' => 'active',
        ]);
    }

    public function addItem(User $user, int $productId, int $qty = 1): Cart
    {
        $qty = max(1, min(self::MAX_QTY, $qty));

        $product = Product::where('id', $productId)->where('is_active', true)->first();
        if (!$product) {
            throw ValidationException::withMessages(['product_id' => 'Produk tidak tersedia.']);
        }

        return DB::transaction(function () use ($user, $product, $qty) {
            $cart = $this->getActiveCart($user);

            $item = CartItem::where('cart_id', $cart->id)
                ->where('product_id', $product->id)
                ->lockForUpdate()
                ->first();

            if ($item) {
                $newQty = min(self::MAX_QTY, $item->qty + $qty);
                $item->update(['qty' => $newQty]);
            } else {
                $item = CartItem::create([
                    'cart_id' => $cart->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'unit_price_snapshot' => (float) $product->price,
                ]);
            }

            // Eager load fresh items
            return $cart->load(['items.product']);
        });
    }

    public function updateQty(User $user, int $itemId, int $qty): Cart
    {
        $qty = max(0, min(self::MAX_QTY, $qty));

        return DB::transaction(function () use ($user, $itemId, $qty) {
            $cart = $this->getActiveCart($user);
            $item = CartItem::where('id', $itemId)->where('cart_id', $cart->id)->lockForUpdate()->firstOrFail();

            if ($qty <= 0) {
                $item->delete();
            } else {
                $item->update(['qty' => $qty]);
            }

            return $cart->load(['items.product']);
        });
    }

    public function removeItem(User $user, int $itemId): Cart
    {
        return DB::transaction(function () use ($user, $itemId) {
            $cart = $this->getActiveCart($user);
            CartItem::where('id', $itemId)->where('cart_id', $cart->id)->delete();
            return $cart->load(['items.product']);
        });
    }

    public function clear(User $user): void
    {
        $cart = $this->getActiveCart($user);
        $cart->items()->delete();
    }
}
