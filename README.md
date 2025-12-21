
```bash
git clone https://github.com/username/nama-repository.git
cd nama-repository
```

### 2. Install Dependency

Pastikan **Composer** sudah terinstall, lalu jalankan:

```bash
composer install
```

### 3. Buat File Environment

Salin file `.env.example` menjadi `.env`:

```bash
cp .env.example .env
```

### 4. Konfigurasi File `.env`

Sesuaikan konfigurasi berikut di file `.env`:

* `APP_NAME`
* `APP_URL`
* `DB_DATABASE`
* `DB_USERNAME`
* `DB_PASSWORD`

### 5. Generate Application Key

```bash
php artisan key:generate
```

### 6. Jalankan Migrasi Database

```bash
php artisan migrate
```

### 7. Jalankan Server Lokal

```bash
php artisan serve
npm run dev
```

Aplikasi dapat diakses melalui:

```
http://127.0.0.1:8000
```

---
