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
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->unsignedInteger('age')->nullable(false);
            $table->string('breed', 100)->nullable(false);
            $table->text('allergies')->nullable();
            $table->string('pet_profile_path')->nullable();
            $table->enum('sex', ['M', 'F'])->nullable(false);
            $table->unsignedInteger('species')->nullable(false);
            $table->unsignedInteger('vaccination')->nullable(false);
            $table->boolean('spayed_neutered')->nullable(false)->default(false);
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
            $table->index(['species', 'breed']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pets');
    }
};
