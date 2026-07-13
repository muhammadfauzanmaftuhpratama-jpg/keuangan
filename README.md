# 💰 Keuangan Pribadi — CodeIgniter 4 v3.0

## Changelog
- v1.x : Project awal + bugfixes
- v2.x : Rate limiting, profil, pagination, laporan lengkap
- v3.0 : CRUD Kategori Custom, Pagination Tabungan & Hutang,
         Search di semua halaman, Modal konfirmasi proper,
         Error pages 404/500/403, Semua menu dari Seeder

## Fitur Lengkap

### Admin
- Dashboard + grafik per minggu + filter bulan
- Manajemen Menu, User, Backup Database
- CRUD Pemasukan, Pengeluaran (search+pagination)
- CRUD Tabungan + Setor/Tarik (search+pagination)
- CRUD Hutang + Bayar Cicilan (search+filter+pagination)
- Laporan Bulanan + Tahunan + Export PDF/Excel/CSV
- Kategori Custom (CRUD)
- Profil + Ganti Password + Upload Avatar

### User
- Dashboard + grafik per minggu + filter bulan
- CRUD Pemasukan, Pengeluaran (search+pagination)
- CRUD Tabungan + Setor/Tarik (search+pagination)
- CRUD Hutang + Bayar Cicilan (search+filter+pagination)
- Laporan Bulanan + Tahunan + Export PDF/Excel/CSV
- Kategori Custom (CRUD)
- Profil + Ganti Password + Upload Avatar
- Notifikasi Jatuh Tempo Hutang

---

## CARA INSTALASI

### 1. Install CodeIgniter 4
  cd C:\xampp\htdocs
  composer create-project codeigniter4/appstarter project-keuangan

### 2. Copy file ZIP ke project → Replace All

### 3. Install library
  composer require dompdf/dompdf
  composer require phpoffice/phpspreadsheet

### 4. Buat database: db_keuangan di phpMyAdmin

### 5. Edit .env
  app.baseURL = 'http://localhost:8080/'
  database.default.database = db_keuangan
  database.default.username = root
  database.default.password =

### 6. Aktifkan helper di app/Config/Autoload.php
  public $helpers = ['url', 'form', 'app'];

### 7. Migration + Seeder
  php spark migrate
  php spark db:seed DatabaseSeeder

### 8. Buat folder avatar (auto dibuat, tapi pastikan ada)
  mkdir writable/uploads/avatars

### 9. Jalankan
  php spark serve → http://localhost:8080

## Akun Default
  Admin : admin@keuangan.com / admin123
  User  : user@keuangan.com  / user123

## Upgrade dari v2.x
  1. Copy file ZIP → Replace All
  2. php spark migrate --all
  3. php spark db:seed CategorySeeder
  4. php spark serve
