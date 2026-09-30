<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kategori_item_master_item', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->unsignedBigInteger('master_item_id');

            $table->foreign('kategori_id')->references('id')->on('kategori_items')->onDelete('cascade');
            $table->foreign('master_item_id')->references('id')->on('master_items')->onDelete('cascade');

            $table->unique(['master_item_id', 'kategori_id']);
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
        Schema::dropIfExists('kategori_item_master_item');
    }
};
