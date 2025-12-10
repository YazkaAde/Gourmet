<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About This Project
Sebuah aplikasi yang dapat digunakan untuk pemesanan makanan dan minuman pada sebuah restoran, cafe, rumah makan atau sejenisnya. aplikasi ini terdiri dari 3 role dan berbagai kemampuan yang dimiliki oleh masing-masing role

# Admin/Pemilik
- Melihat laporan/history penjualan
- Melihat rating yang diberikan oleh user (v)
- Membalas rating yang diberikan oleh user
- Menambah/mengubah/menghapus daftar menu dan harga (v)
- Menambah/mengubah/menghapus daftar category (v)
- Menambah/mengubah/menghapus meja restoran (v)
- Menambah/mengubah/menghapus user cashier (v)
- Memblacklist/unblacklist user customer (v)
- Melihat rating masing-masing menu (v)

# Cashier/Kasir
- Melihat harga dan total harga pesanan (v)
- Konfirmasi pembayaran (v)
- Membuat bukti pembayaran (v)
- Menerima pesanan (v)
- Konfirmasi terima pesanan (v)
- Mengubah status pesanan "siap saji" (v)
- Melihat/mengubah status pembayaran(v)
- Konfirmasi reservasi (v)

# Customer/Pembeli
- Melihat jumlah menu dan jumlah menu dari masing-masing kategori (v)
- Melihat menu, harga dan rating (v)
- Memilih menu (v)
- Melihat total harga pesanan (v)
- Menambahkan barang ke keranjang (v)
- Membuat/mengedit/hapus pesanan (v)
- Membuat/update/hapus Reservasi (v)
- Melihat rating masing-masing menu (v)
- Memberikan rating kepada menu (v)

# Category diatas menu management
# menyelesaikan fitur reservasi
# menyelesaikan harga di halaman create reservasi

# metode pembayaran
- jika user memilih cash maka untuk inputan nominal tidak muncul
- jika user memilih Transfer bank maka akan muncul inputan bank yang dipilih dan juga nomer rekening
- jika user memilih Debit Card maka akan muncul inputan debit yang dipilih dan juga nomer rekening
- jika user memilih Qris maka akan muncul inputan Qris yang dipilih dan juga nomer qris

# Cara Penggunaan file
- buka terminal, lalu ketikan: npm run dev