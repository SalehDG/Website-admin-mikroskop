<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void { Schema::table('serial_codes', function (Blueprint $table) { $table->string('device_id_hash', 64)->nullable()->index(); $table->string('device_name', 120)->nullable(); $table->timestamp('last_validated_at')->nullable(); }); }
    public function down(): void { Schema::table('serial_codes', function (Blueprint $table) { $table->dropColumn(['device_id_hash', 'device_name', 'last_validated_at']); }); }
};
