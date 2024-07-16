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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_name', 70);
            $table->float('normal_price', 9, 2);
            $table->float('actual_price', 9,2);
            $table->foreignId('company_id')->references('id')->on('companies')->onDelete('cascade');
            $table->foreignId('category_id')->references('id')->on('categories');
            $table->boolean('offer')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
