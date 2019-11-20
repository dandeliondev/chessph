<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCphRatingsAddF960 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cph_ratings', function (Blueprint $table) {
            //
			$table->integer('f960_k')->nullable()->after('blitz_k');
			$table->integer('f960_games')->nullable()->after('blitz_k');
			$table->integer('f960_prov')->nullable()->after('blitz_k');
			$table->integer('f960_prev')->nullable()->after('blitz_k');
			$table->integer('f960')->nullable()->after('blitz_k');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cph_ratings', function (Blueprint $table) {
            //
        });
    }
}
