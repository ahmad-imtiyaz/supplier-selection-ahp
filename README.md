# **Supplier Selection Using AHP**

## **Project Overview**

Repository ini berisi implementasi sistem pendukung keputusan (SPK) untuk **pemilihan supplier** berbasis **Analytic Hierarchy Process (AHP)**. AHP adalah metode *Multi-Criteria Decision Making* (MCDM) yang umum digunakan untuk mengevaluasi dan membandingkan alternatif berdasarkan beberapa kriteria yang saling bertentangan. Metode ini memungkinkan pengguna untuk memberikan bobot prioritas pada tiap kriteria, menggabungkan penilaian subjektif dan objektif, lalu menghitung peringkat akhir supplier terbaik berdasarkan bobot tersebut. ([VGTU Journals][1])

Project ini dibuat sebagai tugas akhir mata kuliah **Sistem Pendukung Keputusan (SPK) & Sistem Enterprise (SE)** dan bertujuan memberikan solusi terstruktur dalam proses evaluasi supplier dengan pendekatan AHP.

## **Features**

Fitur utama yang tersedia:

* Form input data supplier dan kriteria evaluasi
* Perhitungan AHP untuk menentukan bobot kriteria
* Perbandingan berpasangan (pairwise comparison)
* Normalisasi dan perankingan supplier
* Tampilan hasil perhitungan dan hasil rekomendasi supplier

## **Tech Stack**

Project ini dibangun menggunakan komponen teknologi berikut:

* **PHP** — Bahasa utama aplikasi backend
* **Laravel Framework** — Struktur aplikasi dan routing
* **Blade Templating Engine** — Untuk rendering antarmuka
* **Bootstrap** — UI framework responsif
* **MySQL** — Database untuk penyimpanan data supplier dan kriteria
* **JavaScript** — Interaktivitas frontend

## **Installation & Setup**

1. **Clone repository:**

   ```bash
   git clone https://github.com/ahmad-imtiyaz/supplier-selection-ahp.git
   ```

2. **Masuk ke direktori project:**

   ```bash
   cd supplier-selection-ahp
   ```

3. **Install dependensi PHP:**

   ```bash
   composer install
   ```

4. **Salin file environment dan konfigurasi:**

   ```bash
   cp .env.example .env
   ```

   Sesuaikan konfigurasi database pada `.env`.

5. **Generate application key:**

   ```bash
   php artisan key:generate
   ```

6. **Migrasi database:**

   ```bash
   php artisan migrate
   ```

7. **Jalankan server development:**

   ```bash
   php artisan serve
   ```

   Akses aplikasi di `http://localhost:8000`.

> Pastikan anda sudah menginstal **PHP**, **Composer**, dan **MySQL** dengan versi sesuai kebutuhan Laravel.

## **Usage**

Setelah server berjalan, Anda dapat:

* Menambahkan data *criteria* dan *supplier* ke dalam sistem.
* Menginput penilaian untuk setiap supplier berdasarkan kriteria.
* Melakukan perbandingan berpasangan antar kriteria.
* Melihat hasil perhitungan AHP dan rekomendasi supplier terbaik.

*Contoh alur penggunaan:*

1. Buka menu kriteria → tambah kriteria evaluasi (misalnya: harga, kualitas, pengiriman).
2. Buka halaman supplier → tambah daftar supplier.
3. Lakukan *pairwise comparison* untuk menentukan bobot prioritas.
4. Sistem secara otomatis menghitung dan menampilkan pilihan supplier terbaik.

## **Folder Structure**

Struktur direktori utama project:

```
app/                  # Logika backend utama (Controllers, Models)
bootstrap/            # Bootstrap framework untuk Laravel
config/               # Konfigurasi aplikasi
database/             # Migrations dan seeders
public/               # Aset publik (CSS/JS), entry point web
resources/            # Views, Blade templates, assets
routes/               # Definisi semua route aplikasi
tests/                # Unit / Feature tests
.env.example          # Template file environment
composer.json         # Daftar dependensi PHP
```

## **Contributing**

Kontribusi diperbolehkan melalui mekanisme GitHub:

1. Fork repository ini.
2. Buat branch baru sesuai fitur/perbaikan.
3. Buat commit dengan pesan yang jelas.
4. Ajukan pull request untuk direview.

## **Author**

**Ahmad Imtiyaz Najih**
Web Developer & SPK Enthusiast
GitHub: [https://github.com/ahmad-imtiyaz](https://github.com/ahmad-imtiyaz)
Email: **imtiyaznajih8@gmail.com**

---

[1]: https://journals.vilniustech.lt/index.php/Transport/article/view/1897?utm_source=chatgpt.com "The use of AHP method for selection of supplier | Transport"
