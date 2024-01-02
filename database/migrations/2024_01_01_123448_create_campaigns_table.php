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
        Schema::create('campaigns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('userId')->constrained('users')->onUpdate('cascade')->onDelete('cascade');
            $table->string('name');
            $table->string('description')->nullable();
            $table->double('targetAmount')->default(0);
            $table->double('amountReceived')->default(0);
            $table->string('status')->default('open');
            $table->timestamp('closeDate');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('campaigns');
    }
};
