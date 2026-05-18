<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('malls', function (Blueprint $table) {
            $table->id();
            $table->string('party');
            $table->string('input1');
            $table->string('input2');
            $table->string('input3');
            $table->string('input4');
            $table->string('input5');
            $table->string('input7');
            $table->string('lot');
            $table->string('newqty')->nullable();
            $table->string('availableqty')->nullable();
            $table->timestamps();
        });

       

    }
    
    public function down(): void
    {
        Schema::dropIfExists('orders');
        Schema::dropIfExists('malls');
    }
};
