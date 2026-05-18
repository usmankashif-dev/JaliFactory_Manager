<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('bundle_history', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id');
            $table->date('date');
            $table->integer('sheets_per_bundle');
            $table->integer('bundle_count');
            $table->timestamps();
        });
    }
    public function down()
    {
        Schema::dropIfExists('bundle_history');
    }
};
