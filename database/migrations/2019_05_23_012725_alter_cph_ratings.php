<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCphRatings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
		Schema::table('cph_ratings', function($table) {

			$table->integer('standard_prev')->nullable()->after('standard');
			$table->integer('rapid_prev')->nullable()->after('rapid');
			$table->integer('blitz_prev')->nullable()->after('blitz');
			$table->integer('birthyear')->nullable()->after('birthdate');
		});


    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
