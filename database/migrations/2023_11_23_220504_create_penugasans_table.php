<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    
    public function up()
    {
        Schema::create('penugasans', function (Blueprint $table) {
            $table->id();
            $table->bigInteger(column:"tugas_id")->unsigned();
            $table->bigInteger(column:"pengguna_id")->unsigned();
            $table->date(column: "tanggal");
            $table->enum("status",['draft','on_progress','done']);
            $table->timestamps();

        });
    }

    

    public function down()
    {
        Schema::dropIfExists('penugasans');
    }
};
