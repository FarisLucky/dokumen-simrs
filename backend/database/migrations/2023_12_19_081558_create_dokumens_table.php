<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dokumens', function (Blueprint $table) {
            $table->id();
            $table->date('tgl_mrs')->nullable();
            $table->string('register', 25)->nullable();
            $table->string('mr', 10)->nullable();
            $table->string('pasien')->nullable();
            $table->string('nama')->nullable();
            $table->string('nama_dok', 150)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('tgl_periksa')->nullable();
            $table->string('penunjang', 50)->nullable();
            $table->string('sumber', 10)->nullable();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('created_by_name', 50)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}
