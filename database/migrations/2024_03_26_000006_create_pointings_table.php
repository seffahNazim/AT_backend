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
        Schema::create('pointings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('employe_id');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->date('date')->nullable();
            $table->enum('statut', ['present', 'absent', 'emergency' , 'inProgress'])->nullable();

            $table->foreign('employe_id')->references('id')->on('employes')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pointings');
    }
};
