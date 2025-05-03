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
            $table->string('name');
            $table->string('slogo');
            $table->string('brand');
            $table->integer('quantity');
            $table->decimal('price', 8, 2);
            $table->string('image');
            $table->decimal('oldPrice', 8, 2)->nullable();
            $table->float('rating');
            $table->integer('reviewCount');
            $table->string('sold');
            $table->string('categorie');
            $table->string('subCategorie');
            $table->text('details');
            $table->timestamps();
            $table->softDeletes();
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
