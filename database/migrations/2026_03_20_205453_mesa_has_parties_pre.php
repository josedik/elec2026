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
        Schema::create('mesa_has_parties_pre', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mesa_id')->constrained('mesas')->onDelete('cascade');
            $table->foreignId('party_id')->constrained('parties')->onDelete('cascade');
            $table->unsignedBigInteger('votes_president')->default(0);
            $table->unsignedBigInteger('votes_senatornac')->default(0);
            $table->unsignedBigInteger('votes_senatorreg')->default(0);
            $table->unsignedBigInteger('votes_diputies')->default(0);
            $table->unsignedBigInteger('votes_andino')->default(0);
            $table->timestamps();

            $table->unique(['mesa_id', 'party_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
    Schema::dropIfExists('mesa_has_parties_pre');

    }
};
