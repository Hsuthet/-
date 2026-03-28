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
    
    if (!Schema::hasColumn('users', 'employee_number')) {
        Schema::table('users', function (Blueprint $table) {
            $table->string('employee_number')->nullable()->unique()->after('id');
        });
    }

    
    $users = \App\Models\User::whereNull('employee_number')->get();
    foreach ($users as $index => $user) {
        $user->update([
            'employee_number' => 'EMP-' . date('Y') . '-' . str_pad($user->id, 3, '0', STR_PAD_LEFT)
        ]);
    }
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
       
        $table->dropColumn('employee_number');
    });
}
};
