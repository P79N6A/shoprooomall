<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MobileModuleSeeder extends Seeder
{

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->shopConfig();
        $this->touchAdPosition();
    }

    private function shopConfig()
    {
        $result = DB::table('shop_config')->where('code', 'wap_category')->first();
        if (empty($result)) {
            // 默认数据
            $rows = [
                [
                    'parent_id' => '9',
                    'code' => 'wap_category',
                    'type' => 'select',
                    'store_range' => '0,1',
                    'sort_order' => '1',
                    'value' => '0',
                ]
            ];
            DB::table('shop_config')->insert($rows);
        }
    }

    /**
     * 秒杀广告位
     * @return
     */
    private function touchAdPosition()
    {
        // 删除旧数据
        $result = DB::table('touch_ad_position')->where('ad_type', 'seckill')->first();
        if (!empty($result)) {
            // 删除秒杀广告位
            DB::table('touch_ad_position')->where('ad_type', 'seckill')->delete();
            // 删除秒杀广告位下的广告
            DB::table('touch_ad')->where('position_id', $result->position_id)->delete();
        }

        $result = DB::table('touch_ad_position')->where('ad_type', 'seckill')->get();
        $result = $result->toArray();
        if (empty($result)) {
            // 默认广告位数据
            $row = [
                'position_name' => '秒杀-banner广告位',
                'ad_width' => '360',
                'ad_height' => '168',
                'position_style' => '{foreach $ads as $ad}<div class="swiper-slide">{$ad}</div>{/foreach}' . "\n" . '',
                'theme' => 'ecmoban_dsc2017',
                'tc_type' => 'banner',
                'ad_type' => 'seckill'
            ];
            $position_id = DB::table('touch_ad_position')->insertGetId($row);

            if ($position_id) {
                // 默认广告数据
                $rows = [
                    [
                        'position_id' => $position_id,
                        'ad_name' => '秒杀-banner001',
                        'ad_code' => '1490123276201426626.jpg',
                        'start_time' => '1481585927',
                        'end_time' => '1577229853',
                        'enabled' => '1',
                    ],
                    [
                        'position_id' => $position_id,
                        'ad_name' => '秒杀-banner002',
                        'ad_code' => '1490123436237080312.jpg',
                        'start_time' => '1481585927',
                        'end_time' => '1577229853',
                        'enabled' => '1',
                    ],
                    [
                        'position_id' => $position_id,
                        'ad_name' => '秒杀-banner003',
                        'ad_code' => '1490123319351624402.jpg',
                        'start_time' => '1481585927',
                        'end_time' => '1577229853',
                        'enabled' => '1',
                    ]
                ];
                DB::table('touch_ad')->insert($rows);
            }
        }
    }
}