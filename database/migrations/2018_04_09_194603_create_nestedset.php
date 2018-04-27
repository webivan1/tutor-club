<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Kalnoy\Nestedset\NestedSet;

class CreateNestedset extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('category', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });

        Schema::table('category', function (Blueprint $table) {
            NestedSet::columns($table);
        });

        Schema::table('category', function (Blueprint $table) {
            $table->integer('parent_id')
                ->nullable()
                ->references('id')
                ->on('category')
                ->onDelete('CASCADE')
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('category', function (Blueprint $table) {
            NestedSet::dropColumns($table);
        });

        Schema::table('category', function (Blueprint $table) {
            $table->integer('parent_id')->nullable()->references('id')->on('category')->onDelete('CASCADE');
        });
    }
}
