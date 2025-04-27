<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyPenilaianTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->dropColumn(['jumlah_laporan', 'jumlah_bolos', 'nilai_kehadiran']);
            
            $table->decimal('persen_laporan', 5, 2)->after('persen_kehadiran'); 
            $table->decimal('persen_bolos', 5, 2)->after('persen_laporan');
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
            // Add the old fields back in case of rollback
            $table->integer('jumlah_laporan')->nullable()->after('existing_column');
            $table->integer('jumlah_bolos')->nullable()->after('jumlah_laporan');
            $table->integer('nilai_kehadiran')->nullable()->after('jumlah_bolos');
            
            // Drop the new fields
            $table->dropColumn(['persen_laporan', 'persen_bolos']);
        });
    }
}
