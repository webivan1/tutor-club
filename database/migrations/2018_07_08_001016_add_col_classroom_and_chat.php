<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColClassroomAndChat extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('classroom', function (Blueprint $table) {
            $table->integer('advert_prices_id')->index();
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->integer('classroom_id')->on('classroom')->references('id')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('classroom', function (Blueprint $table) {
            $table->dropColumn('advert_prices_id');
        });

        Schema::table('messages', function (Blueprint $table) {
            $table->dropColumn('classroom_id');
        });
    }
}
