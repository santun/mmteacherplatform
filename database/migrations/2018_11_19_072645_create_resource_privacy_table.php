<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcePrivacyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_privacy', function (Blueprint $table) {
            $table->increments('id');
			$table->unsignedInteger('resource_id')->comment('ID of Resource record');			
			$table->unsignedInteger('role_id')->nullable()->comment('ID of Role record');
			$table->string('user_type')->nullable()->comment('User type. See type column in users table. Options: Public, Teacher Educator Student Teacher');
			//$table->unsignedInteger('user_id')->nullable();
			$table->index(["resource_id"], 'fk_resource_id_idx');
            $table->index(["user_type"], 'fk_user_type');
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
        Schema::dropIfExists('resource_privacy');
    }
}
