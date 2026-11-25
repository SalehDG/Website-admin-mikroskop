<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('serial_codes', function (Blueprint $table) {
            $table->id();
            $table->string('code', 32)->unique();
            $table->enum('status', ['unused', 'used'])->default('unused')->index();
            $table->string('customer_name')->nullable();
            $table->string('notes', 160)->nullable();
            $table->timestamp('used_at')->nullable()->index();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('serial_codes'); }
};
