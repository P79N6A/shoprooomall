<?php
namespace ectouch;

class Scws4
{
    private $pscws;
    private $charset = 'utf-8';    // 是否识别名字
    private $ignore = true;    // 是否忽略标点
    private $dictPath = false;    // 是否查看统计结果
    private $rulePath = false;    // 是否查看统计结果
    private $duality = true;    // 设置是否将闲散文字自动以二字分词法聚合

    public function __construct()
    {
        $pscesPath = dirname(ROOT_PATH) . '/includes/pscws4/';
        if (!file_exists($pscesPath . 'pscws4.php')) {
            abort('pscws4 类文件不存在');
        }
        require_once $pscesPath . 'pscws4.php';
        $this->dictPath = $pscesPath . 'etc/dict.utf8.xdb';
        $this->rulePath = $pscesPath . 'etc/rules.utf8.ini';
        $this->pscws = new \PSCWS4($this->charset);
    }

    public function segmentate($text, $return_array = false, $top = 5, $sep = ',')
    {
        //参数设置
        $this->pscws->set_charset($this->charset);   //设置字符集
        $this->pscws->set_dict($this->dictPath);  //设置字典文件
        $this->pscws->set_rule($this->rulePath);  //设置新词规则集
        $this->pscws->set_ignore($this->ignore);   //设置返回标点符号
        $this->pscws->set_multi(0);   //设置结果是否复合分割
        $this->pscws->set_duality($this->duality);   //设置是否将闲散文字自动以二字分词法聚合
        $this->pscws->send_text($text);   //发送文本

        $result = array();
        while ($ret = $this->pscws->get_result()) {
            foreach ($ret as $v) {
                if ($v['len'] == 1 && $v['word'] == "\r")
                    continue;
                if ($v['len'] == 1 && $v['word'] == "\n")
                    $result[] = '<br/>';
                else
                    $result[] = $v['word'];
            }
        }
        if (true === $return_array) {
            $result = implode(',', $result);
        }
        return $result;
    }

}