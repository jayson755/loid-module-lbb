<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbBannerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_banner', function (Blueprint $table) {
            $table->increments('banner_id')->comment('自增列');
            $table->string('banner_title', 200)->default('')->comment('banner标题');
            $table->string('banner_link', 200)->default('')->comment('banner链接');
            $table->string('banner_url', 200)->default('')->comment('banner路径');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lbb_banner');
    }
}
