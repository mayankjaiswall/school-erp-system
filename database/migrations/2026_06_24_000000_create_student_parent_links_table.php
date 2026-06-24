<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_parent_links', function (Blueprint $table) {
            $table->id();
            $table->foreignId('parent_id')->constrained('parents')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('relationship');
            $table->timestamps();

            $table->unique(['parent_id', 'student_id']);
            $table->index('parent_id');
            $table->index('student_id');
            $table->index('relationship');
        });

        if (Schema::hasTable('student_parent')) {
            DB::table('student_parent')
                ->orderBy('id')
                ->get()
                ->each(function ($link) {
                    DB::table('student_parent_links')->updateOrInsert(
                        [
                            'parent_id' => $link->parent_id,
                            'student_id' => $link->student_id,
                        ],
                        [
                            'relationship' => $link->relationship,
                            'created_at' => $link->created_at,
                            'updated_at' => $link->updated_at,
                        ]
                    );
                });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('student_parent_links');
    }
};
