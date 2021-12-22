<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->text('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('resource_format')->nullable();
            $table->string('strand')->nullable();
            $table->string('sub_strand')->nullable();
            //$table->string('author')->nullable();
            $table->string('author')->nullable()->comment('User can type multiple author names');
            //$table->string('rating')->nullable();
            $table->float('rating')->nullable()->comment('Pre-calculated average rating. See also ratings table');
            //$table->string('publisher')->nullable();
            $table->string('publisher')->nullable()->comment('User can type multiple publisher names');
            $table->string('publishing_year')->nullable();

            //$table->boolean('published')->default(0);
            $table->boolean('published')->default(0)->comment('Default - 0');
            //$table->tinyInteger('approved')->default(0);
            $table->boolean('approved')->default(0)->comment('Default - 0');
            //$table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('user_id')->comment('ID of User record');
            //$table->unsignedInteger('license_id')->nullable();
            $table->unsignedInteger('license_id')->nullable()->comment('ID of License record');
            $table->boolean('is_featured')->default(0)->comment('Default - 0');
            $table->string('suitable_for_ec_year')->nullable()->comment('Year 1, Year 2, Year 3, Year 4');
            //$table->string('suitable_for_ec_year', 50)->nullable()->comment('Year 1, Year 2, Year 3, Year 4');
            $table->string('url')->nullable();
            $table->unsignedInteger('total_page_views')->default(0);
            $table->unsignedInteger('total_downloads')->default(0);
            $table->boolean('allow_feedback')->default(1)->comment('1 - Allow, 0 - Not allow');
            $table->boolean('allow_download')->default(1)->comment('1 - Allow, 0 - Not allow');
            $table->softDeletes();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();

            $table->index(['license_id'], 'fk_license_id_idx');
            $table->index(['user_id'], 'fk_user_id_idx');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('resources');
    }
}
