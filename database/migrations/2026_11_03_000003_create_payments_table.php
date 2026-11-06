<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
 public function up(): void {Schema::create('payments',function(Blueprint $t){$t->id();$t->foreignId('student_id')->constrained()->cascadeOnDelete();$t->string('title');$t->decimal('amount_due',10,2)->default(0);$t->decimal('amount_paid',10,2)->default(0);$t->date('due_date')->nullable();$t->date('paid_date')->nullable();$t->string('method')->nullable();$t->text('note')->nullable();$t->timestamps();});}
 public function down(): void {Schema::dropIfExists('payments');}
};
