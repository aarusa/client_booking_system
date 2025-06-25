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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained('clients')->onDelete('cascade');
            $table->foreignId('dog_id')->constrained('dogs')->onDelete('cascade');
            $table->string('subscription_name')->nullable()->comment('e.g., "Monthly Grooming Package"');
            $table->enum('frequency', ['weekly', 'biweekly', 'monthly', 'quarterly', 'custom'])->default('monthly');
            $table->integer('frequency_weeks')->nullable()->comment('For custom frequency in weeks');
            $table->date('start_date');
            $table->date('end_date')->nullable()->comment('null = ongoing subscription');
            $table->time('preferred_time');
            $table->decimal('price_per_session', 8, 2);
            $table->boolean('auto_book')->default(true)->comment('Automatically create appointments');
            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Add indexes for better performance
            $table->index(['client_id', 'is_active']);
            $table->index(['start_date', 'end_date']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
};
