<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceYearTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_year', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('resource_id')->comment('ID of Resource record');
            $table->unsignedInteger('year_id')->nullable()->comment('ID of Year record');
            $table->index(["resource_id"], 'fk_resource_id_idx');
            $table->index(["year_id"], 'fk_year_id_idx');
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
        Schema::dropIfExists('resource_year');
    }
}
