<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('exam_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->string('exam_name', 120);
            $table->string('exam_year', 10);
            $table->string('semester', 30)->nullable();
            $table->string('subject_code', 40)->nullable();
            $table->string('subject_name', 150);
            $table->decimal('credit', 5, 2)->default(3);
            $table->decimal('marks', 8, 2)->nullable();
            $table->string('letter_grade', 5);
            $table->decimal('grade_point', 3, 2);
            $table->timestamps();
            $table->index(['student_id', 'exam_name', 'exam_year', 'semester']);
        });
    }
    public function down(): void { Schema::dropIfExists('exam_results'); }
};
