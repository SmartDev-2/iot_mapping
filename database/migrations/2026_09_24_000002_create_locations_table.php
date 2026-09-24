<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration { public function up(): void { Schema::create('locations',function(Blueprint $table){$table->id();$table->string('device_id',50);$table->decimal('latitude',10,7);$table->decimal('longitude',10,7);$table->dateTime('timestamp');$table->timestamps();$table->index(['device_id','timestamp']);$table->foreign('device_id')->references('device_id')->on('devices')->cascadeOnDelete();}); } public function down(): void { Schema::dropIfExists('locations'); } };
