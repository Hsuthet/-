<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up()
    {
        Schema::table('requests', function (Blueprint $table) {

            $table->foreignId('target_department_id')
                ->nullable()
                ->constrained('departments')
                ->nullOnDelete();

            $table->foreignId('worker_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

        });
    }

    public function down()
    {
        Schema::table('requests', function (Blueprint $table) {

            $table->dropForeign(['target_department_id']);
            $table->dropColumn('target_department_id');

            $table->dropForeign(['worker_id']);
            $table->dropColumn('worker_id');

        });
    }
};
