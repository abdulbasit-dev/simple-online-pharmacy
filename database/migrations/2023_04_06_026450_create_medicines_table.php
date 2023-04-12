<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('medicines', function (Blueprint $table) {
            $table->id();
            $table->foreignId("type_id")->constrained("types");
            $table->foreignId("origin_id")->constrained("origins");
            $table->string('name')->unique();
            $table->string('slug');
            $table->string('price');
            $table->string('image')->nullable();
            $table->integer('quantity');
            $table->text("description");
            $table->date("manufacture_at");
            $table->date("expire_at");
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('medicines');
    }
};
