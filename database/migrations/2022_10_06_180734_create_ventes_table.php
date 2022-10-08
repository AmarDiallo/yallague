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
        Schema::create('ventes', function (Blueprint $table) {
            $table->id();
            $table->string('quantity');
            $table->string('amount');
            $table->unsignedBigInteger('id_sub_category');
            $table->unsignedBigInteger('id_user');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('id_sub_category')->references('id')->on('sub_categories');
            $table->foreign('id_user')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ventes');
    }
};
