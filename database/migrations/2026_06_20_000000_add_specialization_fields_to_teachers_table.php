<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->foreignId('primary_subject_id')
                ->nullable()
                ->after('school_id')
                ->constrained('subjects')
                ->nullOnDelete();

            $table->unsignedInteger('experience_years')
                ->nullable()
                ->after('experience');

            $table->string('designation')
                ->nullable()
                ->after('joining_date');
        });

        DB::table('teachers')
            ->whereNotNull('experience')
            ->update(['experience_years' => DB::raw('experience')]);
    }

    public function down(): void
    {
        Schema::table('teachers', function (Blueprint $table) {
            $table->dropConstrainedForeignId('primary_subject_id');
            $table->dropColumn(['experience_years', 'designation']);
        });
    }
};
