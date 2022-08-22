<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContestsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->id();
            $table->integer('election_id');
            $table->boolean('data_available');
            $table->string('type')->nullable();
            $table->string('primary_party')->nullable();
            $table->string('office')->nullable();
            $table->string('level')->nullable();
            $table->string('roles')->nullable();
            $table->string('district_name')->nullable();
            $table->string('district_scope')->nullable();
            $table->string('district_id')->nullable();
            $table->string('number_elected')->nullable();
            $table->string('number_voting_for')->nullable();
            $table->string('ballot_placement')->nullable();
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
        Schema::dropIfExists('contests');
    }
}
