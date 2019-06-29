<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ConfigModuleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->shopConfig();
    }

    private function shopConfig()
    {
        $result = DB::table('shop_config')->where('code', 'cashier_Settlement')->first();
        if (empty($result)) {
            // 默认数据
            $rows = [
                [
                    'parent_id' => '942',
                    'code' => 'cashier_Settlement',
                    'type' => 'hidden',
                    'value' => '1'
                ]
            ];
            DB::table('shop_config')->insert($rows);
        }
    }
}