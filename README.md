# Website Resmi SMAN 6 Tangerang

Website Resmi **SMA Negeri 6 Kota Tangerang** dibangun menggunakan framework **Laravel** sebagai backend, **Bootstrap** sebagai frontend framework, serta **Berry Admin Template** yang telah dimodifikasi dengan **dark theme custom** untuk sistem CMS (Content Management System).

Website ini berfungsi sebagai media informasi sekolah sekaligus sistem pengelolaan konten internal yang terstruktur dan mudah digunakan.

---

## 🚀 Teknologi yang Digunakan

-   **Backend**: Laravel
-   **Frontend**: Bootstrap
-   **Admin Template**: Berry Template (Customized)
-   **Theme**: Custom Dark Theme
-   **Database**: MySQL
-   **Authentication**: Custom Laravel Sanctum Auth
-   **ORM**: Eloquent

---

## 📂 Struktur Fitur

### 1. CMS (Admin Panel)

CMS digunakan untuk mengelola seluruh konten website secara terpusat.

#### 🔹 Master Data

-   Users
-   School Histories (Sejarah Sekolah)
-   Visions (Visi)
-   Missions (Misi)
-   Teachers (Guru & Staff)
-   News (Berita)
-   PPDB
-   Events (Agenda / Kegiatan)

#### 🔹 Settings

-   Footer Settings
-   Navbar Settings
-   Activity Logs (Log Aktivitas Admin)

---

### 2. Public Pages

Halaman yang dapat diakses oleh pengunjung umum tanpa login.

-   Home
-   Profil Sekolah
-   Sejarah Sekolah
-   Visi & Misi
-   Guru & Staff
-   Berita
-   Detail Berita
-   Agenda / Events
-   PPDB
    -   Formulir PPDB
    -   Panduan PPDB
    -   Informasi Pendaftaran
-   Kontak Sekolah

---

## 🎨 Custom Dark Theme

Admin panel menggunakan **Berry Template** yang telah dimodifikasi:

-   Warna gelap (dark mode) buatan sendiri
-   Konsisten dengan branding sekolah
-   Nyaman digunakan dalam jangka waktu lama
-   Optimal untuk manajemen konten CMS

---

## ⚙️ Instalasi

1. **Clone Repository**

    ```bash
    git clone https://github.com/username/sman6-tangerang.git
    cd sman6-tangerang
    ```

2. **Install Dependency**

    ```bash
    composer install
    npm install && npm run build
    ```

3. **Copy Environment**

    ```bash
    cp .env.example .env
    ```

4. **Generate Key**

    ```bash
    php artisan key:generate
    ```

5. **Konfigurasi Database dan Admin User**  
   Atur koneksi database di file `.env`

6. **Migrasi Database**

    ```bash
    php artisan migrate --seed
    ```

7. **Jalankan Server**

    ```bash
    php artisan serve
    ```

---

## 🔐 Hak Akses Pengguna

-   **Admin**

    -   Mengelola seluruh data CMS
    -   Mengatur konten website
    -   Mengakses activity logs

-   **Public User**

    -   Mengakses halaman informasi
    -   Melihat berita, event, dan PPDB

---

## 📌 Catatan Pengembangan

-   Menggunakan struktur MVC Laravel
-   Relasi database menggunakan Eloquent
-   Validasi data menggunakan Form Request
-   Optimasi query untuk halaman publik
-   Template admin sudah di-custom dan tidak menggunakan theme default Berry

---

## 📄 Lisensi

Proyek ini dikembangkan untuk kebutuhan **SMA Negeri 6 Kota Tangerang**.
Penggunaan ulang dan distribusi harus mendapatkan izin dari pihak sekolah.

---

## 🤝 Kontribusi

Kontribusi internal diperbolehkan melalui:

-   Perbaikan bug
-   Penambahan fitur
-   Optimalisasi performa

Silakan buat branch baru sebelum melakukan perubahan.

---

## 📞 Kontak

**SMA Negeri 6 Kota Tangerang**
Website Resmi: _(sesuaikan)_
Email: _(sesuaikan)_

---
