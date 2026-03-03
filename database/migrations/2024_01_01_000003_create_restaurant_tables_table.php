<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number');
            $table->integer('capacity')->default(4);
            $table->enum('status', ['available', 'occupied', 'reserved', 'cleaning'])->default('available');
            $table->string('location')->nullable()->default('Main Hall'); // Main Hall, VIP, Outdoor
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
