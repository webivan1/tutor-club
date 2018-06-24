<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tutor_profile', function (Blueprint $table) {
            $table->removeColumn('file_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->integer('file_id')->nullable()->references('id')->on('files')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tutor_profile', function (Blueprint $table) {
            $table->integer('file_id')->nullable()->references('id')->on('files')->onDelete('SET NULL');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('file_id');
        });
    }
}
