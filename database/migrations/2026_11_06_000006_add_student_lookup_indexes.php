<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->index(['technology', 'semester', 'session', 'status'], 'students_class_lookup_idx');
            $table->index(['department', 'technology'], 'students_department_technology_idx');
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex('students_class_lookup_idx');
            $table->dropIndex('students_department_technology_idx');
        });
    }
};
