<?php

namespace Touch;

use Overtrue\Pinyin\Pinyin as py;

/**
 * 汉字转拼音
 */
class Pinyin
{
    
    /**
     * 汉字转化并输出拼音
     * @param string $str	所要转化拼音的汉字
     * @param boolean $utf8 汉字编码是否为utf8
     * @return string
     */
    public function output($str, $utf8 = true)
    {
        $pinyin = new py();
        return $pinyin->convert($str);
    }
}
