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
- Menambah/mengubah/menghapus flyer promo
- Memblacklist/unblacklist user customer (v)
- Melihat rating masing-masing menu

# Cashier/Kasir
- Melihat harga dan total harga pesanan (v)
- Konfirmasi pembayaran (v)
- Membuat bukti pembayaran (v)
- Menerima pesanan (v)
- Konfirmasi terima pesanan (v)
- Mengubah status pesanan "siap saji" (v)
- Melihat/mengubah status pembayaran(v)
- Konfirmasi reservasi

# Customer/Pembeli
- Melihat jumlah menu dan jumlah menu dari masing-masing kategori (v)
- Melihat flyer promo restoran
- Melihat menu, harga dan rating
- Memilih menu (v)
- Melihat total harga pesanan (v)
- Menambahkan barang ke keranjang (v)
- Membuat/mengedit/hapus pesanan (v)
- Membuat/update/hapus Reservasi
- Melihat rating masing-masing menu
- Memberikan rating kepada menu

# Category diatas menu management
# Cek status ketersediaan meja di tabel nomor meja
# memindah data dari carts ke order item
# menyelesaikan fitur reservasi
# menyelesaikan harga di halaman create reservasi
1. data dari tabel pre order item dioper ke tabel orders, lalu menu diolah di tabel order.
2. lalu user bisa membayar pesanan dari pre order di tabel order, namun biaya nya yaitu biaya total dikurangi biaya dp.
3. sehingga user hanya perlu melakukan pembayaran sisa dari biaya total, jika user melakukan pembayaran secara penuh maka urutan nomor 2 tidak berlaku
4. untuk user yang melakukan reservasi, status otomatis di pending dan payment tetap bisa dilaksanakan apapun statusnya (kecuali cancel) karena reservasi harus melakukan pembayaran terlebih dahulu
5. status reservasi akan otomatis complete jika status dari order sudah complete
ini khusus untuk user yang melakukan pemesanan lewat reservasi
