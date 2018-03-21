<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbStoreWithdrawTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_store_withdraw', function (Blueprint $table) {
            $table->increments('withdraw_id')->comment('自增列');
            $table->integer('store_id')->default(0)->comment('仓库ID');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->string('withdraw_url', 300)->default('')->comment('提现url');
            $table->decimal('withdraw_num', 16, 6)->default(0)->comment('数量');
            $table->integer('store_category')->default(0)->comment('类型');
            $table->boolean('withdraw_status')->default(0)->comment('提现操作状态0:未操作；1：已操作');
            $table->timestamps();
            $table->softDeletes();
            $table->index('store_id');
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
        Schema::dropIfExists('lbb_store_withdraw');
    }
}
