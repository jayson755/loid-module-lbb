<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_category', function (Blueprint $table) {
            $table->increments('category_id')->comment('自增列');
            $table->string('category_name', 20)->default('')->comment('理财币种');
            $table->string('category_url', 200)->default('')->comment('理财币种url');
            $table->string('category_status', 5)->default('on')->comment('状态');
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
        Schema::dropIfExists('lbb_category');
    }
}
