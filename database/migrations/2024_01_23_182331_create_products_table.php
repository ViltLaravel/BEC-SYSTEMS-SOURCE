<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id_product');
            $table->unsignedInteger('id_category');
            $table->foreign('id_category')
                  ->references('id_category')
                  ->on('categories')
                  ->onUpdate('restrict')
                  ->onDelete('restrict');
            $table->string('code_product')->unique();
            $table->string('name_product')->unique();
            $table->string('brand')->nullable();
            $table->integer('price_purchase');
            $table->integer('price_selling');
            $table->integer('stock');
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
        Schema::dropIfExists('products');
    }
}
