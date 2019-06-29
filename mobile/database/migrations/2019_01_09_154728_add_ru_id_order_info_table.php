<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRuIdOrderInfoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('order_info', 'ru_id')) {
            Schema::table('order_info', function (Blueprint $table) {
                $table->integer('ru_id')->unsigned()->default(0)->index('ru_id')->comment('商家ID（同dsc_users表user_id）');
                $table->smallInteger('main_count')->unsigned()->default(0)->index('main_count')->comment('子订单数量');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('order_info', 'ru_id')) {
            Schema::table('order_info', function (Blueprint $table) {
                $table->dropColumn('ru_id');
            });
        }
    }
}
