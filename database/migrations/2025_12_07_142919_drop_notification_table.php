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
        Schema::dropIfExists('notification');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Recreate notification table if needed to rollback
        Schema::create('notification', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('recipient_user_id');
            $table->string('type');
            $table->text('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->uuid('related_submission_id')->nullable();
            $table->timestampTz('created_at')->useCurrent();
        });
    }
};
