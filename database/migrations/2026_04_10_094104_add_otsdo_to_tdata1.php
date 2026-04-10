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
            $table->string('otsdo', 100)->nullable()->after('erdat');
        });

        Schema::table('outstanding_so_t_data2', function (Blueprint $table) {
            $table->string('otsdo', 100)->nullable()->after('erdat');
        });

        Schema::table('outstanding_so_t_data3', function (Blueprint $table) {
            $table->string('otsdo', 100)->nullable()->after('erdat');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('outstanding_so_t_data1', function (Blueprint $table) {
            $table->dropColumn('otsdo');
        });

        Schema::table('outstanding_so_t_data2', function (Blueprint $table) {
            $table->dropColumn('otsdo');
        });

        Schema::table('outstanding_so_t_data3', function (Blueprint $table) {
            $table->dropColumn('otsdo');
        }); 
    }
};
