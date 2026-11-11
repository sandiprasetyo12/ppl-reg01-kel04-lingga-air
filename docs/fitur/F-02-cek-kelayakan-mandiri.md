# F-02 | Cek Kelayakan Mandiri

**Aktor:** Perwakilan Desa  
**Tujuan:** Membantu perwakilan mengetahui kelayakan desa sebelum mengajukan bantuan.

## Urutan modul implementasi

| Urutan | Modul | Pekerjaan | Hasil |
|---|---|---|---|
| 1 | Kontrak data dan aturan | Cocokkan nama tabel/kolom dengan ERD, tetapkan satuan, cara memilih desa, serta kategori kondisi berdasarkan skor. Kriteria, penilaian perbandingan AHP, normalisasi SAW, dan ambang kategori dijabarkan di bawah. | Kontrak data dan kalkulasi jelas; keputusan biner “layak/tidak layak” hanya diterapkan jika kriterianya disepakati. |
| 2 | Validasi input backend | Buat Form Request untuk memvalidasi empat input. Tolak nilai negatif, tipe yang salah, dan nilai yang secara logis melebihi data acuan (KK terdampak > KK total atau jiwa rentan > jiwa total). | Input tidak masuk akal ditolak dengan pesan kesalahan per kolom. |
| 3 | Layanan simulasi | Buat service kalkulasi skor sesuai rumus di bawah. Service menerima input lapangan dan data acuan desa sebagai argumen, mengembalikan skor 0–100, dan tidak mengakses database. | Logika skor bisa dikerjakan dan diuji secara mandiri sebelum tabel Desa tersedia. |
| 4 | Form dan tampilan hasil | Buat halaman input empat data, tampilkan error validasi, dan siapkan komponen hasil. Form dapat dikerjakan sebelum relasi desa terintegrasi. | UI input dan hasil siap diintegrasikan. |
| 5 | Migration dan relasi riwayat | Buat tabel `riwayat_cek_kelayakan`, kolom bobot AHP, lalu hubungkan `kode_desa` ke primary key `desas.kode_desa`. | Hasil tersimpan dan terhubung ke master Desa. |
| 6 | Controller dan route | Route submit hanya dapat diakses akun Desa yang sudah menyelesaikan onboarding. Sistem memakai profil Desa yang terhubung ke akun, menghitung skor, menyimpan snapshot, lalu menampilkan hasil. | Alur hitung dan simpan terikat ke profil Desa akun yang sedang login. |
| 7 | Integrasi dan pengujian | Uji pemilihan desa, penggunaan profil, validasi data tidak lengkap, kalkulasi, penyimpanan dan relasi. | Alur utama dan kasus batas terverifikasi. |
| 8 | Verifikasi acceptance criteria | Ukur waktu respons end-to-end pada kondisi uji representatif. Pastikan input tidak masuk akal ditolak dan hasil tampil maksimal 5 detik. | Bukti pemenuhan acceptance criteria sebelum digabungkan. |

## Lokasi implementasi

- `database/migrations/*_create_riwayat_cek_kelayakan_table.php`
- `app/Models/RiwayatCekKelayakan.php`
- `app/Models/Desa.php`
- `app/Http/Requests/SimulasiKelayakanRequest.php`
- `app/Services/CekKelayakanService.php`
- `app/Http/Controllers/CekKelayakanController.php`
- `routes/web.php`
- `resources/views/cek-kelayakan/index.blade.php`
- `tests/Feature/CekKelayakanTest.php`

## Relasi ke Desa

Riwayat terhubung ke `desas.kode_desa` (varchar 15), yang merupakan primary key model Desa. Migration riwayat dibuat terpisah dari migration foreign key karena tabel `desas` baru dibuat oleh migration F-01.

Rumus, ambang normalisasi, dan kategori kondisi skor sudah tersedia pada spesifikasi tambahan di bawah. Kategori Aman/Waspada/Kritis berbeda dari keputusan biner “layak/tidak layak”; keputusan biner itu belum ditentukan dalam acceptance criteria.

## Status implementasi mandiri

Bagian F-02 yang tidak bergantung pada migration Desa sudah tersedia sebagai kalkulator simulasi mandiri:

- Perwakilan mendaftar, memilih satu desa yang belum tertaut, lalu mengisi lima data kependudukan wajib pada onboarding profil desa. Setelah login dan onboarding lengkap, buka `/cek-kelayakan`; identitas kecamatan/desa dan lima data acuan diambil dari profil akun, ditampilkan read-only, dan tidak dapat diganti melalui request. Pengguna hanya mengisi kondisi lapangan (KK terdampak, hari tanpa air, dan jarak sumber air).
- Backend menolak nilai yang bukan bilangan bulat, nilai negatif, jumlah total nol, KK terdampak yang melampaui jumlah KK, serta total kelompok rentan yang melampaui jumlah jiwa.
- Service menghitung bobot dengan AHP dari matriks perbandingan di `config/cek_kelayakan.php`, lalu menghitung skor SAW dari C1–C4. Setelah form dikirim, panel hasil menampilkan kategori dengan ikon, skor, ringkasan kondisi beserta angka input, dan tindakan yang sesuai status.
- Skor gabungan diklasifikasikan sebagai Aman (0–29,99), Waspada (30–59,99), atau Kritis (60–100). Lama tanpa air menjadi salah satu kriteria berbobot dalam skor, bukan pemicu Kritis tersendiri. Kategori ini menunjukkan tingkat kondisi, bukan keputusan biner “layak/tidak layak”.
- Rekomendasi berisi panduan sesuai status. Tombol “Ajukan Pengajuan” hanya ditampilkan untuk Waspada/Kritis dan saat ini nonaktif dengan keterangan bahwa halaman pengajuan belum tersedia; untuk Aman tombol tidak ditampilkan. Karena endpoint pengajuan belum ada, penjagaan server pada proses pengajuan belum dapat diterapkan dan harus ditambahkan saat alur tersebut dibuat.
- Setiap hasil submit valid disimpan di tabel `riwayat_cek_kelayakan`, termasuk `kode_desa`, snapshot angka acuan, tiga isian kondisi lapangan, komponen C1–C4, bobot AHP yang digunakan, rasio konsistensi, skor, status, dan waktu pemeriksaan. Riwayat terhubung ke master Desa dengan foreign key; data profil yang belum lengkap ditolak agar skor tidak dihitung dari angka kosong.
- Seed master hanya memuat identitas dan wilayah desa. Lima kolom demografi dibiarkan kosong sampai diisi dengan data yang disepakati; data tersebut wajib tersedia sebelum kalkulasi dijalankan. Tiga kondisi lapangan pada form (KK terdampak, hari tanpa air, dan jarak sumber air) harus diisi pengguna setiap melakukan pemeriksaan dan tidak memiliki nilai dummy bawaan.
- Root `/` menampilkan landing page. Cek Kelayakan memakai middleware auth, onboarding, dan role Desa.


