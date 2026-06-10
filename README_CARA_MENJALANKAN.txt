PANDUAN MENJALANKAN SISTEM PENDAFTARAN PASIEN RS ONLINE
=========================================================

1. Pastikan XAMPP 7.3 sudah menyala:
   - Apache: Start
   - MySQL: Start

2. Extract ZIP ini.

3. Ubah nama folder hasil extract menjadi:
   rs_pendaftaran

4. Pindahkan folder rs_pendaftaran ke:
   C:\xampp\htdocs\rs_pendaftaran

5. Buka phpMyAdmin:
   http://localhost/phpmyadmin

6. Import file database.sql yang ada di dalam folder project.
   File tersebut otomatis membuat database:
   rs_pendaftaran

7. Buka sistem melalui browser:
   http://localhost/rs_pendaftaran/index.php/auth/login

8. Login admin:
   Username: admin
   Password: admin123

9. Untuk pasien:
   - Klik Daftar Pasien
   - Buat akun pasien
   - Login sebagai pasien
   - Isi formulir pendaftaran
   - Admin dapat menyetujui/menolak pendaftaran dari dashboard admin

CATATAN PENTING
===============
- File ini dibuat agar bisa langsung dijalankan di XAMPP/PHP 7.3.
- Struktur folder menggunakan pola MVC CodeIgniter 3: controllers, models, views, config.
- Koneksi database default:
  hostname: localhost
  username: root
  password: kosong
  database: rs_pendaftaran

Jika folder project tidak bernama rs_pendaftaran, ubah base_url di:
application/config/config.php
