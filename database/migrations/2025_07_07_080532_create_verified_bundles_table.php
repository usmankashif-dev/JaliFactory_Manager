<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('verified_bundles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_id');
            $table->date('date');
            $table->integer('sheets_per_bundle');
            $table->integer('bundle_count');
            $table->string('packed_by')->nullable();
            $table->timestamps();
            // Add all relevant columns from bundle_history and finished_stocks as needed
            $table->string('party_name')->nullable();
            $table->string('lot')->nullable();
            $table->string('cutsheet')->nullable();
            $table->string('peice')->nullable();
            $table->string('widht')->nullable();
            $table->string('jalilenght')->nullable();
            $table->string('machine_id')->nullable();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('verified_bundles');
    }
};
