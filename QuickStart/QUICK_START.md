# 🚀 QUICK START GUIDE

## Panduan Cepat Memulai Sistem Inventaris Kantin Kenanga

### Prerequisites ✅
- PHP 8.2+
- Composer
- Node.js & npm
- Git

---

## 5 Langkah Setup

### 1️⃣ Clone & Install Dependencies
```bash
cd improved-barnacle
composer install
npm install
```

### 2️⃣ Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 3️⃣ Database Setup
```bash
php artisan migrate
php artisan db:seed
```

### 4️⃣ Build Frontend
```bash
npm run build
```

### 5️⃣ Jalankan Server
```bash
# Terminal 1
php artisan serve

# Terminal 2
npm run dev
```

---

## 🔐 Login Credentials

| Field | Value |
|-------|-------|
| Email | admin@kantin.com |
| Password | password |

---

## 📍 URL Akses

| Page | URL |
|------|-----|
| Beranda | http://localhost:8000/ |
| Dashboard | http://localhost:8000/dashboard |
| Inventaris | http://localhost:8000/inventory |

---

## 🎯 First Steps

1. **Login** dengan credentials di atas
2. **Buka Dashboard** → Klik "Buka Inventaris"
3. **Lihat Produk Sample** yang sudah di-seed
4. **Tambah Produk Baru** dengan klik "+ Tambah Produk"
5. **Input Pergerakan Stok** di halaman detail produk

---

## 📚 Dokumentasi Lengkap

Baca file:
- `INVENTORY_README.md` - Features & Architecture
- `PANDUAN_PENGGUNAAN.md` - User Manual
- `SUMMARY.md` - Complete Overview

---

## 💡 Perintah Util

```bash
# Clear cache
php artisan cache:clear
php artisan config:clear

# Reset database (dev only)
php artisan migrate:refresh --seed

# Create new user
php artisan tinker
> User::factory()->create(['name' => 'New User', 'email' => 'user@test.com'])

# View logs
tail -f storage/logs/laravel.log
```

---

## 🆘 Troubleshooting

| Problem | Solution |
|---------|----------|
| Assets tidak load | `npm run build` |
| Database error | `php artisan migrate --fresh` |
| Page blank | `php artisan cache:clear` |
| Port 8000 sudah pakai | `php artisan serve --port=8001` |

---

**Selamat! Sistem sudah siap digunakan! 🎉**

*For detailed guide, read PANDUAN_PENGGUNAAN.md*
