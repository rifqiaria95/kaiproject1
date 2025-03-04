<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {

        // Tabel vendor
        Schema::create('vendor', function (Blueprint $table) {
            $table->id('id_vendor');
            $table->string('nm_vendor');
            $table->text('alamat_vendor');
            $table->foreignId('id_kota')->constrained(config('laravolt.indonesia.table_prefix').'cities', 'id_kota');
            $table->foreignId('id_provinsi')->constrained(config('laravolt.indonesia.table_prefix').'provinces', 'id_provinsi');
            $table->string('permission')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Tabel kendaraan
        Schema::create('kendaraan', function (Blueprint $table) {
            $table->id('id_kendaraan');
            $table->string('nm_kendaraan');
            $table->integer('cc');
            $table->string('produsen');
            $table->string('permission')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Tabel item
        Schema::create('item', function (Blueprint $table) {
            $table->id('id_item');
            $table->string('kd_barcode')->nullable();
            $table->string('kd_item');
            $table->string('nm_item');
            $table->string('jenis_item');
            $table->integer('stok');
            $table->decimal('hrg_beli', 15, 2);
            $table->decimal('hrg_jual', 15, 2);
            $table->string('rak')->nullable();
            $table->unsignedBigInteger('id_satuan');
            $table->string('permission')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        // Tabel pegawai
        Schema::create('pegawai', function (Blueprint $table) {
            $table->id('id_pegawai');
            $table->string('nm_pegawai');
            $table->string('no_telp_pegawai');
            $table->string('foto_pegawai')->nullable();
            $table->string('jabatan_pegawai');
            $table->decimal('gaji_pegawai', 15, 2);
            $table->date('tgl_lahir_pegawai');
            $table->date('tgl_masuk_pegawai');
            $table->date('tgl_keluar_pegawai')->nullable();
            $table->string('no_ktp_pegawai');
            $table->string('no_sim_pegawai')->nullable();
            $table->string('no_npwp_pegawai')->nullable();
            $table->text('alamat_pegawai');
            $table->unsignedBigInteger('user_id');
            $table->tinyInteger('jenis_kelamin');
            $table->foreignId('id_kota')->constrained(config('laravolt.indonesia.table_prefix').'cities', 'id_kota');
            $table->foreignId('id_provinsi')->constrained(config('laravolt.indonesia.table_prefix').'provinces', 'id_provinsi');
            $table->string('permission')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('pegawai');
        Schema::dropIfExists('item');
        Schema::dropIfExists('kendaraan');
        Schema::dropIfExists('vendor');
    }
};
