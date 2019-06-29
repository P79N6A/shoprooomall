<?php
/**
 * 抛出异常处理
 *
 * @param string $msg 异常消息
 * @param integer $code 异常代码 默认为0
 * @param string $exception 异常类
 *
 * @throws Exception
 */
function abort($msg, $code = 0)
{
    E($msg, $code);
}

/**
 * 记录时间（微秒）和内存使用情况
 * @param string $start 开始标签
 * @param string $end 结束标签
 * @param integer|string $dec 小数位 如果是m 表示统计内存占用
 * @return mixed
 */
function debug($start, $end = '', $dec = 6)
{
    return G($start, $end, $dec);
}

/**
 * 获取语言变量值
 * @param string $name 语言变量名
 * @param array $vars 动态变量值
 * @param string $lang 语言
 * @return mixed
 */
function lang($name = null, $value = null)
{
    return L($name, $value);
}

/**
 * 获取和设置配置参数
 * @param string|array $name 参数名
 * @param mixed $value 参数值
 * @param string $range 作用域
 * @return mixed
 */
function config($name = null, $value = null, $default = null)
{
    return C($name, $value, $default);
}

/**
 * 获取输入数据 支持默认值和过滤
 * @param string $key 获取的变量名
 * @param mixed $default 默认值
 * @param string $filter 过滤方法
 * @return mixed
 */
function input($name, $default = '', $filter = null, $datas = null)
{
    return I($name, $default, $filter, $datas);
}

/**
 * 渲染输出Widget
 * @param string $name Widget名称
 * @param array $data 传人的参数
 * @return mixed
 */
function widget($name, $data = array())
{
    return W($name, $data);
}

/**
 * 实例化模型类
 * @param string $name 模型名称
 * @return Think\Model
 */
function model($name = '')
{
    $class = '\\app\\models\\' . $name;
    if (class_exists($class)) {
        return new $class;
    }
    return false;
}

/**
 * 实例化一个没有模型文件的Model
 * @param string $name Model名称 支持指定基础模型 例如 MongoModel:User
 * @param string $tablePrefix 表前缀
 * @param mixed $connection 数据库连接信息
 * @return Think\Model
 */
function dao($name = '', $tablePrefix = '', $connection = '')
{
    return M($name, $tablePrefix, $connection);
}

/**
 * 调用模块的操作方法 参数格式 [模块/控制器/]操作
 * @param string $url 调用地址
 * @param string|array $vars 调用参数 支持字符串和数组
 * @param string $layer 要调用的控制层名称
 * @param bool $appendSuffix 是否添加类名后缀
 * @return mixed
 */
function action($url, $vars = array(), $layer = '')
{
    return R($url, $vars, $layer);
}

/**
 * Url生成
 * @param string $url 路由地址
 * @param string|array $value 变量
 * @param bool|string $suffix 前缀
 * @param bool|string $domain 域名
 * @return string
 */
function url($url = '', $vars = '', $suffix = true, $domain = false)
{
    $routes = config('URL_ROUTE_RULES');
    $rule = array_search($url, $routes);
    if ($rule !== false && $domain === false && config('url_model') == '2') {
        $rule = str_replace('\\/', '/', $rule);
        trims($rule, array('/^', '$/', '$'));
        $rule = explode('/', $rule);
        $string = '';
        foreach ($rule as $item) {
            if (0 === strpos($item, '[:')) {
                $item = substr($item, 1, -1);
            }
            if (0 === strpos($item, ':')) { // 动态变量获取
                if ($pos = strpos($item, '^')) {
                    $var = substr($item, 1, $pos - 1);
                } elseif (strpos($item, '\\')) {
                    $var = substr($item, 1, -2);
                } else {
                    $var = substr($item, 1);
                }
            }
            $string .= '/' . (($var === null) ? $item : $vars[$var]);
            if (isset($vars[$var])) unset($vars[$var]);
        }
        return U($string) . (empty($vars) ? '' : '?' . http_build_query($vars, '', '&'));
    }
    return U($url, $vars, $suffix, $domain);
}

/**
 * 缓存管理
 * @param mixed $name 缓存名称，如果为数组表示进行缓存设置
 * @param mixed $value 缓存值
 * @param mixed $options 缓存参数
 * @return mixed
 */
function cache($name, $value = '', $options = null)
{
    return S($name, $value, $options);
}

/**
 * 渲染模板输出
 * @param string $template 模板文件
 * @param array $vars 模板变量
 * @param integer $code 状态码
 * @return \think\response\View
 */
