<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('user_id')
                ->nullable()
                ->after('id')
                ->constrained('users')
                ->nullOnDelete();

            $table->string('employee_code')
                ->nullable()
                ->after('school_id');

            $table->date('joining_date')
                ->nullable()
                ->after('experience');

            $table->unique('user_id');
            $table->unique(['school_id', 'employee_code']);
        });
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropUnique(['school_id', 'employee_code']);
            $table->dropUnique(['user_id']);
            $table->dropConstrainedForeignId('user_id');
            $table->dropColumn(['employee_code', 'joining_date']);
        });
    }
};
