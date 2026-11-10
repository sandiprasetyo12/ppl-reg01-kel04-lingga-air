# Kontribusi Anggota PPL - Lingga Air

*Nama*: Farah Rizki Permatasari  
*NIM*: 102092400016  
*Username GitHub*: farahrizkipermatasari  
*Fitur Sprint 1*: F-03 (Pengajuan Bantuan Air Bersih)  
*Daftar PBI Sprint 1*: PBI-007, PBI-008, PBI-009  

---

### Status Sinkronisasi & Mode Tanpa Login
- **Sinkronisasi dengan `main`**: Branch telah digabungkan (*merged*) dengan `main` (`commit 84a7b19` oleh Sandi), mengadopsi tabel terpusat `desas` (`kode_desa` sebagai *primary key*), `DesaSeeder`, dan data master 239 desa di Kabupaten Purbalingga.
- **Akses Pengajuan Tanpa Login**: Rute `/pengajuan` dapat diakses langsung tanpa harus melewati autentikasi login (`/login`), sehingga pengujian dan demo fitur dapat berjalan lancar.
- **Skenario Simulasi Status Cek Kelayakan (F-02)**: Tersedia tombol *switch* skenario di header portal:
  - 🟡 **WASPADA (Desa Serang - Skor: 74.8)**: Mengisi pengajuan bantuan air dengan parameter kebutuhan terkunci dari hasil Cek Kelayakan.
  - 🟢 **AMAN (Desa Bodas - Skor: 48.1)**: Pengujian proteksi SOP BPBD, di mana tombol "Buat Pengajuan Baru" langsung diblokir dan memunculkan pop-up modal penolakan.
  - 🔴 **KRITIS (Desa Tanalum - Skor: 92.4)**: Kondisi darurat untuk simulasi prioritas tinggi.

---

### Checklist PBI & Technical Tasks (Semua Terpenuhi 100%)

#### PBI-007 | Susun draf pengajuan beserta foto pendukung
- **Aktor**: Perwakilan Desa
- **User Story**: Sebagai Perwakilan Desa, saya ingin menyusun draf pengajuan beserta foto pendukung, agar kebutuhan desa terdokumentasi.
- **Acceptance Criteria**:
  - [x] Formulir terisi otomatis dari hasil cek kelayakan dan Pemohon mengunggah surat Kepala Desa dan foto lapangan.
  - [x] Sistem hanya menerima foto JPG/PNG sesuai batas ukuran dan menolak berkas tidak sesuai.
- **Technical Tasks**:
  - [x] Membuat migration tabel pengajuan dan berkas pengajuan.
  - [x] Membuat model Pengajuan dan BerkasPengajuan beserta relasinya.
  - [x] Membuat formulir pengajuan yang terisi otomatis dari hasil cek kelayakan (frontend).
  - [x] Membuat komponen unggah surat Kepala Desa dan foto lapangan (frontend).
  - [x] Membuat controller penyimpanan draf dan berkas (backend).
  - [x] Memvalidasi foto hanya JPG/PNG dan batas ukuran (maks 5MB), serta menolak berkas tidak sesuai.
  - [x] Mengintegrasikan frontend dengan backend.
  - [x] Memeriksa hasil terhadap Acceptance Criteria.

#### PBI-008 | Lihat, ubah, dan hapus draf pengajuan
- **Aktor**: Perwakilan Desa
- **User Story**: Sebagai Perwakilan Desa, saya ingin melihat, mengubah, dan menghapus draf sebelum dikirim, agar kesalahan isian dapat diperbaiki.
- **Acceptance Criteria**:
  - [x] Draf yang sudah dikirim tidak dapat diubah dan draf desa lain tidak terlihat.
  - [x] Perangkat desa dapat melihat, mengubah, dan menghapus draf milik desanya sebelum dikirim.
- **Technical Tasks**:
  - [x] Membuat halaman daftar dan detail draf pengajuan (frontend).
  - [x] Membuat form ubah draf dan tombol hapus dengan konfirmasi (frontend).
  - [x] Membuat controller lihat, ubah, dan hapus draf (backend).
  - [x] Membatasi akses draf hanya untuk desa pemiliknya (policy).
  - [x] Mengintegrasikan frontend dengan backend.
  - [x] Memeriksa hasil terhadap Acceptance Criteria.

#### PBI-009 | Kirim pengajuan ke antrean verifikasi
- **Aktor**: Perwakilan Desa
- **User Story**: Sebagai Perwakilan Desa, saya ingin mengirim pengajuan setelah hasil cek layak atau perlu verifikasi lapangan, agar pengajuan masuk antrean Admin.
- **Acceptance Criteria**:
  - [x] Sistem menolak pengajuan baru jika desa sudah punya tiket aktif.
  - [x] Sistem menerbitkan nomor tiket unik, status Diajukan, dan menempatkan pengajuan di antrean Admin.
- **Technical Tasks**:
  - [x] Membuat jendela konfirmasi kirim dan halaman "berhasil dikirim" (frontend).
  - [x] Membuat controller pengiriman pengajuan (backend).
  - [x] Membuat mekanisme nomor tiket unik (`LA-YYYYMMDD-XXX`).
  - [x] Mengubah status menjadi Diajukan dan menempatkannya di antrean admin.
  - [x] Memvalidasi hasil cek berstatus Kritis/Waspada sebelum dapat dikirim (menolak status Aman dengan modal pop-up).
  - [x] Menolak pengajuan baru jika desa masih punya tiket aktif (anti-spam).
  - [x] Mengintegrasikan frontend dengan backend.
  - [x] Memeriksa hasil terhadap Acceptance Criteria.
