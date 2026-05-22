<div align="center">
  
  # 🚀 NextGen CBT (Computer Based Test)
  
  **Sistem Ujian Berbasis Komputer Modern dengan Fitur Anti-Curang & Auto-Grading Cerdas.**

  <p align="center">
    <img src="https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white" alt="Laravel" />
    <img src="https://img.shields.io/badge/Vue.js-35495E?style=for-the-badge&logo=vuedotjs&logoColor=4FC08D" alt="Vue.js" />
    <img src="https://img.shields.io/badge/Inertia.js-9553E9?style=for-the-badge&logo=inertia&logoColor=white" alt="Inertia.js" />
    <img src="https://img.shields.io/badge/Tailwind_CSS-38B2AC?style=for-the-badge&logo=tailwind-css&logoColor=white" alt="Tailwind CSS" />
    <img src="https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white" alt="MySQL" />
  </p>

</div>

---

## 📖 Tentang Aplikasi
NextGen CBT adalah aplikasi ujian digital yang dirancang untuk memberikan pengalaman evaluasi akademik yang aman, jujur, dan efisien. Dibangun menggunakan arsitektur modern (Monolith SPA), aplikasi ini menjembatani kebutuhan Guru dalam manajemen soal yang dinamis dan kebutuhan Murid akan platform ujian yang responsif dan ketat.

## ✨ Fitur Unggulan

- 🎭 **Role-Based Access Control (RBAC):** Pemisahan *dashboard* dan fungsionalitas yang ketat antara `Guru` dan `Murid`.
- 🧠 **Hybrid Auto-Grading System:**
  - **Pilihan Ganda:** Dikoreksi instan oleh sistem.
  - **Isian Singkat:** *Auto-grading* cerdas berbasis kompilasi *Keyword* (Mentolerir variasi jawaban siswa).
  - **Essay:** Antarmuka khusus bagi Guru untuk membaca uraian siswa dan memberikan skor manual secara langsung.
- ⏱️ **Strict Per-Question Timer:** Sistem memaksakan *Auto-Next* (1 Menit untuk PG, 2 Menit Isian, 3 Menit Essay). Siswa tidak dapat kembali ke soal sebelumnya.
- 🛡️ **Sistem Anti-Curang (Browser Visibility):** Mendeteksi jika siswa mengecilkan jendela (*minimize*) atau membuka *tab* baru (Google). Jika pelanggaran melebihi 3 kali peringatan, ujian akan dihentikan paksa (Force Submit).
- 📅 **Exam Scheduling:** Guru dapat mengatur status Publikasi (Draft/Published) beserta rentang waktu ujian (Start Time - End Time).
- 📊 **One-Click Export:** Ekspor rekapitulasi nilai seluruh siswa ke format `.csv` (Excel) secara *real-time*.

---

## 🛠️ Teknologi yang Digunakan

Aplikasi ini dibangun di atas tumpukan teknologi modern untuk memastikan performa SPA (*Single Page Application*) yang mulus tanpa mengorbankan ketangguhan *backend*.

- **Backend:** Laravel (PHP)
- **Frontend:** Vue 3 (Composition API)
- **Routing & Data Bridge:** Inertia.js
- **Styling UI:** Tailwind CSS
- **Database:** MySQL

---

## 🔄 Alur Aplikasi (User Flow)

### 👨‍🏫 Alur Guru (Pengajar)
1. **Manajemen Ujian:** Guru membuat sesi ujian baru, mengatur estimasi waktu, dan jadwal rilis.
2. **Bank Soal:** Guru meracik soal (PG, Isian dengan *keyword*, atau Essay) serta menentukan bobot poin per soal.
3. **Monitoring:** Guru memantau ujian yang sedang/telah dikerjakan siswa.
4. **Grading & Export:** Guru masuk ke panel "Rekap Nilai", mengoreksi soal Essay (jika ada), lalu mengunduh rekap nilai akhir ke dalam format Excel.

### 👨‍🎓 Alur Murid (Peserta)
1. **Dashboard:** Murid melihat daftar ujian yang sedang berstatus *Published* dan berada pada rentang waktu yang diizinkan.
2. **Lobi Ujian:** Murid masuk ke "Ruang Tunggu" untuk membaca peraturan dan durasi sebelum *timer* benar-benar berjalan.
3. **Pengerjaan:** Murid mengerjakan soal satu per satu (tidak bisa mundur). Sistem Anti-Curang aktif memantau aktivitas layar murid.
4. **Selesai:** Nilai PG dan Isian langsung terakumulasi, sementara skor Essay menunggu koreksi Guru. 

---

## 🗄️ Entity Relationship Diagram (ERD)

Struktur relasi *database* dirancang untuk memfasilitasi integritas data ujian, soal, hingga rekam jejak jawaban siswa.

```mermaid
erDiagram
    USERS ||--o{ EXAM_ATTEMPTS : "melakukan"
    EXAMS ||--o{ EXAM_ATTEMPTS : "memiliki_riwayat"
    EXAMS ||--o{ QUESTIONS : "terdiri_dari"
    QUESTIONS ||--o{ ANSWERS : "memiliki_kunci/opsi"
    QUESTIONS ||--o{ STUDENT_RESPONSES : "dijawab_pada"
    EXAM_ATTEMPTS ||--o{ STUDENT_RESPONSES : "berisi"

    USERS {
        id bigint PK
        name varchar
        email varchar
        password varchar
        role enum "guru, murid"
    }

    EXAMS {
        id bigint PK
        title varchar
        description text
        duration_minutes int
        is_published boolean
        start_time datetime
        end_time datetime
    }

    QUESTIONS {
        id bigint PK
        exam_id bigint FK
        type enum "pilihan_ganda, isian, essay"
        question_text text
        points int
    }

    ANSWERS {
        id bigint PK
        question_id bigint FK
        answer_text text
        is_correct boolean
    }

    EXAM_ATTEMPTS {
        id bigint PK
        exam_id bigint FK
        user_id bigint FK
        total_score int
        status enum "menunggu_koreksi, selesai"
    }

    STUDENT_RESPONSES {
        id bigint PK
        exam_attempt_id bigint FK
        question_id bigint FK
        answer_id bigint FK "nullable"
        answer_text text "nullable"
        score int
    }
