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
        Schema::create('party_has_voters', function (Blueprint $table) {
            $table->id();
            $table->foreignId('party_id')->constrained('parties')->onDelete('cascade');
            $table->foreignId('voter_id')->nullable()->constrained('voters')->onDelete('cascade');
            $table->foreignId('district_id')->constrained('districts')->onDelete('cascade');
            
            $table->unsignedInteger('order')->default(0);
            $table->timestamps();

            $table->unique(['party_id', 'voter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'party_has_voters');

    }
};
