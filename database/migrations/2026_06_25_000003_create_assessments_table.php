<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assessments', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('class_name')->nullable()->index();
            $table->date('assessment_date')->nullable()->index();
            $table->unsignedSmallInteger('total_marks')->default(100);
            $table->boolean('is_published')->default(false)->index();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['class_name', 'is_published', 'assessment_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assessments');
    }
};
