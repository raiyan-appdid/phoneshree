<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('extras', function (Blueprint $table) {
            $table->string('android_version')->nullable();
            $table->enum('android_force_update', ['active', 'inactive'])->default('active')->nullable();
            $table->enum('ios_force_update', ['active', 'inactive'])->default('active')->nullable();
            $table->enum('maintenance', ['active', 'inactive'])->default('inactive')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('extras', function (Blueprint $table) {
            //
        });
    }
};
