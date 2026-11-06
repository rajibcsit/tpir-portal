<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {
  Schema::table('students', function (Blueprint $table) { $table->string('profile_photo')->nullable()->after('name'); });
  Schema::table('users', function (Blueprint $table) { $table->string('role')->default('admin')->after('password'); $table->foreignId('student_id')->nullable()->after('role')->constrained('students')->nullOnDelete(); });
  Schema::table('exam_results', function (Blueprint $table) { $table->decimal('full_marks', 8, 2)->default(100)->after('subject_name'); $table->decimal('pass_marks', 8, 2)->default(40)->after('full_marks'); });
 }
 public function down(): void {
  Schema::table('exam_results', fn(Blueprint $table) => $table->dropColumn(['full_marks','pass_marks']));
  Schema::table('users', function (Blueprint $table) { $table->dropConstrainedForeignId('student_id'); $table->dropColumn('role'); });
  Schema::table('students', fn(Blueprint $table) => $table->dropColumn('profile_photo'));
 }
};
