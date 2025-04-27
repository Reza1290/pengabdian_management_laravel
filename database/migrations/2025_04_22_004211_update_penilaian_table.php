<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->integer('jumlah_laporan')->after('pengabdian_id');
            $table->integer('jumlah_bolos')->after('jumlah_laporan');
            $table->decimal('persen_kehadiran', 5, 2)->after('jumlah_bolos');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropColumn(['jumlah_laporan', 'jumlah_bolos', 'persen_kehadiran']);
        });
    }
};
