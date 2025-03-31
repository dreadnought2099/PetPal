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
        Schema::create('adoption_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->string('last_name');
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('address');
            $table->string('contact_number');
            $table->date('dob');
            
            // Adoption-related questions
            $table->enum('previous_experience', ['yes', 'no']);
            $table->enum('other_pets', ['yes', 'no']);
            $table->enum('financial_preparedness', ['yes', 'no']);

            // Status and timestamps
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->timestamps();
            $table->softDeletes(); // Allows restoring deleted adoption requests
            $table->unique(['user_id', 'pet_id']); // Prevents duplicate requests
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('adoption_requests');
    }
};
