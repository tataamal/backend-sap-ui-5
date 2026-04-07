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
        Schema::table('outstanding_so_t_data1', function (Blueprint $table) {
            $table->string('erdat', 8)->nullable()->after('kmtl');
        });

        Schema::table('outstanding_so_t_data2', function (Blueprint $table) {
            $table->string('erdat', 8)->nullable()->after('kmtl');
        });

        Schema::table('outstanding_so_t_data3', function (Blueprint $table) {
            $table->string('erdat', 8)->nullable()->after('kmtl');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outstanding_so_t_data1', function (Blueprint $table) {
            $table->dropColumn('erdat');
        });

        Schema::table('outstanding_so_t_data2', function (Blueprint $table) {
            $table->dropColumn('erdat');
        });

        Schema::table('outstanding_so_t_data3', function (Blueprint $table) {
            $table->dropColumn('erdat');
        });
    }
};
