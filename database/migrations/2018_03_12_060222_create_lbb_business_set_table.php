<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbBusinessSetTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_business_set', function (Blueprint $table) {
            $table->increments('param_id')->comment('自增列');
            $table->string('param_name', 100)->default('')->comment('参数名字');
            $table->string('param_value', 300)->default('')->comment('参数值');
            $table->string('param_type', 20)->default('')->comment('参数类型');
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
        Schema::dropIfExists('lbb_business_set');
    }
}
