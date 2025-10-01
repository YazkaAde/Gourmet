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
# menyelesaikan fitur reservasi
# menyelesaikan harga di halaman create reservasi

# metode pembayaran
- jika user memilih cash maka untuk inputan nominal tidak muncul
- jika user memilih Transfer bank maka akan muncul inputan bank yang dipilih dan juga nomer rekening dan nominal
- jika user memilih Debit Card maka akan muncul inputan debit yang dipilih dan juga nomer rekening dan nominal
- jika user memilih Qris maka akan muncul inputan Qris yang dipilih dan juga nomer qris dan nominal

operasional
pengembangan
marketing
manajemen

target
1. membuat tampilan untuk add-menu untuk reservasi
2. merubah tampilan payment untuk reservasi dan order dengan metode pembayaran
3. memperbaiki tampilan sidebar
4. membuat dashboard untuk cashier
5. menyelesaikan dashboard admin
6. menyelesaikan logic untuk payment reservasi
7. membuat laporan penjualan


terimakasih telah membantu saya sejauh ini. sekarang bisakah kamu membuat bagian untuk menampilkan reservation proof yang mencakup
1. username
2. tanggal dan waktu create
3. tanggal dan waktu pemesanan
4. nomor reservasi
5. informasi pembayaran = jumlah masing-masing harga, total harga dan sisa uang yang belum dibayarkan