function view($template = '', $layer = '')
{
    return T($template, $layer);
}

/**
 * 获取\think\response\Json对象实例
 * @param mixed $data 返回的数据
 * @return \think\response\Json
 */
function json($data = array())
{
    return json_encode($data, PHP_VERSION >= '5.4.0' ? JSON_UNESCAPED_UNICODE : 0);
}

/**
 * 清除多个字符
 * @param $value
 * @param string $charlist
 */
function trims(&$value, $charlist = '')
{
    if (is_string($charlist)) {
        $charlist = array($charlist);
    }
    foreach ($charlist as $char) {
        $value = trim($value, $char);
    }
}

/**
 * 检查是否是微信浏览器访问
 */
function is_wechat_browser()
{
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    if (strpos($user_agent, 'micromessenger') === false) {
        return false;
    } else {
        return true;
    }
}

/**
 * 根据数组生成常量定义
 */
function array_define($array, $check = true)
{
    $content = "\n";
    foreach ($array as $key => $val) {
        $key = strtoupper($key);
        if ($check) $content .= 'defined(\'' . $key . '\') or ';
        if (is_int($val) || is_float($val)) {
            $content .= "define('" . $key . "'," . $val . ');';
        } elseif (is_bool($val)) {
            $val = ($val) ? 'true' : 'false';
            $content .= "define('" . $key . "'," . $val . ');';
        } elseif (is_string($val)) {
            $content .= "define('" . $key . "','" . addslashes($val) . "');";
        }
        $content .= "\n";
    }
    return $content;
}

/**
 * 二维数组排序
 * @param array $array 排序的数组
 * @param string $key 排序主键
 * @param string $type 排序类型 asc|desc
 * @param bool $reset 是否返回原始主键
 * @return array
 */
function array_order($array, $key, $type = 'asc', $reset = false)
{
    if (empty($array) || !is_array($array)) {
        return $array;
    }
    foreach ($array as $k => $v) {
        $keysvalue[$k] = $v[$key];
    }
    if ($type == 'asc') {
        asort($keysvalue);
    } else {
        arsort($keysvalue);
    }
    $i = 0;
    foreach ($keysvalue as $k => $v) {
        $i++;
        if ($reset) {
            $new_array[$k] = $array[$k];
        } else {
            $new_array[$i] = $array[$k];
        }
    }
    return $new_array;
}

/**
 * 获取文件或文件大小
 * @param string $directoty 路径
 * @return int
 */
function dir_size($directoty)
{
    $dir_size = 0;
    if ($dir_handle = @opendir($directoty)) {
        while ($filename = readdir($dir_handle)) {
            $subFile = $directoty . DIRECTORY_SEPARATOR . $filename;
            if ($filename == '.' || $filename == '..') {
                continue;
            } elseif (is_dir($subFile)) {
                $dir_size += dir_size($subFile);
            } elseif (is_file($subFile)) {
                $dir_size += filesize($subFile);
            }
        }
        closedir($dir_handle);
    }
    return ($dir_size);
}

//复制目录
function copy_dir($sourceDir, $aimDir)
{
    $succeed = true;
    if (!file_exists($aimDir)) {
        if (!mkdir($aimDir, 0777)) {
            return false;
        }
    }
    $objDir = opendir($sourceDir);
    while (false !== ($fileName = readdir($objDir))) {
        if (($fileName != ".") && ($fileName != "..")) {
            if (!is_dir("$sourceDir/$fileName")) {
                if (!copy("$sourceDir/$fileName", "$aimDir/$fileName")) {
                    $succeed = false;
                    break;
                }
            } else {
                copy_dir("$sourceDir/$fileName", "$aimDir/$fileName");
            }
        }
    }
    closedir($objDir);
    return $succeed;
}

/**
 * 遍历删除目录和目录下所有文件
 * @param string $dir 路径
 * @return bool
 */
function del_dir($dir)
{
    \ectouch\Util::delDir($dir);
}

/**
 * html代码输入
 */
function html_in($str)
{
    $str = htmlspecialchars($str);
    if (!get_magic_quotes_gpc()) {
        $str = addslashes($str);
    }
    return $str;
}

/**
 * html代码输出
 */
function html_out($str)
{
    if (function_exists('htmlspecialchars_decode')) {
        $str = htmlspecialchars_decode($str);
    } else {
        $str = html_entity_decode($str);
    }
    $str = stripslashes($str);
    return $str;
}

/**
 * 生成唯一数字
 */
