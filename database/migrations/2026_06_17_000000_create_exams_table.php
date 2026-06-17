<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('exams', function (Blueprint $table) {
            $table->id();
            $table->foreignId('school_id')->constrained('schools')->onDelete('cascade');
            $table->string('name');
            $table->string('exam_type');
            $table->date('exam_date');
            $table->string('academic_year');
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->unique(['school_id', 'name', 'academic_year']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('exams');
    }
};
