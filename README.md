
## **Installation & Setup**

Ikuti langkah-langkah berikut **secara berurutan** agar aplikasi dapat dijalankan dengan benar.

### **1. Clone Repository**

```bash
git clone https://github.com/ahmad-imtiyaz/supplier-selection-ahp.git
```

### **2. Masuk ke Direktori Project**

```bash
cd supplier-selection-ahp
```

### **3. Install Dependency PHP (Laravel)**

```bash
composer install
```

### **4. Install Dependency Node.js**

Project ini menggunakan **Node.js** untuk kebutuhan frontend build (Vite / asset bundling), sehingga **wajib menjalankan perintah berikut**:

```bash
npm install
```

> Pastikan **Node.js dan NPM** sudah terinstal di komputer Anda.

### **5. Salin File Environment**

```bash
cp .env.example .env
```

Kemudian sesuaikan konfigurasi database pada file `.env`:

```env
DB_DATABASE=nama_database
DB_USERNAME=root
DB_PASSWORD=
```

### **6. Generate Application Key**

```bash
php artisan key:generate
```

### **7. Migrasi Database**

```bash
php artisan migrate
```

### **8. WAJIB: Jalankan Database Seeder**

```bash
php artisan db:seed
```

Seeder digunakan untuk mengisi **data awal sistem**, meliputi:

* Akun administrator
* Data kriteria evaluasi
* Data supplier

> Tanpa menjalankan seeder, aplikasi **tidak dapat digunakan dengan benar** karena data awal belum tersedia.

### **9. Jalankan Aplikasi**

Untuk menjalankan aplikasi, **tidak menggunakan `php artisan serve`**, melainkan:

```bash
npm run start
```

Aplikasi dapat diakses melalui browser pada alamat:

```
http://localhost:8000
```

---

##  **Default Admin Account**

Setelah menjalankan perintah **`php artisan db:seed`**, sistem akan otomatis membuat akun administrator dengan kredensial berikut:

```
Email    : admin@gmail.com
Password : password
Role     : Admin
Status   : Active
```

Akun ini digunakan untuk:

* Login ke sistem
* Mengelola data kriteria
* Mengelola data supplier
* Melakukan proses perhitungan dan analisis AHP

>  **Catatan Keamanan:**
> Akun dan password ini disediakan untuk keperluan demo dan pengujian.
> Disarankan untuk mengganti password setelah login pertama kali.

---

##  **Seeder Information**

Seeder yang dijalankan dalam project ini:

* **AdminUserSeeder** → Membuat akun admin default
* **CriteriaSeeder** → Mengisi data kriteria evaluasi
* **SupplierSeeder** → Mengisi data supplier

Semua seeder menggunakan metode `updateOrCreate`, sehingga **aman dijalankan berulang kali** tanpa menyebabkan duplikasi data.

---

##  **Catatan untuk Dosen / Penguji**

Project ini dibuat sebagai tugas mata kuliah **Sistem Pendukung Keputusan (SPK) & Sistem Enterprise (SE)** dengan studi kasus **pemilihan supplier menggunakan metode Analytic Hierarchy Process (AHP)**.

Untuk keperluan pengujian dan penilaian:

1. Jalankan **migration**
2. Jalankan **database seeder**
3. Login menggunakan **akun admin default**
4. Sistem siap digunakan tanpa konfigurasi tambahan

---
