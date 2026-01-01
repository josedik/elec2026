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
        Schema::create('schools', function (Blueprint $table) {
           $table->id();
            $table->string('code',6)->unique();
            $table->string('name');
            $table->string('address')->nullable()->default(null);
            $table->integer('tables')->nullable()->default(0);
            $table->bigInteger('voters')->nullable()->default(0);
            $table->foreignId('district_id')->constrained('districts');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schools');
    }
};
