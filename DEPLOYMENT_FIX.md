# Perbaikan Masalah Deployment

## Masalah yang Ditemukan

Error terjadi karena ada duplikasi migration untuk tabel `password_reset_tokens`:

1. Migration `0001_01_01_000000_create_users_table.php` sudah membuat tabel `password_reset_tokens`
2. Migration `2025_08_08_155526_create_password_reset_tokens_table.php` mencoba membuat tabel yang sama lagi

## Solusi yang Diterapkan

1. **Menghapus migration duplikat**: File `2025_08_08_155526_create_password_reset_tokens_table.php` telah dihapus
2. **Membuat script deployment**: File `deploy-fix.sh` untuk membantu deployment

## Langkah Deployment

### Opsi 1: Menggunakan Script (Direkomendasikan)
```bash
chmod +x deploy-fix.sh
./deploy-fix.sh
```

### Opsi 2: Manual Commands
```bash
# Clear cache
php artisan config:clear
php artisan cache:clear

# Jalankan migration dengan force flag
php artisan migrate --force

# Clear cache lagi
php artisan config:clear
php artisan cache:clear
```

## Pencegahan di Masa Depan

1. **Selalu periksa migration yang sudah ada** sebelum membuat migration baru
2. **Gunakan `php artisan make:migration`** untuk membuat migration baru
3. **Periksa duplikasi** dengan mencari nama tabel yang sama di semua file migration
4. **Test migration di environment development** sebelum deploy ke production

## Migration yang Sudah Ada

Migration `0001_01_01_000000_create_users_table.php` sudah mencakup:
- Tabel `users`
- Tabel `password_reset_tokens`
- Tabel `sessions`

Jangan membuat migration baru untuk tabel-tabel ini.
