<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('teacher_subjects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained()->onDelete('cascade');
            $table->foreignId('school_class_id')->constrained('school_classes')->onDelete('cascade');
            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            $table->unique(['school_id', 'school_class_id', 'subject_id']);
            $table->unique(['school_id', 'teacher_id', 'school_class_id', 'subject_id'], 'teacher_subject_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('teacher_subjects');
    }
};
