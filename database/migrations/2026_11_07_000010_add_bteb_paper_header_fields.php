<?php
use Illuminate\Database\Migrations\Migration; use Illuminate\Database\Schema\Blueprint; use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::table('question_papers', function(Blueprint $t){
  $t->string('board_name')->nullable()->after('title');
  $t->string('program_name')->nullable()->after('board_name');
  $t->string('exam_session')->nullable()->after('program_name');
  $t->string('exam_year')->nullable()->after('exam_session');
  $t->string('exam_term')->nullable()->after('exam_year');
  $t->string('department_name')->nullable()->after('exam_term');
 }); }
 public function down(): void { Schema::table('question_papers', function(Blueprint $t){$t->dropColumn(['board_name','program_name','exam_session','exam_year','exam_term','department_name']);}); }
};
