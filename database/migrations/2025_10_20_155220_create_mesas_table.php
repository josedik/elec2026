<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('mesas', function (Blueprint $table) {
            $table->id();
            $table->string('code',6)->unique();
            $table->bigInteger('electors');
            $table->string('dnii',8)->nullable(1);
            $table->string('dnif',8)->nullable(1);
            $table->foreignId('district_id')->constrained('districts');
            $table->foreignId('school_id')->constrained('schools');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mesas');
    }
};
