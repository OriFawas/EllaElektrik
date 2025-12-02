# Laravel Sanctum API - Setup Guide

## 🎯 Apa yang Sudah Diinstall

### 1. Laravel Sanctum Package
- ✅ Package `laravel/sanctum` v4.2.1 terinstall
- ✅ Konfigurasi dipublish ke `config/sanctum.php`
- ✅ Migration `personal_access_tokens` table sudah dijalankan

### 2. Konfigurasi
- ✅ User model (`app/Models/User.php`) sudah ditambahkan trait `HasApiTokens`
- ✅ Sanctum middleware dikonfigurasi di `bootstrap/app.php`
- ✅ CORS settings dikonfigurasi di `config/cors.php`

### 3. Authentication Controller
- ✅ `app/Http/Controllers/Api/AuthController.php` dengan endpoints:
  - `POST /api/login` - Login & generate token
  - `GET /api/user` - Get authenticated user info
  - `POST /api/logout` - Revoke current token
  - `POST /api/logout-all` - Revoke all tokens

### 4. API Routes
- ✅ Routes dikonfigurasi di `routes/api.php` dengan abilities middleware

### 5. Postman Collection
- ✅ `EllaElektrik-API-Postman.json` - Collection file
- ✅ `EllaElektrik-Environment-Postman.json` - Environment file

---

## 🚀 Cara Import di Postman

### Import Collection
1. Buka Postman
2. Klik **Import** (tombol di kiri atas)
3. Pilih file `EllaElektrik-API-Postman.json`
4. Collection "Ella Elektrik API - Laravel Sanctum" akan muncul di sidebar

### Import Environment
1. Klik icon **⚙️** (Settings) di kanan atas
2. Pilih tab **Environments**
3. Klik **Import** 
4. Pilih file `EllaElektrik-Environment-Postman.json`
5. Environment "Ella Elektrik - Local" akan tersedia
6. **Aktifkan environment** dengan memilih dari dropdown di kanan atas

---

## 🔐 Token Abilities Structure

### Admin User
- **Ability**: `admin:*`
- **Access**: Semua endpoints termasuk admin endpoints
- Token digenerate saat login dengan role admin

### Regular User  
- **Ability**: `user:read`
- **Access**: Hanya protected endpoints non-admin
- Token digenerate saat login dengan role user

---

## 📝 Cara Testing di Postman

### 1. Login & Get Token

**Endpoint**: `POST {{base_url}}/api/login`

**Request Body** (untuk admin):
```json
{
    "email": "admin@example.com",
    "password": "password"
}
```

**Response**:
```json
{
    "message": "Login successful",
    "user": {
        "id": 1,
        "name": "Admin",
        "email": "admin@example.com",
        "role": "admin"
    },
    "token": "1|xxxxxxxxxxxxxxxxxxxxxxxxxxxxxx",
    "abilities": ["admin:*"]
}
```

✨ **Token akan otomatis tersimpan** di environment variable `api_token` (ada script di Test tab)

---

### 2. Test Protected Endpoints

Setelah login, token otomatis digunakan di request berikutnya.

#### Get User Info
**Endpoint**: `GET {{base_url}}/api/user`
- Header `Authorization: Bearer {{api_token}}` sudah diset otomatis
- Mengembalikan info user dan abilities

#### Admin Products (Admin Only)
**Endpoint**: `GET {{base_url}}/api/admin/products`
- Requires `admin:*` ability
- User biasa akan dapat error 403 Forbidden

#### Public Endpoints (No Auth)
**Endpoint**: `GET {{base_url}}/api/products`
- Tidak perlu authentication
- Bisa diakses siapa saja

---

### 3. Logout

**Single Device**: `POST {{base_url}}/api/logout`
- Revoke token yang sedang digunakan

**All Devices**: `POST {{base_url}}/api/logout-all`
- Revoke semua token user

---

## 🔧 Testing Workflow

### Test Flow untuk Admin:
1. **Login** → Save token
2. **Get User Info** → Verify abilities `["admin:*"]`
3. **Get Admin Products** → Success (200)
4. **Get Categories** → Success (200)
5. **Logout** → Token revoked

### Test Flow untuk Regular User:
1. **Login** (dengan email user biasa) → Save token
2. **Get User Info** → Verify abilities `["user:read"]`
3. **Get Public Products** → Success (200)
4. **Add Item to Cart** → Success (200)
5. **Get Cart** → Success - see cart items
6. **Update Cart Item** → Success - change quantity
7. **Get Admin Products** → Error 403 (Forbidden - insufficient ability)

---

## ⚙️ Environment Variables

Di Postman environment "Ella Elektrik - Local":

| Variable | Description | Auto-set? |
|----------|-------------|-----------|
| `base_url` | API base URL | ✅ Default: `http://localhost:8000` |
| `api_token` | Bearer token | ✅ Auto-saved setelah login |
| `user_id` | User ID | ✅ Auto-saved setelah login |
| `user_role` | User role (admin/user) | ✅ Auto-saved setelah login |

---

## 🗂️ Collection Structure

```
Ella Elektrik API
├── Authentication
│   ├── Login (auto-save token)
│   ├── Get User Info
│   ├── Logout
│   └── Logout All Devices
├── Public Endpoints (no auth)
│   ├── Get Subkategori
│   └── Get Products
├── Cart Endpoints (auth required)
│   ├── Get Cart
│   ├── Get Cart Count
│   ├── Add Item to Cart
│   ├── Update Cart Item Quantity
│   └── Remove Item from Cart
└── Admin Endpoints (admin:* required)
    ├── Get Admin Products
    ├── Delete Product
    ├── Get Categories
    └── Get Subcategories
```

