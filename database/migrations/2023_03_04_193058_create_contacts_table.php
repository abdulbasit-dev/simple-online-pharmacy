<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string("name",60);
            $table->string("email",60);
            $table->string("subject");
            $table->longText("message");
            $table->timestamps();
            $table->softDeletes();
        });
    }
};
