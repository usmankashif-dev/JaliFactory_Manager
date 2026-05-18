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
        Schema::create('machine', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
            $table->integer('machineqty');
            $table->string('machinedate');
            $table->integer('machinenumber');
            $table->integer('olenght')->nullable();
            $table->string('peice')->nullable();
            $table->integer('ogauge')->nullable();
            $table->string('cutsheet')->nullable();
            $table->string('lot')->nullable();
            $table->integer('bundlewidht')->nullable();
            $table->integer('sheetperbundle')->nullable();
            $table->string('partyorder')->nullable();
            $table->integer('jalilenght')->nullable();
            $table->integer('orderedpeices')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('machine');
    }
};
