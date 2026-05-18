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
Schema::create('orders', function (Blueprint $table) {
    $table->id();
    $table->foreignId('mall_id')->nullable()->constrained('malls')->onDelete('set null');
    $table->string('orderedqty');
    $table->string('olenght');
    $table->string('peice');
    $table->string('ogauge');
    $table->string('dateno');
    $table->string('gauge');
    $table->string('widht');
    $table->string('lenght');
    $table->string('lot');
    $table->string('rem');
    $table->string('orgsheet');
    $table->string('cutsheet');
    $table->string('bundlewidht');
    $table->string('sheetperbundle');
    $table->string('partyorder');
    $table->string('cutsheetqty');
    $table->string('jalilenght');
    $table->string('orderedpeices');
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
