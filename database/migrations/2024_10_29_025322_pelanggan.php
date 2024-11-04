<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Pelanggan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('pelanggan', function (Blueprint $table) {
            $table->bigInteger('id')->primary();
            $table->string('nama', 255);
            $table->string('alamat', 255);
            $table->enum('status', ['normal', 'promo']);
            $table->bigInteger('paket');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pelanggan');
    }
}
