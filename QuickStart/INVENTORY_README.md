# 📦 Sistem Manajemen Inventaris Kantin Kenanga

Sistem manajemen inventaris modern untuk mengelola stok barang di kantin kenanga dengan interface yang user-friendly menggunakan Laravel 12, Livewire 3, dan Tailwind CSS.

## 🎯 Fitur Utama

### Dashboard Inventaris
- **Statistik Real-time**: Total produk, stok rendah, dan nilai total inventaris
- **Pencarian & Filter**: Cari produk berdasarkan nama atau SKU, filter berdasarkan kategori
- **Sorting**: Urutkan produk berdasarkan nama atau stok
- **Paginasi**: Tampilan produk dengan pagination untuk performa optimal

### Manajemen Produk
- **Tambah Produk**: Form lengkap untuk menambahkan produk baru
- **Edit Produk**: Perbarui informasi produk yang sudah ada
- **Hapus Produk**: Menghapus produk dari sistem
- **Detail Produk**: Lihat informasi lengkap dan riwayat pergerakan stok

### Pergerakan Stok
- **Input Stok**: Catat pergerakan stok masuk/keluar/penyesuaian/pengembalian
- **Riwayat Lengkap**: Lihat semua transaksi dengan detail user, tanggal, dan catatan
- **Referensi**: Catat referensi invoice/PO untuk setiap pergerakan

### Monitor Stok
- **Status Stok**: Identifikasi stok rendah, normal, atau berlebih dengan warna indikator
- **Alert Otomatis**: Sistem akan menunjukkan produk dengan stok di bawah minimum
- **Tracking Nilai**: Monitor total nilai inventaris secara real-time

## 📋 Struktur Database

### Tabel Utama

#### `categories`
Menyimpan kategori produk (Minuman, Makanan Ringan, dll)
- `id`: Primary Key
- `name`: Nama kategori (unique)
- `description`: Deskripsi kategori
- `timestamps`: Created/Updated at

#### `products`
Menyimpan informasi produk
- `id`: Primary Key
- `category_id`: Foreign Key ke categories
- `name`: Nama produk
- `sku`: Stock Keeping Unit (unique)
- `description`: Deskripsi produk
- `price`: Harga satuan
- `quantity`: Stok saat ini
- `min_quantity`: Stok minimum
- `max_quantity`: Stok maksimum
- `unit`: Satuan (pcs, botol, kg, dll)
- `supplier`: Nama supplier
- `is_active`: Status produk aktif/tidak aktif
- `timestamps`: Created/Updated at

#### `stock_movements`
Menyimpan riwayat pergerakan stok
- `id`: Primary Key
- `product_id`: Foreign Key ke products
- `user_id`: Foreign Key ke users (yang mencatat)
- `type`: Enum (in, out, adjustment, return)
- `quantity`: Jumlah pergerakan
- `reference`: Referensi (Invoice/PO)
- `notes`: Catatan tambahan
- `timestamps`: Created/Updated at

#### `stock_alerts`
Menyimpan alert stok
- `id`: Primary Key
- `product_id`: Foreign Key ke products
- `alert_type`: Enum (low_stock, overstocked, expiring)
- `is_resolved`: Status alert
- `resolved_at`: Waktu alert diselesaikan
- `timestamps`: Created/Updated at

## 🚀 Instalasi & Setup

### Prerequisites
- PHP 8.2+
- Node.js & npm
- Composer
- SQLite (atau database lainnya)

### Langkah Instalasi

1. **Clone Repository**
```bash
git clone <repository-url>
cd improved-barnacle
```

2. **Install Dependencies**
```bash
composer install
npm install
```

3. **Setup Environment**
```bash
cp .env.example .env
php artisan key:generate
```

4. **Database Setup**
```bash
php artisan migrate
php artisan db:seed
```

5. **Build Assets**
```bash
npm run build
```

6. **Jalankan Server**
```bash
php artisan serve
npm run dev  # Di terminal lain
```

### Default Credentials
- Email: `admin@kantin.com`
- Password: `password`

## 📁 Struktur Folder

```
app/
├── Livewire/
│   ├── InventoryDashboard.php      # Dashboard utama inventaris
│   ├── ProductForm.php              # Form tambah/edit produk
│   ├── StockMovementForm.php        # Form pergerakan stok
│   └── ProductDetail.php            # Detail produk & riwayat
├── Models/
│   ├── Category.php
│   ├── Product.php
│   ├── StockMovement.php
│   └── StockAlert.php

resources/views/
├── livewire/
│   ├── inventory-dashboard.blade.php
│   ├── product-form.blade.php
│   ├── stock-movement-form.blade.php
│   └── product-detail.blade.php
├── inventory-dashboard.blade.php
└── product-detail.blade.php

database/
├── migrations/
│   └── 2025_11_13_000001_create_inventory_tables.php
└── seeders/
    ├── DatabaseSeeder.php
    └── InventorySeeder.php
```

## 🎨 User Interface

### Dashboard Inventaris
Menampilkan:
- Statistik ringkas (total produk, stok rendah, nilai inventaris)
- Tabel produk dengan fitur pencarian, filter, dan sorting
- Tombol aksi untuk detail, edit, dan hapus

### Form Produk
- Input lengkap: nama, SKU, kategori, harga, stok, unit, dll
- Validasi form real-time
- Support untuk edit dan tambah baru

### Detail Produk
- Informasi produk lengkap
- Form input pergerakan stok
- Riwayat stok dengan pagination
- Indikator status dan nilai inventaris

## 🔧 Teknologi yang Digunakan

- **Laravel 12**: Framework PHP modern
- **Livewire 3**: Component-based reactive UI
- **Tailwind CSS 4**: Utility-first CSS framework
- **Flux UI**: Component library for Livewire
- **SQLite**: Database
- **Vite**: Build tool

## 📊 Sample Data

Sistem dilengkapi dengan data sampel:
- **5 Kategori**: Minuman, Makanan Ringan, Makanan Berat, Peralatan, Supplies
- **8 Produk Sample**: Air Mineral, Teh Botol, Keripik, Coklat, Nasi Kuning, Gelas, Serviette, Sedotan

## 🔒 Security Features

- Authentication dengan Fortify
- Email verification
- Two-factor authentication support
- CSRF protection
- Password hashing

## 📈 Fitur Lanjutan yang Bisa Ditambahkan

- [ ] Export laporan ke Excel/PDF
- [ ] Barcode/QR code scanning
- [ ] Multi-warehouse support
- [ ] Notification sistem
- [ ] API REST untuk mobile app
- [ ] Dashboard analitik dengan chart
- [ ] Forecast prediksi stok
- [ ] Purchase order management
- [ ] Supplier management
- [ ] Adjustment approval workflow

## 📝 License

MIT License

## 👥 Support

Untuk bantuan atau pertanyaan, silakan hubungi tim development.

---

**Last Updated**: November 13, 2025
**Version**: 1.0.0
