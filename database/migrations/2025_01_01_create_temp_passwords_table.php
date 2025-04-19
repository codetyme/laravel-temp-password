<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('temp_passwords', function (Blueprint $table) {
            $table->id();
            $table->morphs('authenticatable');
            $table->string('temp_password');
            $table->boolean('used')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('temp_passwords');
    }
};
