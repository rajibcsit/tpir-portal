<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('students') || !Schema::hasColumn('students', 'technology')) return;

        $catalog = [
            'Computer (CST)' => ['computer (cst)', 'cst', 'computer', 'computer technology', 'computer engineering', 'computer technology (cst)', 'cst technology'],
            'Civil (CT)' => ['civil (ct)', 'ct', 'civil', 'civil technology', 'civil engineering', 'civil technology (ct)'],
            'Electrical (ET)' => ['electrical (et)', 'et', 'electrical', 'electrical technology', 'electrical engineering', 'electrical technology (et)'],
            'Mechanical (MT)' => ['mechanical (mt)', 'mt', 'mechanical', 'mechanical technology', 'mechanical engineering', 'mechanical technology (mt)'],
            'Textile (TT)' => ['textile (tt)', 'tt', 'textile', 'textile technology', 'textile technology (tt)'],
            'Architecture (AT)' => ['architecture (at)', 'at', 'architecture', 'architecture technology', 'architecture technology (at)'],
        ];

        foreach ($catalog as $canonical => $aliases) {
            $department = trim(strstr($canonical, ' (', true) ?: $canonical);
            foreach ($aliases as $alias) {
                DB::table('students')->whereRaw('LOWER(TRIM(technology)) = ?', [$alias])->update([
                    'technology' => $canonical,
                    'department' => $department,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Keep normalized student technology data; reverting labels could make records ambiguous.
    }
};
