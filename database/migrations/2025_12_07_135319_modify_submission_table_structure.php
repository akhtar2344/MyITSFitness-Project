<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('submission', function (Blueprint $table) {
            $table->text('name')->nullable()->after('activity_id'); // Add nullable name column
            $table->dropColumn('notes'); // Remove notes column
        });
        
        // Update existing records with activity name
        DB::statement("
            UPDATE submission 
            SET name = activity.name 
            FROM activity 
            WHERE submission.activity_id = activity.id
        ");
        
        // Make name not nullable after data migration
        Schema::table('submission', function (Blueprint $table) {
            $table->text('name')->nullable(false)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('submission', function (Blueprint $table) {
            $table->dropColumn('name');
            $table->text('notes')->nullable();
        });
    }
};
