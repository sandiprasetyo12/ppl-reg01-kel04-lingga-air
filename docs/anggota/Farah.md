# Kontribusi Anggota PPL - Lingga Air

*Nama*: Farah Rizki Permatasari  
*NIM*: 102092400016  
*Username GitHub*: farahrizkipermatasari  
*Fitur Sprint 1*: F-03 (Pengajuan Bantuan Air Bersih)  
*Daftar PBI Sprint 1*: PBI-007, PBI-008, PBI-009  

---

### Rincian Pengerjaan Fitur F-03

#### 1. PBI-007: Susun Draf Pengajuan Beserta Foto Pendukung
- **Migration & Model**: Membuat migration dan model `Pengajuan` dan `BerkasPengajuan` beserta relasi ke entitas `Desa` dan `SimulasiKelayakan`.
- **Pre-filled Form & Parameter Locking**: Formulir pengajuan otomatis terisi data hasil cek kelayakan mandiri. Tiga parameter kondisi darurat (**Jumlah KK Terdampak**, **Lama Hari Tanpa Air**, dan **Jarak Sumber Air Alternatif**) dikunci secara ketat (*readonly* & terlindungi pada *backend*) dari Cek Kelayakan Mandiri (F-02) agar kalkulasi skor prioritas SPK (AHP-SAW) tidak dapat dimanipulasi secara manual oleh pemohon.
- **Validasi Berkas**: Komponen unggah foto kondisi lapangan hanya menerima berkas gambar JPG/PNG dengan batas ukuran maksimal 5 MB per foto (maksimal 3 foto) serta menolak berkas tidak sesuai, serta komponen scan surat pengantar Kepala Desa berstempel basah.
- **Penyimpanan Draf**: Controller penyimpanan draf pengajuan ke basis data pusat.

#### 2. PBI-008: Lihat, Ubah, dan Hapus Draf Pengajuan
- **Halaman Daftar & Detail Draf**: Menampilkan daftar dan detail draf pengajuan milik desa yang sedang login.
- **Form Ubah Draf & Hapus dengan Konfirmasi**: Menyediakan antarmuka untuk mengubah isi draf serta tombol hapus dengan jendela modal konfirmasi.
- **Kebijakan Akses (Policy)**: `PengajuanPolicy` membatasi draf hanya dapat dilihat, diubah, dan dihapus oleh perwakilan desa pemiliknya. Draf yang sudah dikirim terkunci (tidak dapat diubah atau dihapus).

#### 3. PBI-009: Kirim Pengajuan ke Antrean Verifikasi
- **Penerbitan Nomor Tiket Unik**: Sistem menerbitkan format nomor tiket unik `LA-YYYYMMDD-XXX` saat pengajuan dikirim.
- **Antrean Verifikasi Admin**: Status pengajuan otomatis beralih menjadi `Diajukan` dan masuk ke antrean verifikasi Admin BPBD.
- **Validasi Kedaruratan**: Memvalidasi hasil cek berstatus `Kritis` atau `Waspada` sebelum dapat dikirim (menolak jika status `Aman`).
- **Batasan Tiket Aktif (Anti-Spam)**: Sistem menolak pengiriman pengajuan baru jika desa bersangkutan masih memiliki tiket bantuan aktif yang belum terselesaikan.
- **Komponen Konfirmasi & Halaman Sukses**: Jendela modal konfirmasi kirim dan halaman "Pengajuan Berhasil Dikirim" sesuai mockup proposal halaman 58, 59, dan 60.