function unique_number()
{
    return date('Ymd') . substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

/**
 * 生成随机字符串
 */
function random_str()
{
    $year_code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
    $order_sn = $year_code[intval(date('Y')) - 2010] .
        strtoupper(dechex(date('m'))) . date('d') .
        substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('d', rand(0, 99));
    return $order_sn;
}

/**
 * 数据签名认证
 * @param  array $data 被认证的数据
 * @return string       签名
 */
function data_auth_sign($data)
{
    //数据类型检测
    if (!is_array($data)) {
        $data = (array)$data;
    }
    ksort($data); //排序
    $code = http_build_query($data); //url编码并生成query字符串
    $sign = sha1($code); //生成签名
    return $sign;
}

/**
 * 写入日志文件
 * @param string $word
 */
function logResult($word = '')
{
    $word = is_array($word) ? var_export($word, true) : $word;
    $fp = fopen(ROOT_PATH . 'storage/logs/log.txt', "a");
    flock($fp, LOCK_EX);
    fwrite($fp, "执行日期：" . date("Y-m-d H:i:s", time()) . "\n" . $word . "\n");
    flock($fp, LOCK_UN);
    fclose($fp);
}

/**
 * Get the path to a versioned Elixir file.
 *
 * @param  string $file
 * @param  boolean $absolute_path = true 绝对路径
 * @return string
 */
function elixir($file, $absolute_path = false)
{
    return ($absolute_path == true ? __HOST__ : '') . __TPL__ . '/' . ltrim($file, '/');
}

/**
 * 静态资源
 * @param string $type 资源类型
 * @param string $module 资源所属模块
 * @return string
 */
function global_assets($type = 'css', $module = 'app', $mode = 0)
{
    $assets = C('ASSETS');
    $gulps = array('dist' => 'statics/');

    if (APP_DEBUG || $mode) {
        $resources = './resources/assets/';
        $paths = array();
        foreach ($assets as $key => $item) {
            foreach ($item as $vo) {
                if (substr($vo, -3) == '.js') {
                    $paths[$key]['js'][] = '<script src="' . __PUBLIC__ . '/' . $vo . '?v=' . time() . '"></script>';
                    $gulps[$key]['js'][] = $resources . $vo;
                } else if (substr($vo, -4) == '.css') {
                    $paths[$key]['css'][] = '<link href="' . __PUBLIC__ . '/' . $vo . '?v=' . time() . '" rel="stylesheet" type="text/css" />';
                    $gulps[$key]['css'][] = $resources . $vo;
                }
            }
        }
        file_put_contents(ROOT_PATH . 'storage/gulpconf.js', 'module.exports = ' . json_encode($gulps));
    } else {
        $paths[$module] = array(
            'css' => array('<link href="' . elixir('css/' . $module . '.css') . '?v=' . RELEASE . '" rel="stylesheet" type="text/css" />'),
            'js' => array('<script src="' . elixir('js/' . $module . '.js') . '?v=' . RELEASE . '"></script>')
        );
    }

    return isset($paths[$module][$type]) ? implode("\n", $paths[$module][$type]) . "\n" : '';
}

/**
 * 生成可视化编辑器
 * @param $input_name 输入框名称
 * @param string $input_value 输入框值
 * @param int $width 编辑器宽度
 * @param int $height 编辑器高度
 * @return string
 */
function create_editor($input_name, $input_value = '', $width = 600, $height = 260)
{
    static $ueditor_created = false;
    $editor = '';
    if (!$ueditor_created) {
        $ueditor_created = true;
        $editor .= '<script type="text/javascript" src="' . __PUBLIC__ . '/vendor/editor/ueditor.config.js"></script>';
        $editor .= '<script type="text/javascript" src="' . __PUBLIC__ . '/vendor/editor/ueditor.all.min.js"></script>';
    }
    $editor .= '<script id="ue_' . $input_name . '" name="' . $input_name . '" type="text/plain" style="width:' . $width . 'px;height:' . $height . 'px;">' . htmlspecialchars_decode($input_value) . '</script>';
    $editor .= '<script type="text/javascript">var ue_' . $input_name . ' = UE.getEditor("ue_' . $input_name . '");</script>';
    return $editor;
}

/**
 * 输出给定变量并结束脚本运行
 * @param $var
 * @param bool $echo
 * @param null $label
 * @param bool $strict
 */
function dd($var, $echo=true, $label=null, $strict=true){
    dump($var, $echo, $label, $strict);
    die();
}
