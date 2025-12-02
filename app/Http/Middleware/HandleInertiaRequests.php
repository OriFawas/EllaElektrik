<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use App\Models\KategoriProduct;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
{
    [$message, $author] = str(Inspiring::quotes()->random())->explode('-');

    return [
        ...parent::share($request),
        'name' => config('app.name'),
        'quote' => ['message' => trim($message), 'author' => trim($author)],
        'auth' => [
            'user' => $request->user() ? [
                'id' => $request->user()->id,
                'name' => $request->user()->name,
                'email' => $request->user()->email,
                'role' => $request->user()->role,
                'avatar' => $request->user()->avatar, // If you use avatars
                'email_verified_at' => $request->user()->email_verified_at,
                // Profile fields
                'phone' => $request->user()->phone,
                'province' => $request->user()->province,
                'city' => $request->user()->city,
                'address' => $request->user()->address,
                'nik' => $request->user()->nik,
                // Verification fields
                'verification_status' => $request->user()->verification_status ?: 'unverified',
                'verification_note' => $request->user()->verification_note,
                'verified_at' => $request->user()->verified_at,
                'ktp_path' => $request->user()->ktp_path,
                'created_at' => $request->user()->created_at,
                'updated_at' => $request->user()->updated_at,
            ] : null,
        ],
        'flash' => [
            'success' => fn () => $request->session()->get('success'),
            'error' => fn () => $request->session()->get('error'),
        ],
        'sidebarOpen' => ! $request->hasCookie('sidebar_state') || $request->cookie('sidebar_state') === 'true',
        // Categories for header dropdown — include subcategories
        'categories' => fn () => KategoriProduct::with(['subkategories' => function ($q) { $q->select('id', 'name', 'slug', 'kategori_product_id')->orderBy('name'); }])->select('id', 'name', 'slug')->orderBy('name')->get()->map(function ($c) {
            return [
                'id' => $c->id,
                'name' => $c->name,
                'slug' => $c->slug,
                'subkategories' => $c->subkategories->map(function ($s) { return ['id' => $s->id, 'name' => $s->name, 'slug' => $s->slug]; }),
            ];
        }),
    ];
}
}