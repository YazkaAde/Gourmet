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
- Melihat rating yang diberikan oleh user
- Membalas rating yang diberikan oleh user
- Menambah/mengubah/menghapus daftar menu dan harga (v)
- Menambah/mengubah/menghapus daftar category (v)
- Menambah/mengubah/menghapus meja restoran (v)
- Menambah/mengubah/menghapus user cashier (v)
- Menambah/mengubah/menghapus flyer promo
- Memblacklist/unblacklist user customer
- Melihat rating masing-masing menu

# Cashier/Kasir
- Melihat harga dan total harga pesanan (v)
- Konfirmasi pembayaran
- Membuat bukti pembayaran
- Menerima pesanan (v)
- Konfirmasi terima pesanan (v)
- Mengubah status pesanan "siap saji" (v)
- Melihat/mengubah status pembayaran
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
# Status order ...->diproses->siap saji
# Cek status ketersediaan meja di tabel nomor meja

sekarang, bisakah kamu membuat halaman baru untuk fitur pembayaran, jadi:
1. status data orders yang sudah berubah menjadi completed otomatis diambil ke tabel payments.
2. setiap user (customer dan cashier) memiliki kuasa yang berbeda
3. customer bisa memilih metode pembayaran dan cashier mengkonfirmasi pembayaran dari customer
4. tampilan customer: di halaman order akan muncul tombol bayar,user memasuki halaman web baru, di halaman web itu user bisa memilih metode pembayaran dan menentukan jumlah uang yang akan dibayarkan, jika jumlah uang yang dibayarkan kurang user tidak bisa melakukan pembayaran(tidak berlaku jika user membayar menggunakan cash)
5. tampilan cashier: akan ada halaman baru dengan nama payment, nanti user melakukan konfirmasi pembayaran(apakah benar customer sudah membayar? jika sudah maka user cashier akan melakukan konfirmasi), jika sudah cashier akan mencetak bukti pembayaran yang berisi menu yang dipesan, uang yang diberikan dan juga kembalian
6. metode pembayaran terdiri dari beberapa opsi: cash, credit, qris, dll.

file yang dibutuhkan
1. tabel yang berkaitan
2. controller 
3. rute
4. file blade
