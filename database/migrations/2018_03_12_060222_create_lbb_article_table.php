<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_article', function (Blueprint $table) {
            $table->increments('article_id')->comment('自增列');
            $table->string('article_title', 200)->default('')->comment('文章标题');
            $table->string('article_category', 50)->default('notice')->comment('文章类型');
            $table->longText('article_content')->comment('文章内容');
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
        Schema::dropIfExists('lbb_article');
    }
}
