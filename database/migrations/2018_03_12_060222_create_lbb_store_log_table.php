<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbStoreLogTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_store_log', function (Blueprint $table) {
            $table->increments('log_id')->comment('自增列');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('store_id')->default(0)->comment('仓库ID');
            $table->integer('store_category')->default(0)->comment('类型');
            $table->string('flag', 20)->default('none')->comment('变动类型');
            $table->decimal('store_num', 16, 6)->default(0)->comment('变动数量');
            $table->decimal('last_num', 16, 6)->default(0)->comment('剩余数量');
            $table->integer('origin_store_id')->default(0)->comment('来源仓库ID');
            $table->integer('origin_user_id')->default(0)->comment('来源用户ID');
            $table->text('store_data')->comment('数据');
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lbb_store_log');
    }
}
