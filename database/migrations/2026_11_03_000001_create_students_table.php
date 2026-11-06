<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void { Schema::create('students',function(Blueprint $t){$t->id();$t->string('student_id')->unique();$t->string('name');$t->string('department');$t->string('technology')->nullable();$t->string('semester')->nullable();$t->string('shift')->nullable();$t->string('session')->nullable();$t->string('phone')->nullable();$t->string('email')->nullable();$t->string('guardian_name')->nullable();$t->string('guardian_phone')->nullable();$t->text('address')->nullable();$t->date('joining_date')->nullable();$t->enum('status',['Active','Inactive','Graduated'])->default('Active');$t->timestamps();});}
 public function down(): void {Schema::dropIfExists('students');}
};
