<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbFinancialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_financial', function (Blueprint $table) {
            $table->increments('financial_id')->comment('自增列');
            $table->integer('financial_category')->default(0)->comment('理财币种类型');
            $table->integer('financial_limit')->default(0)->comment('理财种类');
            $table->string('financial_status', 5)->default('on')->comment('状态');
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
        Schema::dropIfExists('lbb_financial');
    }
}
