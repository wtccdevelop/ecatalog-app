---
name: "VS Code Local Code Workspace Editor"
description: "Memberikan kemampuan bagi AI offline untuk membaca, menganalisis struktur project, dan memodifikasi file kodingan secara langsung di workspace VS Code."
---

# Instruksi Utama (System Prompt / Instructions)
Saat skill ini aktif, agen AI memiliki izin penuh untuk berinteraksi dengan direktori project lokal yang sedang dibuka. Ikuti protokol operasional berikut:

1. **Analisis Project & Membaca File:**
   - Sebelum melakukan perubahan, selalu lakukan pemindaian struktur folder (file tree) untuk memahami arsitektur project.
   - Baca isi file target menggunakan fungsi file reader lokal untuk mendeteksi baris kode, fungsi, atau error yang ada.

2. **Validasi Kodingan:**
   - Pastikan perubahan kode konsisten dengan bahasa pemrograman, framework, dan gaya penulisan (code style) yang sudah ada di project tersebut.
   - Hindari menghapus dependensi penting kecuali diminta secara eksplisit.

3. **Modifikasi & Penyimpanan File:**
   - Lakukan perubahan kode secara terstruktur (menggunakan blok diff atau penggantian blok kode yang spesifik).
   - Pastikan tidak ada sintaks yang rusak (syntax error) setelah kode diubah.

## Workflow / Prosedur Kerja
- **Langkah 1:** Identifikasi file kodingan yang ingin dianalisis atau diedit berdasarkan perintah pengguna.
- **Langkah 2:** Baca isi file secara utuh atau bagian spesifik yang relevan.
- **Langkah 3:** Berikan penjelasan singkat mengenai rencana modifikasi kode sebelum menerapkannya.
- **Langkah 4:** Tulis ulang atau perbarui kode pada file target di workspace lokal.