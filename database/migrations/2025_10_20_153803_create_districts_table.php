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
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('code',6)->unique();
            $table->string('name', 100);
            $table->double('population')->nullable();
            $table->decimal('area', 10, 2)->nullable();
            $table->foreignId('province_id')->constrained()->onDelete('cascade');
            $table->integer('escanios')->default(5);
            $table->string('capital', 20)->default('not');
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('districts');
    }
};
