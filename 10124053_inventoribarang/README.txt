Aplikasi Inventaris Barang merupakan aplikasi berbasis web yang digunakan untuk mengelola data barang. Aplikasi ini menggunakan PHP dan database MySQL/MariaDB.

A. Kebutuhan
   - XAMPP atau lingkungan server lokal yang mendukung Apache, PHP, dan MySQL/MariaDB.
   - Browser seperti Google Chrome, Mozilla Firefox, Safari, atau Microsoft Edge.
   - File proyek 10124053_inventoribarang.
   - File database db_inventori.sql.

B. Menyalin Proyek
   Ekstrak folder 10124053_inventoribarang, kemudian letakkan di direktori web server sesuai sistem operasi yang digunakan.
   1. Windows
      - Instal dan buka XAMPP.
      - Letakkan folder proyek di:
         C:\xampp\htdocs\10124053_inventoribarang\
      - Buka XAMPP Control Panel.
      - Jalankan Apache dan MySQL.

   2. macOS
      - Instal dan buka XAMPP.
      - Letakkan folder proyek di:
         /Applications/XAMPP/htdocs/10124053_inventoribarang/
      - Jalankan Apache dan MySQL dari XAMPP.

C. Menyiapkan Database
   1. Jalankan MySQL/MariaDB.
   2. Buka phpMyAdmin melalui alamat berikut jika tersedia:
      http://localhost/phpmyadmin
   3. Klik tab Databases dan buat database bernama db_inventori jika belum ada.
   4. Pilih database db_inventori pada panel kiri.
   5. Klik tab Import, pilih file db_inventori.sql, pastikan formatnya SQL, kemudian klik Import atau Go.
   6. Setelah proses import selesai, buka tabel users dan pastikan username admin tersedia.

Catatan database:
Akun admin akan disetel ke password demo yang tercantum pada bagian E.

D. Konfigurasi Koneksi Database
   1. Buka file config/database.php dan periksa konfigurasi koneksi database.
   2. Konfigurasi default yang umum digunakan pada instalasi XAMPP:
      Host: localhost
      Database: db_inventori
      Username: root
      Password: kosong
   3. Jika akun MySQL/MariaDB pada komputer menggunakan password, sesuaikan variabel $password dengan password database milik pengguna.

E. Login Demo
   Setelah Apache dan MySQL berjalan serta database berhasil disiapkan, buka aplikasi melalui browser:
      http://localhost/10124053_inventoribarang/
   Akun demo:
      Username: admin
      Password: admin123
   Jika nama folder atau konfigurasi server berbeda, sesuaikan alamat URL dengan pengaturan yang digunakan.
   Peringatan: Ganti password demo sebelum aplikasi digunakan di luar lingkungan tugas atau lokal.

F. Pengujian Aplikasi
   Setelah aplikasi berhasil dijalankan, lakukan pengujian berikut:
      1. Login menggunakan akun demo.
      2. Tambahkan barang percobaan.
      3. Edit nama atau stok barang.
      4. Hapus barang percobaan.
      5. Coba kirim formulir dengan kolom wajib kosong dan stok negatif.
      6. Logout, kemudian coba buka dashboard.php secara langsung. Pengguna seharusnya diarahkan kembali ke halaman login.
      7. Ubah bahasa Indonesia/Inggris dan pastikan teks pada dashboard serta formulir ikut berubah.