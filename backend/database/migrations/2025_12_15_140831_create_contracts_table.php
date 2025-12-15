<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['electricity', 'gas', 'mobile']);
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->timestamps();

            // Constraint to prevent duplicate contract types per client
            $table->unique(['client_id', 'type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
