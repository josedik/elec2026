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
        Schema::create('parties', function (Blueprint $table) {
            $table->id();
            $table->string('code',6)->unique();
            $table->string('name',100);
            $table->string('acronym',10)->unique();
            $table->unsignedBigInteger('voter_id')->nullable();
            $table->foreign('voter_id')->references('id')->on('voters')->onDelete('set null');
            $table->integer('voters');
            $table->boolean('active')->default(true);
            $table->string('logo_path', 255)->nullable()->default('logo.png');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parties');
    }
};
