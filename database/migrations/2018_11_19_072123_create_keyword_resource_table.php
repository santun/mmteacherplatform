<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateKeywordResourceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('keyword_resource', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('keyword_id')->nullable();
			$table->unsignedInteger('resource_id')->nullable();
			$table->unsignedInteger('user_id')->nullable();
			$table->string('provided_by')->nullable();
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
        Schema::dropIfExists('keyword_resource');
    }
}
