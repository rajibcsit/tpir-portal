<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::create('attendances',function(Blueprint $t){$t->id();$t->foreignId('student_id')->constrained()->cascadeOnDelete();$t->date('attendance_date');$t->enum('status',['Present','Absent','Late','Leave']);$t->string('remarks')->nullable();$t->timestamps();$t->unique(['student_id','attendance_date']);});}
 public function down(): void {Schema::dropIfExists('attendances');}
};
