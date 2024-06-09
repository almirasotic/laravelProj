<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::rename('courses', 'themes');

        Schema::table('themes', function (Blueprint $table) {
            $table->dropColumn(['tags', 'duration', 'price']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::rename('themes', 'courses');

        Schema::table('courses', function (Blueprint $table) {
            $table->string('tags');
            $table->decimal('duration');
            $table->float('price');
        });
    }
};