Verifikasi dilakukan melalui `tests/Unit/CekKelayakanServiceTest.php`, `tests/Feature/CekKelayakanTest.php`, dan `tests/Feature/OnboardingProfileDesaTest.php`, termasuk penggunaan profil akun, akses terautentikasi, validasi lima data profil, relasi riwayat, dan batas waktu hasil 5 detik pada test harness. Waktu jaringan dan browser di produksi tetap bergantung pada hosting dan koneksi pengguna.

## Aturan kalkulasi AHP-SAW

Kriteria C1 adalah proporsi KK terdampak. C2 adalah proporsi penduduk desa dalam kelompok balita, lansia, dan disabilitas; C2 diperlakukan sebagai faktor prioritas/kerentanan meskipun data tidak menyatakan bahwa mereka ikut terdampak. C3 adalah durasi tanpa air dan C4 adalah jarak sumber air.

Penilaian berpasangan AHP yang digunakan untuk prototipe disimpan di `config/cek_kelayakan.php` dengan urutan C1, C2, C3, C4. Penilaian ini memprioritaskan proporsi KK terdampak dan durasi kekeringan, sementara kerentanan dan akses air tetap berkontribusi:

| Pasangan | Penilaian | Makna |
|---|---:|---|
| C1 terhadap C2 | 5 | C1 lebih penting daripada C2 |
| C1 terhadap C3 | 2 | C1 sedikit lebih penting daripada C3 |
| C1 terhadap C4 | 5 | C1 lebih penting daripada C4 |
| C2 terhadap C3 | 1/3 | C3 moderat lebih penting daripada C2 |
| C2 terhadap C4 | 1/2 | C4 sedikit lebih penting daripada C2 |
| C3 terhadap C4 | 4 | C3 lebih penting daripada C4 |

Matriks menghasilkan bobot AHP sekitar C1 **50,47%**, C2 **8,26%**, C3 **30,26%**, C4 **11,00%**. Rasio konsistensi (CR) sekitar **0,0398**, di bawah batas 0,10. Bobot dan ambang status ini adalah asumsi kebijakan untuk prototipe, bukan persentase universal dari standar umum; BPBD perlu mengonfirmasinya sebelum digunakan untuk keputusan operasional.

Nilai normalisasi SAW dibatasi maksimal 1.0:

- `C1 = min(kk_terdampak / jumlah_kk_total, 1.0)`
- `rasio_rentan = (jumlah_balita + jumlah_lansia + jumlah_disabilitas) / jumlah_jiwa_total`
- `C2 = min(rasio_rentan / 0.15, 1.0)`
- `C3 = min(hari_tanpa_air / 7, 1.0)`
- `C4 = min(jarak_sumber_meter / 2000, 1.0)`
- `skor_akhir = (bobot_C1*C1 + bobot_C2*C2 + bobot_C3*C3 + bobot_C4*C4) * 100`

Implementasi perlu menolak pembagi nol (jumlah KK dan jumlah jiwa harus lebih dari nol), nilai negatif, serta jumlah KK terdampak yang melebihi total KK. Nilai hari dan jarak yang melampaui ambang normalisasi tetap sah; kontribusinya dibatasi ke 1.0 sesuai rumus. Pastikan kategori balita, lansia, dan disabilitas tidak saling tumpang tindih; jika tidak, rumus C2 dapat menghitung orang yang sama lebih dari sekali.

Contoh: C1 = 450/600 = 0.75; C2 = (95+80+12)/2400/0.15 = 0.5194; C3 = 5/7 = 0.7143; C4 = min(2500/2000, 1) = 1. Dengan bobot AHP tersebut, skor sekitar **74.76/100** (pembulatan dua desimal), sehingga statusnya Kritis.

Contoh kasus dari profil Desa Gandesul: 300 dari 450 KK terdampak, 80 dari 1.800 jiwa termasuk kelompok rentan, 3 hari tanpa air, dan jarak sumber air 2.000 meter. Dengan bobot tersebut, skor sekitar **60.07/100**, sehingga masuk kategori Kritis.

Skor dan kategori Aman/Waspada/Kritis mengikuti ambang skor gabungan yang ditampilkan di atas. Keduanya tidak otomatis menentukan label biner “layak” atau “tidak layak”; jangan menyimpulkan label biner sebelum kriterianya disepakati.
