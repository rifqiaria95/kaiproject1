# Perbaikan Hierarki Dropdown - Halaman Pegawai/Index

## Masalah yang Diperbaiki
Dropdown `cabang` dan `departemen` pada halaman `pegawai/index` sebelumnya menampilkan semua data yang tersedia, tidak sesuai dengan hierarki yang diinginkan (hanya menampilkan data yang dimiliki oleh perusahaan/divisi yang dipilih).

## Perubahan yang Dilakukan

### 1. Controller (`PegawaiController.php`)
- **Menghapus** variabel `$cabang` dan `$departemen` dari method `index()`
- **Menghapus** data cabang dan departemen dari compact array yang dikirim ke view

### 2. View (`pegawai/index.blade.php`)
- **Menghapus** populate data cabang dan departemen dari controller
- Dropdown `cabang` dan `departemen` sekarang hanya memiliki option default "Pilih Cabang" dan "Pilih Departemen"

### 3. JavaScript (`hierarchy-dropdown.js`)
- **Menambahkan** event handler untuk dropdown dengan ID `pegawai-perusahaan` dan `pegawai-divisi`
- **Menambahkan** event handler untuk reset dropdown saat modal ditutup
- **Menambahkan** event handler untuk reset dropdown saat form di-reset

### 4. JavaScript (`pegawai.js`)
- **Menghapus** duplikasi event handler untuk `pegawai-perusahaan`
- **Menambahkan** reset dropdown saat modal ditutup
- **Menambahkan** reset dropdown saat form di-reset (mode insert)
- **Memperbaiki** populate dropdown saat edit data dengan AJAX call yang sesuai

## Hasil Implementasi

### Sebelum Perbaikan:
- Dropdown `cabang` menampilkan semua cabang dari semua perusahaan
- Dropdown `departemen` menampilkan semua departemen dari semua divisi
- User bisa memilih cabang/departemen tanpa memilih perusahaan/divisi terlebih dahulu

### Setelah Perbaikan:
- Dropdown `cabang` awalnya kosong, hanya terisi setelah user memilih perusahaan
- Dropdown `departemen` awalnya kosong, hanya terisi setelah user memilih divisi
- Data yang ditampilkan sesuai dengan hierarki yang dipilih
- Dropdown di-reset saat modal ditutup atau form di-reset

## Cara Kerja

1. **Saat halaman dimuat**: Dropdown `cabang` dan `departemen` hanya menampilkan option default
2. **Saat user memilih perusahaan**: Event handler `pegawai-perusahaan` terpicu, melakukan AJAX call ke `/pegawai/get-cabang/{id_perusahaan}`, dan mengisi dropdown `cabang` dengan data yang sesuai
3. **Saat user memilih divisi**: Event handler `pegawai-divisi` terpicu, melakukan AJAX call ke `/pegawai/get-departemen/{id_divisi}`, dan mengisi dropdown `departemen` dengan data yang sesuai
4. **Saat modal ditutup**: Dropdown `cabang` dan `departemen` di-reset ke kondisi awal
5. **Saat edit data**: Dropdown di-populate dengan data yang sesuai menggunakan AJAX call

## Testing

Untuk memastikan implementasi berfungsi dengan baik:

1. Buka halaman `pegawai/index`
2. Klik tombol "Tambah Pegawai"
3. Pilih perusahaan dari dropdown "Perusahaan"
4. Verifikasi bahwa dropdown "Cabang" hanya menampilkan cabang yang dimiliki oleh perusahaan yang dipilih
5. Pilih divisi dari dropdown "Divisi"
6. Verifikasi bahwa dropdown "Departemen" hanya menampilkan departemen yang dimiliki oleh divisi yang dipilih
7. Tutup modal dan buka kembali untuk memastikan dropdown di-reset dengan benar

## File yang Dimodifikasi

- `app/Http/Controllers/Mono/PegawaiController.php`
- `resources/views/internal/pegawai/index.blade.php`
- `public/assets/js/hierarchy-dropdown.js`
- `public/assets/ajax/pegawai.js` 