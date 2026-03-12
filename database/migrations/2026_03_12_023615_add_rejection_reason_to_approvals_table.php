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
    Schema::table('approvals', function (Blueprint $table) {
        // Use text so users can write a detailed reason
        $table->text('rejection_reason')->nullable()->after('approval_status');
    });
}

public function down(): void
{
    Schema::table('approvals', function (Blueprint $table) {
        $table->dropColumn('rejection_reason');
    });
}
};
