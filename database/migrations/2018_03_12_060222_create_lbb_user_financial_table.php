<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbUserFinancialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_user_financial', function (Blueprint $table) {
            $table->increments('id')->comment('自增列');
            $table->integer('financial_id')->default(0)->comment('理财ID');
            $table->string('category_name')->default('')->comment('分类名称');
            $table->string('limit_date')->default('')->comment('天数');
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->string('category_status', 5)->default('on')->comment('是否到期');
            $table->dateTime('effective_date')->comment('生效日期');
            $table->dateTime('closed_date')->comment('结束日期');
            $table->decimal('financial_num', 16, 6)->comment('预计收益');
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
        Schema::dropIfExists('lbb_user_financial');
    }
}
