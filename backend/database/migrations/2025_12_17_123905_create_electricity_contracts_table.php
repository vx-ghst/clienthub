<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('electricity_contracts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('contract_id')
                ->constrained()
                ->cascadeOnDelete()
                ->unique();

            $table->string('ean', 18)->unique();
            $table->decimal('power_kw', 5, 2);

            $table->string('tariff_code');
            $table->decimal('tariff_price_kwh', 10, 4);
            $table->decimal('tariff_subscription', 10, 2);

            $table->decimal('advance_amount', 10, 2);
            $table->enum('advance_frequency', ['monthly', 'quarterly']);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('electricity_contracts');
    }
};
