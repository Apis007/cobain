<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Redaman extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('redaman', function (Blueprint $table) {
            $table->id();
            $table->string('port', 255);
            $table->string('redaman', 255);
            $table->integer('id_pelanggan');
            $table->string('nama', 255);
            $table->string('alamat', 255);
            $table->string('paket', 50)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('redaman');
    }
}
