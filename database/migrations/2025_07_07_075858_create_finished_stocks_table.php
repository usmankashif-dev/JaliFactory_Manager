<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('finished_stocks', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('machine_id');
            $table->date('date');
            $table->string('party_name');
            $table->string('khana')->nullable();
            $table->string('b_width')->nullable();
            $table->string('b_length')->nullable();
            $table->string('sheets_per_bundle')->nullable();
            $table->string('sheet_size')->nullable();
            $table->string('lot')->nullable();
            $table->integer('bundle');
            $table->string('packed_by')->nullable();
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('finished_stocks');
    }
};
