<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * FEATURE: Drop unused status_history table to clean up database schema
     */
    public function up(): void
    {
        // FEATURE: Remove status_history table as it's not being used in the application
        Schema::dropIfExists('status_history');
    }

    /**
     * FEATURE: Restore status_history table if rollback is needed
     */
    public function down(): void
    {
        // FEATURE: Recreate status_history table structure for potential rollback
        Schema::create('status_history', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('submission_id');
            $table->string('from_status');
            $table->string('to_status'); 
            $table->text('note')->nullable();
            $table->timestamp('changed_at');
            
            $table->foreign('submission_id')->references('id')->on('submission')->onDelete('cascade');
        });
        
        // FEATURE: Set ENUM types for status columns
        DB::statement("ALTER TABLE public.status_history ALTER COLUMN from_status TYPE public.submission_status USING from_status::public.submission_status;");
        DB::statement("ALTER TABLE public.status_history ALTER COLUMN to_status TYPE public.submission_status USING to_status::public.submission_status;");
    }
};
