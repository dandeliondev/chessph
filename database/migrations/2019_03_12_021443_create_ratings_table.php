<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cph_ratings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('ncfp_id',10)->nullable();
            $table->string('fide_id',10)->nullable();
            $table->string('firstname',50)->nullable();
            $table->string('middlename',50)->nullable();
            $table->string('lastname',50)->nullable();
            $table->enum('gender',['m','f'])->nullable();
            $table->string('federation',10)->nullable();
            $table->string('title',10)->nullable();
            $table->integer('standard')->nullable();
            $table->integer('standard_prov')->nullable();
            $table->integer('standard_games')->nullable();
            $table->string('standard_k')->nullable();
            $table->integer('rapid')->nullable();
            $table->integer('rapid_prov')->nullable();
            $table->integer('rapid_games')->nullable();
            $table->integer('rapid_k')->nullable();
            $table->integer('blitz')->nullable();
            $table->integer('blitz_prov')->nullable();
            $table->integer('blitz_games')->nullable();
            $table->integer('blitz_k')->nullable();
            $table->integer('total_games')->comment('n19');
            $table->date('birthdate')->nullable();
            $table->enum('status',['1','2'])->comment('1=active,2=inactive');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cph_ratings');
    }
}
