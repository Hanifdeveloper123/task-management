<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
 
    
    public function up()
    {
        Schema::create('penggunas', function (Blueprint $table) {
            $table->id();
            $table->string(column: "username");
            $table->string(column: "email");
            $table->string(column: "password");
            $table->bigInteger(column: "role_id")->unsigned();
            $table->timestamps();
        });
    }

   
    public function down()
    {
        Schema::dropIfExists('penggunas');
    }
};
