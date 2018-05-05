<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbMobileCodeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_mobile_code', function (Blueprint $table) {
            $table->increments('mobile_code_id')->comment('自增列');
            $table->string('mobile_code_mobile', 11)->default('')->comment('手机号');
            $table->string('mobile_code_type', 50)->default('')->comment('验证码类型');
            $table->string('mobile_code_str', 6)->default('')->comment('验证码');
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
        Schema::dropIfExists('lbb_mobile_code');
    }
}
