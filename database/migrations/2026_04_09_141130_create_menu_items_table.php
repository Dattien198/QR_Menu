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
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->decimal('price', 15, 2);
            $table->decimal('cost', 15, 2)->nullable();
            $table->json('images')->nullable();
            $table->text('description')->nullable();
            $table->json('variants')->nullable();
            $table->string('tags')->nullable();
            $table->integer('calories')->nullable();
            $table->integer('preparation_time')->nullable(); // in minutes
            $table->string('status')->default('available'); // available, out_of_stock, upcoming
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