---

## 🛒 Cart API Examples

### Add Item to Cart
**Request**: `POST {{base_url}}/api/cart/items`
```json
{
    "product_id": 1,
    "qty": 2
}
```

**Response**:
```json
{
    "id": 1,
    "user_id": 2,
    "items": [
        {
            "id": 1,
            "product_id": 1,
            "name": "Product Name",
            "slug": "product-name",
            "image": "/images/ProductImages/product.jpg",
            "qty": 2,
            "unit_price": 50000,
            "line_total": 100000
        }
    ],
    "subtotal": 100000,
    "item_count": 2
}
```

### Get Cart
**Request**: `GET {{base_url}}/api/cart`

Returns full cart with all items, subtotal, and item count.

### Update Cart Item
**Request**: `PUT {{base_url}}/api/cart/items/1`
```json
{
    "qty": 5
}
```

Set `qty` to 0 to remove item from cart.

---

## 🧪 Create Test User

Untuk testing, buat user via tinker atau seeder:

```php
php artisan tinker

// Cek apakah user sudah ada
User::where('email', 'admin@example.com')->first();

// Jika belum ada, buat admin user
User::create([
    'name' => 'Admin Test',
    'email' => 'admin@example.com',
    'password' => bcrypt('password'),
    'role' => 'admin'
]);

// Jika belum ada, buat regular user
User::create([
    'name' => 'User Test',
    'email' => 'user@example.com',
    'password' => bcrypt('password'),
    'role' => 'user'
]);
```

**Note**: User dengan email tersebut sudah ada di database Anda.

---

## 📌 Important Notes

### Token Expiration
- Token expire setelah **30 hari** (dikonfigurasi di `AuthController@login`)
- Bisa diubah dengan mengubah `now()->addDays(30)`

### Multiple Tokens
- User bisa punya multiple active tokens (multiple devices)
- Setiap login menghasilkan token baru
- Use `logout-all` untuk revoke semua token

### CORS Settings
- Configured di `config/cors.php`
- Default: allow all origins (`'*'`)
- Untuk production, ganti dengan domain spesifik

### API Routes & CSRF
- Semua API routes ada prefix `/api`
- Protected routes menggunakan `auth:sanctum` middleware
- **CSRF disabled** untuk API routes - pure token-based authentication
- `EnsureFrontendRequestsAreStateful` middleware removed dari API untuk Postman compatibility
- Admin routes tambahan menggunakan custom `CheckTokenAbility` middleware

---

## 🔧 Troubleshooting

### "CSRF token mismatch" di Postman
**Root Cause**: Request masih melewati web middleware atau CSRF validation aktif.

**Solutions Applied**: 
1. ✅ Removed `EnsureFrontendRequestsAreStateful` dari API middleware
2. ✅ Added CSRF exception untuk `api/*` routes di `bootstrap/app.php`
3. ✅ Created `VerifyCsrfToken` middleware dengan exception `api/*`
4. ✅ API sekarang pure token-based

**Checklist Postman**:
- ✅ URL menggunakan prefix `/api/` (contoh: `/api/cart/items` bukan `/cart/items`)
- ✅ Header `Authorization: Bearer {token}` ada
- ✅ Header `Accept: application/json` ada
- ✅ Header `Content-Type: application/json` untuk POST/PUT
- ✅ **JANGAN** tambahkan CSRF token atau X-CSRF-TOKEN header
- ✅ Development server sudah di-restart setelah perubahan

```bash
# Clear semua cache
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Restart server
php artisan serve
```

### "Target class [ability/abilities] does not exist"
**Root Cause**: Sanctum's default middleware aliases tidak bekerja dengan baik di Laravel 12.

**Solution**: 
- ✅ Dibuat custom middleware `CheckTokenAbility` di `app/Http/Middleware/CheckTokenAbility.php`
- ✅ Routes menggunakan full class name `\App\Http\Middleware\CheckTokenAbility::class`
- ✅ Middleware check `admin:*` ability untuk admin endpoints

```bash
php artisan optimize:clear
```

### "No query results for model [Product] X"
**Fixed**: Delete endpoint sekarang menggunakan `find()` dan return JSON response dengan proper error handling (404 jika product tidak ditemukan).

### Middleware tidak bekerja
**Solution**: 
1. Pastikan sudah clear cache
2. Cek `bootstrap/app.php` - middleware alias `check.ability` sudah terdaftar
3. Restart development server jika diperlukan

---

## ✅ Ready to Test!

Semua sudah siap dan bug sudah diperbaiki. Sekarang:
1. Import collection & environment ke Postman
2. Pastikan Laravel app running (`php artisan serve`)
3. Test user sudah ada di database (admin@example.com / user@example.com)
4. Test login dan protected endpoints

**Changes made**:
- ✅ Created custom `CheckTokenAbility` middleware (solves Laravel 12 + Sanctum compatibility)
- ✅ Fixed middleware registration - uses `check.ability` alias instead of Sanctum's default
- ✅ Fixed delete product endpoint - return JSON, handle 404 gracefully
- ✅ Added index method untuk admin products listing
- ✅ All caches cleared

**Technical Details**:
- Custom middleware di `app/Http/Middleware/CheckTokenAbility.php`
- Cek token abilities dengan proper error messages (401/403)
- Support `admin:*` wildcard untuk full admin access

Happy testing! 🎉
