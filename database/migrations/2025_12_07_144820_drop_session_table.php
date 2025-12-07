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
        Schema::dropIfExists('session');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate session table if needed to rollback
        Schema::create('session', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->text('token')->unique();
            $table->timestampTz('issued_at')->useCurrent();
            $table->timestampTz('expires_at');
            $table->timestampTz('revoked_at')->nullable();
            $table->timestampTz('last_login_at')->nullable();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            
            $table->foreign('user_id')->references('id')->on('user_account')->onDelete('cascade');
        });
    }
};
