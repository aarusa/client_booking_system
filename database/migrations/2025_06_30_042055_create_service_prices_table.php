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
        Schema::create('service_prices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->enum('dog_size', ['small', 'medium', 'large', 'extra_large']);
            $table->decimal('price', 8, 2);
            $table->timestamps();
            
            // Ensure unique combination of service and dog size
            $table->unique(['service_id', 'dog_size']);
            
            // Add indexes for better performance
            $table->index(['service_id']);
            $table->index(['dog_size']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service_prices');
    }
};
