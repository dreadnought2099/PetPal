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
        Schema::table('adoption_requests', function (Blueprint $table) {
            if (!Schema::hasColumn('adoption_requests', 'valid_id')) {
                $table->string('valid_id')->nullable();
            }
            if (!Schema::hasColumn('adoption_requests', 'valid_id_back')) {
                $table->string('valid_id_back')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('adoption_requests', function (Blueprint $table) {
            if (Schema::hasColumn('adoption_requests', 'valid_id')) {
                $table->dropColumn('valid_id');
            }
            if (Schema::hasColumn('adoption_requests', 'valid_id_back')) {
                $table->dropColumn('valid_id_back');
            }
        });
    }
};
