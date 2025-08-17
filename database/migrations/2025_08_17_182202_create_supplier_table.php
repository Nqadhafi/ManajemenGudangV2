<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSupplierTable extends Migration
{
    public function up()
    {
        Schema::create('supplier', function (Blueprint $table) {
            $table->id();
            $table->string('nama_supplier');
            $table->string('no_hp_supplier')->nullable();
            $table->text('alamat')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('supplier');
    }
}