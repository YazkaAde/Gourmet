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
# Status order ...->diproses->siap saji
# Cek status ketersediaan meja di tabel nomor meja

saya ingin membuat fitur rating menggunakan file Review, file ini digunakan untuk memberikan rating kepada menu yang telah di order. perhatikan ketentuan berikut:
Customer:
1. user customer dapat memberikan review kepada menu yang sudah dipesan, user customer lain juga dapat melihat rata rata dari rating yang diberikan di halaman menu dan cart
2. akan ada tombol untuk meriview di bagian orders, ketika ditekan akan berisi sebuah card
3. card review berisi username yang melakukan rewiew, form berisi 5 bintang yang bisa dipilih mau diberi berapa bintang, text yang bisa diisi komentar oleh user customer.
4. customer bisa menghapus review namun tidak bisa mengedit
Admin:
1. user admin bisa melihat card review yang berisi username, tanggal review, bintang yang diberikan, dan juga komentar customer
2. admin hanya bisa membalas komentar customer dan tidak bisa menghapusnya.
3. jika akun user di blacklist, review masih tersimpan dan tidak hilang
4. Admin bisa melihat rata-rata rating, jumlah rating dan berapa yang memberikan rating 5, berapa yang memberikan rating 4 dan seterusnya. 
Admin dapat melihat laporan singkat tentang review di dashboard dan dapat melihat detailnya jika user admin menekan tombol detail

file yang dibutuhkan:
menu.blade, cart.blade, order.blade, 
dashboard.blade, 