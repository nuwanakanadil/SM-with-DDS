<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->string('class_group')->nullable()->after('class_name')->index();
            $table->index(['class_name', 'class_group', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::table('students', function (Blueprint $table) {
            $table->dropIndex(['class_name', 'class_group', 'is_active']);
            $table->dropColumn('class_group');
        });
    }
};
