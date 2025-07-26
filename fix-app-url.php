<?php
/**
 * Script untuk mengupdate APP_URL di file .env
 * Berguna untuk memastikan logo dan asset lain dapat diakses melalui email
 */

$envFile = __DIR__ . '/.env';

if (!file_exists($envFile)) {
    echo "❌ File .env tidak ditemukan!\n";
    echo "💡 Salin .env.example ke .env terlebih dahulu\n";
    exit(1);
}

// Baca file .env
$envContent = file_get_contents($envFile);

// Deteksi project name untuk Laragon
$projectName = basename(__DIR__);

// URL options untuk Laragon
$laravelUrls = [
    "http://{$projectName}.test",
    "http://localhost/{$projectName}/public",
    "http://127.0.0.1:8000",
    "http://localhost:8000"
];

echo "🔧 Script Perbaikan APP_URL untuk Email Verification\n";
echo "═══════════════════════════════════════════════════\n\n";

echo "📂 Project: {$projectName}\n";
echo "📍 Current APP_URL: " . (getenv('APP_URL') ?: 'Not set') . "\n\n";

echo "Pilih URL yang sesuai dengan environment Anda:\n";
foreach ($laravelUrls as $index => $url) {
    echo ($index + 1) . ". {$url}\n";
}
echo "5. Custom URL\n";
echo "0. Batal\n\n";

$choice = readline("Pilihan Anda (0-5): ");

switch ($choice) {
    case '1':
    case '2':
    case '3':
    case '4':
        $selectedUrl = $laravelUrls[$choice - 1];
        break;
    case '5':
        $selectedUrl = readline("Masukkan URL custom (contoh: http://yourapp.local): ");
        break;
    case '0':
        echo "❌ Dibatalkan\n";
        exit(0);
    default:
        echo "❌ Pilihan tidak valid\n";
        exit(1);
}

// Update APP_URL di .env
if (preg_match('/^APP_URL=(.*)$/m', $envContent)) {
    $newEnvContent = preg_replace('/^APP_URL=(.*)$/m', "APP_URL={$selectedUrl}", $envContent);
} else {
    $newEnvContent = $envContent . "\nAPP_URL={$selectedUrl}";
}

// Simpan file .env yang baru
if (file_put_contents($envFile, $newEnvContent)) {
    echo "\n✅ APP_URL berhasil diupdate ke: {$selectedUrl}\n";
    echo "🔄 Jalankan: php artisan config:clear\n";
    echo "📧 Kemudian test email: php artisan email:test-verification [email]\n";
} else {
    echo "\n❌ Gagal mengupdate file .env\n";
    exit(1);
}

echo "\n📋 Checklist:\n";
echo "  ☐ Jalankan: php artisan config:clear\n";
echo "  ☐ Test email verification\n";
echo "  ☐ Pastikan logo tampil dengan benar\n";
echo "\n💡 Jika logo masih tidak tampil, pastikan file logo.png ada di:\n";
echo "   public/assets/img/branding/logo.png\n";
?> 