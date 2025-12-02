# Test Cart API - Step by Step

## Server Status
✅ Server running di port 8000
✅ Cache cleared
✅ API middleware reconfigured tanpa web middleware

## Postman Test Checklist

### 1. Login dulu untuk dapat token
**Request**: `POST http://localhost:8000/api/login`

**Headers**:
```
Content-Type: application/json
Accept: application/json
```

**Body (raw JSON)**:
```json
{
    "email": "user@example.com",
    "password": "password"
}
```

**Expected Response**:
```json
{
    "message": "Login successful",
    "user": { ... },
    "token": "1|xxxxxxxxxxxxx",
    "abilities": ["user:read"]
}
```

**Action**: Copy token dari response

---

### 2. Test Add Item to Cart
**Request**: `POST http://localhost:8000/api/cart/items`

**Headers**:
```
Authorization: Bearer 1|xxxxxxxxxxxxx
Content-Type: application/json
Accept: application/json
```

**Body (raw JSON)**:
```json
{
    "product_id": 1,
    "qty": 2
}
```

**Expected Response**: Cart dengan items
```json
{
    "id": 1,
    "user_id": 2,
    "items": [...],
    "subtotal": 100000,
    "item_count": 2
}
```

---

### 3. Jika masih error CSRF

Cek di response Postman:
1. Status code berapa?
2. Response body lengkap?
3. Headers apa yang di-return server?

Screenshot atau copy-paste semua detail request & response.

---

## Alternative Testing dengan cURL

```bash
# Login
curl -X POST http://localhost:8000/api/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"email":"user@example.com","password":"password"}'

# Add to cart (ganti TOKEN dengan token dari login)
curl -X POST http://localhost:8000/api/cart/items \
  -H "Authorization: Bearer TOKEN" \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{"product_id":1,"qty":2}'
```

---

## Debug Information

Jika masih error, jalankan di terminal:
```bash
php artisan route:list --path=api/cart
```

Dan share output-nya.
