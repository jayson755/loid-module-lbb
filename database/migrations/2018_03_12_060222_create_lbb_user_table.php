<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbUserTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_user', function (Blueprint $table) {
            $table->increments('lbb_user_id')->comment('自增列');
            $table->char('lbb_user_account', 11)->comment('注册帐号-手机号');
            $table->string('lbb_user_name', 50)->default('')->comment('标签名称');
            $table->char('lbb_user_mobile', 11)->default('')->comment('预留手机号');
            $table->char('lbb_user_pwd', 32)->comment('用户密码');
            $table->char('lbb_user_paypwd', 32)->comment('支付密码');
            $table->integer('lbb_user_origin')->default('0')->comment('推广来源');
            $table->char('lbb_user_uuid', 36)->default('')->comment('uuid');
            $table->timestamps();
            $table->softDeletes();
            $table->index('lbb_user_account');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lbb_user');
    }
}
