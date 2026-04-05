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
        Schema::create('candidates', function (Blueprint $table) {
            $table->id();
            //candidato proviene de voter, con mismo id, pero con un campo adicional de partido, y otro de distrito y un orden de preferencia
            $table->foreignId('voter_id')->constrained('voters')->onDelete('cascade')->nullable(true);
            $table->foreignId('party_id')->constrained('parties')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'candidates');
    }
};
