<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courses', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->text('description');
            $table->string('cover_image');
            $table->string('url_link')->nullable();
            $table->unsignedInteger('downloadable_option');
            $table->unsignedInteger('course_category_id');
            $table->unsignedInteger('level_id');
            $table->tinyInteger('approval_status')->nullable();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('approved_by')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->boolean('isRequested')->default(0);
            $table->boolean('is_published')->default(0);
            $table->boolean('allow_edit')->default(0);
            $table->boolean('is_locked')->default(0);
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
        Schema::dropIfExists('courses');
    }
}
