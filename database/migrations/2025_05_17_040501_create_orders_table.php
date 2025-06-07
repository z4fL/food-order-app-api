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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->tinyInteger('meja');
            $table->text('catatan')->nullable();
            $table->bigInteger('total_harga')->unsigned();
            $table->enum('status', ['belum dibayar', 'diproses', 'diantar'])->default('belum dibayar');

            // Xendit fields
            $table->string('external_id')->nullable(); // buat Xendit
            $table->string('checkout_link')->nullable(); // link invoice
            $table->string('status_xendit')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
