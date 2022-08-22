<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateElectionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('elections', function (Blueprint $table) {
            $table->id();
            $table->integer('election_id');
            $table->string('name');
            $table->string('date');
            $table->string('ocd_id');
            $table->string('election_info_url')->nullable();
            $table->string('election_registration_url')->nullable();
            $table->string('election_registration_confirmation_url')->nullable();
            $table->string('absentee_voting_info_url')->nullable();
            $table->string('voting_location_finder_url')->nullable();
            $table->string('ballot_info_url')->nullable();
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
        Schema::dropIfExists('elections');
    }
}
