<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('testimoni', function (Blueprint $table) {
            // Drop foreign key constraints first
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);

            // Make columns nullable
            $table->foreignId('updated_by')->nullable()->change()->constrained('users');
            $table->foreignId('deleted_by')->nullable()->change()->constrained('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('testimoni', function (Blueprint $table) {
            // Drop foreign key constraints
            $table->dropForeign(['updated_by']);
            $table->dropForeign(['deleted_by']);

            // Make columns not nullable again
            $table->foreignId('updated_by')->change()->constrained('users');
            $table->foreignId('deleted_by')->change()->constrained('users');
        });
    }
};
