<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLbbStoreChangeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lbb_store_change', function (Blueprint $table) {
            $table->integer('user_id')->default(0)->comment('用户ID');
            $table->integer('store_id')->default(0)->comment('仓库ID');
            $table->integer('store_category')->default(0)->comment('类型');
            $table->decimal('last_num', 16, 6)->default(0)->comment('剩余数量');
            $table->timestamps();
            $table->softDeletes();
            $table->index('user_id');
            $table->index('store_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('lbb_store_change');
    }
}
