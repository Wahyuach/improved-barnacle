# 📦 Panduan Penggunaan Sistem Manajemen Inventaris Kantin Kenanga

## 🎯 Daftar Isi
1. [Login & Autentikasi](#login--autentikasi)
2. [Dashboard Utama](#dashboard-utama)
3. [Manajemen Produk](#manajemen-produk)
4. [Pergerakan Stok](#pergerakan-stok)
5. [Monitoring Stok](#monitoring-stok)
6. [Tips & Trik](#tips--trik)

---

## 🔐 Login & Autentikasi

### Cara Login
1. Buka halaman login aplikasi
2. Masukkan email: `admin@kantin.com`
3. Masukkan password: `password`
4. Klik tombol "Login"

### Fitur Keamanan
- **Email Verification**: Email harus diverifikasi saat registrasi
- **Two-Factor Authentication**: Opsional untuk keamanan tambahan
- **Password Recovery**: Bisa reset password melalui email

---

## 📊 Dashboard Utama

### Halaman Awal
Setelah login, Anda akan melihat dashboard dengan:
- **Total Produk**: Menampilkan jumlah produk aktif di sistem
- **Stok Rendah**: Jumlah produk dengan stok di bawah minimum
- **Total Nilai Inventaris**: Total nilai barang dalam rupiah

### Navigasi
```
Dashboard
├── Dashboard Utama
├── Inventory
│   ├── Dashboard Inventaris
│   └── Detail Produk
├── Settings
└── Logout
```

---

## 📦 Manajemen Produk

### Membuka Inventaris Dashboard
1. Klik menu "Inventory" atau "Dashboard Inventaris"
2. Anda akan melihat tabel produk lengkap

### Fitur Pencarian & Filter

#### Pencarian Produk
- Gunakan kolom "Cari Produk" untuk mencari:
  - Nama produk (contoh: "Air Mineral")
  - SKU/Kode produk (contoh: "DRINK-001")
- Pencarian bersifat real-time

#### Filter Kategori
- Pilih kategori dari dropdown untuk memfilter:
  - Semua Kategori
  - Minuman
  - Makanan Ringan
  - Makanan Berat
  - Peralatan
  - Supplies

#### Sorting
- Klik header kolom "Nama Produk" atau "Stok" untuk mengurutkan
- Klik lagi untuk mengubah urutan (ascending/descending)
- Tanda ↑ atau ↓ menunjukkan arah sorting

### Menambah Produk Baru

1. Klik tombol "+ Tambah Produk"
2. Isi form dengan data produk:
   - **Nama Produk** (wajib): Nama lengkap produk
   - **SKU** (wajib): Kode unik produk (contoh: DRINK-001)
   - **Kategori** (wajib): Pilih kategori dari dropdown
   - **Harga** (wajib): Harga satuan dalam rupiah
   - **Stok Saat Ini** (wajib): Jumlah stok saat ini
   - **Unit** (wajib): Satuan barang (pcs, botol, kg, dll)
   - **Stok Minimum** (wajib): Batas minimum stok
   - **Stok Maksimum** (wajib): Batas maksimum stok
   - **Deskripsi** (opsional): Penjelasan produk
   - **Supplier** (opsional): Nama supplier

3. Klik "Simpan Produk"

### Mengedit Produk

1. Di tabel produk, klik tombol "Edit" pada produk yang ingin diubah
2. Form akan terbuka dengan data produk terisi
3. Ubah data yang diperlukan
4. Klik "Perbarui Produk"

**Catatan**: SKU tidak bisa diubah setelah produk dibuat

### Menghapus Produk

1. Di tabel produk, klik tombol "Hapus" pada produk
2. Produk akan ditandai tidak aktif (soft delete)
3. Produk tidak akan tampil di inventaris utama

---

## 📤 Pergerakan Stok

### Cara Input Pergerakan Stok

1. Di tabel produk, klik "Detail" pada produk untuk melihat detail lengkap
2. Di halaman detail, Anda akan melihat form "Input Stok"
3. Klik "+ Input Pergerakan Stok" untuk membuka form

### Jenis Pergerakan Stok

#### 📥 Masuk (Pembelian)
- **Gunakan untuk**: Barang baru masuk dari supplier
- **Efek**: Menambah stok
- Contoh: Beli 50 botol air mineral

#### 📤 Keluar (Penjualan)
- **Gunakan untuk**: Barang terjual ke pelanggan
- **Efek**: Mengurangi stok
- Contoh: Jual 10 teh botol

#### ⚙️ Penyesuaian
- **Gunakan untuk**: Koreksi fisik stok
- **Efek**: Mengurangi stok (jika kurang) atau menambah
- Contoh: Stok fisik 5 tapi sistem 8, penyesuaian -3

#### ↩️ Pengembalian
- **Gunakan untuk**: Barang dikembalikan dari pelanggan
- **Efek**: Menambah stok
- Contoh: Pelanggan kembalikan 2 pack serviette

### Form Input Stok

**Jenis Pergerakan** (wajib)
- Pilih salah satu dari 4 jenis di atas

**Jumlah** (wajib)
- Masukkan jumlah pergerakan
- Tidak boleh kosong atau 0

**Referensi** (opsional)
- Masukkan nomor invoice/PO/bukti
- Contoh: INV-2025-001 atau PO-11-2025

**Catatan** (opsional)
- Tambahkan catatan tambahan
- Contoh: "Barang rusak 2 botol", "Kadaluarsa"

### Contoh Penggunaan

#### Contoh 1: Barang Masuk
```
Jenis: Masuk (Pembelian)
Jumlah: 50
Referensi: INV-2025-001
Catatan: Pembelian dari supplier A
```

#### Contoh 2: Barang Rusak
```
Jenis: Keluar (Penjualan)
Jumlah: 3
Referensi: -
Catatan: Barang rusak dalam gudang
```

#### Contoh 3: Penyesuaian Stok
```
Jenis: Penyesuaian
Jumlah: 5
Referensi: STK-ADJ-001
Catatan: Koreksi stok fisik vs sistem
```

---

## 📊 Monitoring Stok

### Status Stok

Setiap produk memiliki status berdasarkan jumlah stok:

| Status | Kondisi | Warna | Aksi |
|--------|---------|-------|------|
| ✓ Normal | Min < Stok < Max | 🟢 Hijau | Normal |
| ⚠️ Rendah | Stok ≤ Min | 🔴 Merah | Segera pesan |
| 📈 Berlebih | Stok ≥ Max | 🟡 Kuning | Prioritaskan penjualan |

### Riwayat Pergerakan Stok

Di halaman detail produk, Anda dapat melihat:

| Kolom | Keterangan |
|-------|-----------|
| Tanggal | Waktu pergerakan tercatat |
| Jenis | Jenis pergerakan (icon + label) |
| Jumlah | Berapa banyak barang |
| User | Siapa yang mencatat |
| Referensi | Nomor invoice/bukti |
| Catatan | Informasi tambahan |

### Tips Monitoring

1. **Pantau Stok Rendah Rutin**
   - Lihat dashboard untuk produk dengan stok rendah
   - Lakukan pemesanan proaktif

2. **Cek Riwayat Bulanan**
   - Analisis pola penjualan
   - Perkirakan stok untuk bulan depan

3. **Verifikasi Stok Fisik**
   - Lakukan stock take berkala
   - Gunakan "Penyesuaian" jika ada selisih

---

## 💡 Tips & Trik

### Best Practices

1. **Konsisten dalam Naming**
   - Gunakan nama produk yang deskriptif
   - Contoh: Bukan "Minuman" tapi "Air Mineral 600ml"

2. **SKU yang Terstruktur**
   - Gunakan prefix kategori
   - Contoh: DRINK-001, SNACK-001, FOOD-001

3. **Catat Referensi**
   - Selalu masukkan nomor invoice/PO
   - Memudahkan audit dan tracking

4. **Catatan yang Detail**
   - Jika ada masalah, jelaskan di catatan
   - Berguna untuk follow-up

### Shortcuts & Fitur Tersembunyi

1. **Real-time Search**: Ketik di kolom cari, hasil muncul otomatis
2. **Quick Sort**: Klik header kolom untuk sort instant
3. **Pagination**: Atur berapa banyak item per halaman
4. **Dark Mode**: Settings → Appearance untuk tema gelap

### Troubleshooting

**Q: Saya tidak bisa edit SKU produk?**
- A: SKU tidak bisa diubah setelah produk dibuat untuk menjaga integritas data

**Q: Bagaimana jika stok menjadi negatif?**
- A: Sistem akan mengatur stok minimal 0, cegah dengan melakukan penyesuaian

**Q: Bisa lihat laporan bulanan?**
- A: Fitur laporan akan ditambahkan di versi berikutnya

**Q: Apakah bisa multiple user?**
- A: Ya, sistem mencatat siapa yang input stok

---

## 📞 Support & Bantuan

Jika ada pertanyaan atau masalah:
1. Hubungi tim IT/Development
2. Sertakan screenshot jika ada error
3. Jelaskan langkah yang dilakukan sebelum error muncul

---

**Semoga berhasil! Happy Inventory Managing! 📦✨**

*Last Updated: November 13, 2025*
