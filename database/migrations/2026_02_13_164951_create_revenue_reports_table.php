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
        Schema::create('revenue_reports', function (Blueprint $table) {
            $table->id();
            $table->string('month');
            $table->integer('gross_cents');
            $table->decimal('rent_eur');
            $table->integer('net_cents');
            $table->integer('done_count');
            $table->integer('avg_ticket_cents');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('revenue_reports');
    }
};
