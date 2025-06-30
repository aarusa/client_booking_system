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
        Schema::create('dogs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->string('name');
            $table->string('breed')->nullable();
            $table->integer('age')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('photo')->nullable();
            $table->decimal('weight', 5, 2)->nullable()->comment('Weight in pounds');
            $table->string('coat_type')->nullable();            
            $table->enum('spayed_neutered', ['yes', 'no', 'unknown'])->nullable();
            $table->string('behavior')->nullable();
            $table->text('tags')->nullable();
            $table->text('notes')->nullable();
            $table->string('before_photo')->nullable()->comment('Before grooming photo');
            $table->string('after_photo')->nullable()->comment('After grooming photo');
            $table->json('general_photos')->nullable()->comment('Array of general photo paths');
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['client_id', 'name']);
            $table->index(['breed']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dogs');
    }
};
