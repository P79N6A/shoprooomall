<?php
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;
//SHOPROOO
function sub_str($str, $length = 0, $append = true, $start = 0)
{
	$str = trim($str);
	$strlength = strlen($str);
	if ($length == 0 || $strlength <= $length) {
		return $str;
	}
	else if ($length < 0) {
		$length = $strlength + $length;

		if ($length < 0) {
			$length = $strlength;
		}
	}

	if (function_exists('mb_substr')) {
		$newstr = mb_substr($str, $start, $length, CHARSET);
	}
	else if (function_exists('iconv_substr')) {
		$newstr = iconv_substr($str, $start, $length, CHARSET);
	}
	else {
		$newstr = substr($str, $start, $length);
	}

	if ($append && $str != $newstr) {
		$newstr .= '...';
	}

	return $newstr;
}

function real_ip()
{
	static $realip;

	if ($realip !== NULL) {
		return $realip;
	}

	if (isset($_SERVER)) {
		if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);

			foreach ($arr as $ip) {
				$ip = trim($ip);

				if ($ip != 'unknown') {
					$realip = $ip;
					break;
				}
			}
		}
		else if (isset($_SERVER['HTTP_CLIENT_IP'])) {
			$realip = $_SERVER['HTTP_CLIENT_IP'];
		}
		else if (isset($_SERVER['REMOTE_ADDR'])) {
			$realip = $_SERVER['REMOTE_ADDR'];
		}
		else {
			$realip = '0.0.0.0';
		}
	}
	else if (getenv('HTTP_X_FORWARDED_FOR')) {
		$realip = getenv('HTTP_X_FORWARDED_FOR');
	}
	else if (getenv('HTTP_CLIENT_IP')) {
		$realip = getenv('HTTP_CLIENT_IP');
	}
	else {
		$realip = getenv('REMOTE_ADDR');
	}

	preg_match('/[\\d\\.]{7,15}/', $realip, $onlineip);
	$realip = !empty($onlineip[0]) ? $onlineip[0] : '0.0.0.0';
	return $realip;
}

function str_len($str)
{
	$length = strlen(preg_replace('/[\\x00-\\x7F]/', '', $str));

	if ($length) {
		return strlen($str) - $length + intval($length / 3) * 2;
	}
	else {
		return strlen($str);
	}
}

function get_crlf()
{
	if (stristr($_SERVER['HTTP_USER_AGENT'], 'Win')) {
		$the_crlf = '\\r\\n';
	}
	else if (stristr($_SERVER['HTTP_USER_AGENT'], 'Mac')) {
		$the_crlf = '\\r';
	}
	else {
		$the_crlf = '\\n';
	}

	return $the_crlf;
}

function send_sms($mobile, $send_time = '', $content)
{
    require ROOT_PATH . '/vendor/autoload.php';
    AlibabaCloud::accessKeyClient('LTAIiv8UtzC20C8a', 'CqpcLB71QC8cTzqyklLlluKzGzvhiC')
    ->regionId('ap-southeast-1')
    ->asGlobalClient();
    try {
        $result = AlibabaCloud::rpcRequest()
        ->product('Dysmsapi')
        ->host('dysmsapi.ap-southeast-1.aliyuncs.com')
        ->version('2018-05-01')
        ->action('SendMessageToGlobe')
        ->method('POST')
        ->options([
            'query' => [
                "To" => '61'.$mobile,
                // "From" => "1234567890",
                "Message" => $content,
            ],
        ])
        ->request();
        
        if ($result['ResponseCode'] == 'OK') {
            return true;
        }
        else {
            return $result['ResponseDescription'];
        }
    } catch (ClientException $e) {
        return $e->getErrorMessage() . PHP_EOL;
    } catch (ServerException $e) {
        return $e->getErrorMessage() . PHP_EOL;
    }
	/*$sms_type = array('ihuyi', 'alidayu', 'aliyun', 'dscsms');
	$config = array(
		'driver'       => 'sms',
		'driverConfig' => array(
			'sms_type' => $sms_type[$GLOBALS['_CFG']['sms_type']],
			'ihuyi'    => array('sms_name' => $GLOBALS['_CFG']['sms_ecmoban_user'], 'sms_password' => $GLOBALS['_CFG']['sms_ecmoban_password']),
			'alidayu'  => array('ali_appkey' => $GLOBALS['_CFG']['ali_appkey'], 'ali_secretkey' => $GLOBALS['_CFG']['ali_secretkey']),
			'aliyun'   => array('access_key_id' => $GLOBALS['_CFG']['access_key_id'], 'access_key_secret' => $GLOBALS['_CFG']['access_key_secret']),
			'dscsms'   => array('app_key' => $GLOBALS['_CFG']['dsc_appkey'], 'app_secret' => $GLOBALS['_CFG']['dsc_appsecret'])
			)
		);
	$sms = new \App\Channels\Send($config);

	if ($sms->push($mobile, $send_time, $content) === true) {
		return true;
	}
	else {
		return $sms->getError();
	}*/
}

function send_mail($name, $email, $subject, $content, $type = 0, $notification = false)
{
    require(ROOT_PATH . '/mobile/app/Libraries/Mailin.php');
    $mailin = new Mailin('https://api.sendinblue.com/v2.0','0aRjkQ1YdHLgpPwn');
    $data = array( "to" => array($email=>"to whom!"),
        "from" => array("services@shoprooo.com.au","Shoprooo"),
        "subject" => $subject,
        "text" => "This is the text",
        "html" => $content,
        "attachment" => array(),
        "headers" => array("Content-Type"=> "text/html; charset=iso-8859-1","X-param1"=> "value1", "X-param2"=> "value2","X-Mailin-custom"=>"my custom value", "X-Mailin-IP"=> "102.102.1.2", "X-Mailin-Tag" => "My tag"),
        "inline_image" => array()
    );
    
    $res = $mailin->send_email($data);
    if ($res['code'] != 'success') {
        $GLOBALS['err']->add($GLOBALS['_LANG']['sendemail_false']);
        return false;
    }
    else {
        return true;
    }
	/*if ($GLOBALS['_CFG']['mail_charset'] != CHARSET) {
		$name = ecs_iconv(CHARSET, $GLOBALS['_CFG']['mail_charset'], $name);
		$subject = ecs_iconv(CHARSET, $GLOBALS['_CFG']['mail_charset'], $subject);
		$content = ecs_iconv(CHARSET, $GLOBALS['_CFG']['mail_charset'], $content);
		$shop_name = ecs_iconv(CHARSET, $GLOBALS['_CFG']['mail_charset'], $GLOBALS['_CFG']['shop_name']);
	}

	$charset = $GLOBALS['_CFG']['mail_charset'];
	if ($GLOBALS['_CFG']['mail_service'] == 0 && function_exists('mail')) {
		$content_type = $type == 0 ? 'Content-Type: text/plain; charset=' . $charset : 'Content-Type: text/html; charset=' . $charset;
		$headers = array();
		$headers[] = 'From: "' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?=' . '" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
		$headers[] = $content_type . '; format=flowed';

		if ($notification) {
			$headers[] = 'Disposition-Notification-To: ' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?=' . '" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
		}

		$res = @mail($email, '=?' . $charset . '?B?' . base64_encode($subject) . '?=', $content, implode('
', $headers));

		if (!$res) {
			$GLOBALS['err']->add(L('sendemail_false'));
			return false;
		}
		else {
			return true;
		}
	}
	else {
		$content_type = $type == 0 ? 'Content-Type: text/plain; charset=' . $charset : 'Content-Type: text/html; charset=' . $charset;
		$content = base64_encode($content);
		$headers = array();
		$headers[] = 'Date: ' . gmdate('D, j M Y H:i:s') . ' +0000';
		$headers[] = 'To: "' . '=?' . $charset . '?B?' . base64_encode($name) . '?=' . '" <' . $email . '>';
		$headers[] = 'From: "' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?=' . '" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
		$headers[] = 'Subject: ' . '=?' . $charset . '?B?' . base64_encode($subject) . '?=';
		$headers[] = $content_type . '; format=flowed';
		$headers[] = 'Content-Transfer-Encoding: base64';
		$headers[] = 'Content-Disposition: inline';

		if ($notification) {
			$headers[] = 'Disposition-Notification-To: ' . '=?' . $charset . '?B?' . base64_encode($shop_name) . '?=' . '" <' . $GLOBALS['_CFG']['smtp_mail'] . '>';
		}

		$params['host'] = $GLOBALS['_CFG']['smtp_host'];
		$params['port'] = $GLOBALS['_CFG']['smtp_port'];
		$params['user'] = $GLOBALS['_CFG']['smtp_user'];
		$params['pass'] = $GLOBALS['_CFG']['smtp_pass'];
		if (empty($params['host']) || empty($params['port'])) {
			$GLOBALS['err']->add(L('smtp_setting_error'));
			return false;
		}
		else {
			if (!function_exists('fsockopen')) {
				$GLOBALS['err']->add(L('disabled_fsockopen'));
				return false;
			}

			static $smtp;
			$send_params['recipients'] = $email;
			$send_params['headers'] = $headers;
			$send_params['from'] = $GLOBALS['_CFG']['smtp_mail'];
			$send_params['body'] = $content;

			if (!isset($smtp)) {
				$smtp = new \App\Libraries\Smtp($params);
			}

			if ($smtp->connect() && $smtp->send($send_params)) {
				return true;
			}
			else {
				$err_msg = $smtp->error_msg();

				if (empty($err_msg)) {
					$GLOBALS['err']->add('Unknown Error');
				}
				else if (strpos($err_msg, 'Failed to connect to server') !== false) {
					$GLOBALS['err']->add(sprintf(L('smtp_connect_failure'), $params['host'] . ':' . $params['port']));
				}
				else if (strpos($err_msg, 'AUTH command failed') !== false) {
					$GLOBALS['err']->add(L('smtp_login_failure'));
				}
				else if (strpos($err_msg, 'bad sequence of commands') !== false) {
					$GLOBALS['err']->add(L('smtp_refuse'));
				}
				else {
					$GLOBALS['err']->add($err_msg);
				}

				return false;
			}
		}
	}*/
}

function gd_version()
{
	return \App\Libraries\Image::gd_version();
}

function file_mode_info($file_path)
{
	if (!file_exists($file_path)) {
		return false;
	}

	$mark = 0;

	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		$test_file = $file_path . '/cf_test.txt';

		if (is_dir($file_path)) {
			$dir = @opendir($file_path);

			if ($dir === false) {
				return $mark;
			}

			if (@readdir($dir) !== false) {
				$mark ^= 1;
			}

			@closedir($dir);
			$fp = @fopen($test_file, 'wb');

			if ($fp === false) {
				return $mark;
			}

			if (@fwrite($fp, 'directory access testing.') !== false) {
				$mark ^= 2;
			}

			@fclose($fp);
			@unlink($test_file);
			$fp = @fopen($test_file, 'ab+');

			if ($fp === false) {
				return $mark;
			}

			if (@fwrite($fp, 'modify test.') !== false) {
				$mark ^= 4;
			}

			@fclose($fp);

			if (@rename($test_file, $test_file) !== false) {
				$mark ^= 8;
			}

			@unlink($test_file);
		}
		else if (is_file($file_path)) {
			$fp = @fopen($file_path, 'rb');

			if ($fp) {
				$mark ^= 1;
			}

			@fclose($fp);
			$fp = @fopen($file_path, 'ab+');
			if ($fp && @fwrite($fp, '') !== false) {
				$mark ^= 6;
			}

			@fclose($fp);

			if (@rename($test_file, $test_file) !== false) {
				$mark ^= 8;
			}
		}
	}
	else {
		if (@is_readable($file_path)) {
			$mark ^= 1;
		}

		if (@is_writable($file_path)) {
			$mark ^= 14;
		}
	}

	return $mark;
}

function log_write($arg, $file = '', $line = '')
{
	if ((DEBUG_MODE & 4) != 4) {
		return NULL;
	}

	$str = '
-- ' . date('Y-m-d H:i:s') . ' --------------------------------------------------------------
';
	$str .= 'FILE: ' . $file . '
LINE: ' . $line . '
';

	if (is_array($arg)) {
		$str .= '$arg = array(';

		foreach ($arg as $val) {
			foreach ($val as $key => $list) {
				$str .= '\'' . $key . '\' => \'' . $list . '\'
';
			}
		}

		$str .= ')
';
	}
	else {
		$str .= $arg;
	}

	file_put_contents(ROOT_PATH . 'storage/logs/log.txt', $str);
}

function make_dir($folder)
{
	$reval = false;

	if (!file_exists($folder)) {
		@umask(0);
		preg_match_all('/([^\\/]*)\\/?/i', $folder, $atmp);
		$base = $atmp[0][0] == '/' ? '/' : '';

		foreach ($atmp[1] as $val) {
			if ('' != $val) {
				$base .= $val;
				if ('..' == $val || '.' == $val) {
					$base .= '/';
					continue;
				}
			}
			else {
				continue;
			}

			$base .= '/';

			if (!file_exists($base)) {
				if (@mkdir(rtrim($base, '/'), 511)) {
					@chmod($base, 511);
					$reval = true;
				}
			}
		}
	}
	else {
		$reval = is_dir($folder);
	}

	clearstatcache();
	return $reval;
}

function gzip_enabled()
{
	static $enabled_gzip;

	if ($enabled_gzip === NULL) {
		$enabled_gzip = $GLOBALS['_CFG']['enable_gzip'] && function_exists('ob_gzhandler');
	}

	return $enabled_gzip;
}

function addslashes_deep($value)
{
	if (empty($value)) {
		return $value;
	}
	else {
		return is_array($value) ? array_map('addslashes_deep', $value) : addslashes($value);
	}
}

function addslashes_deep_obj($obj)
{
	if (is_object($obj) == true) {
		foreach ($obj as $key => $val) {
			$obj->$key = addslashes_deep($val);
		}
	}
	else {
		$obj = addslashes_deep($obj);
	}

	return $obj;
}

function stripslashes_deep($value)
{
	if (empty($value)) {
		return $value;
	}
	else {
		return is_array($value) ? array_map('stripslashes_deep', $value) : stripslashes($value);
	}
}

function make_semiangle($str)
{
	$arr = array('０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4', '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9', 'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E', 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J', 'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T', 'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y', 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd', 'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n', 'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x', 'ｙ' => 'y', 'ｚ' => 'z', '（' => '(', '）' => ')', '〔' => '[', '〕' => ']', '【' => '[', '】' => ']', '〖' => '[', '〗' => ']', '“' => '[', '”' => ']', '‘' => '[', '’' => ']', '｛' => '{', '｝' => '}', '《' => '<', '》' => '>', '％' => '%', '＋' => '+', '—' => '-', '－' => '-', '～' => '-', '：' => ':', '。' => '.', '、' => ',', '，' => '.', '、' => '.', '；' => ',', '？' => '?', '！' => '!', '…' => '-', '‖' => '|', '”' => '"', '’' => '`', '‘' => '`', '｜' => '|', '〃' => '"', '　' => ' ');
	return strtr($str, $arr);
}

function compile_str($str)
{
	$arr = array('<' => '＜', '>' => '＞', '"' => '”', '\'' => '’');
	return strtr($str, $arr);
}

function check_file_type($filename, $realname = '', $limit_ext_types = '')
{
	if ($realname) {
		$extname = strtolower(substr($realname, strrpos($realname, '.') + 1));
	}
	else {
		$extname = strtolower(substr($filename, strrpos($filename, '.') + 1));
	}

	if ($limit_ext_types && stristr($limit_ext_types, '|' . $extname . '|') === false) {
		return '';
	}

	$str = $format = '';
	$file = @fopen($filename, 'rb');

	if ($file) {
		$str = @fread($file, 1024);
		@fclose($file);
	}
	else if (stristr($filename, ROOT_PATH) === false) {
		if ($extname == 'jpg' || $extname == 'jpeg' || $extname == 'gif' || $extname == 'png' || $extname == 'doc' || $extname == 'xls' || $extname == 'txt' || $extname == 'zip' || $extname == 'rar' || $extname == 'ppt' || $extname == 'pdf' || $extname == 'rm' || $extname == 'mid' || $extname == 'wav' || $extname == 'bmp' || $extname == 'swf' || $extname == 'chm' || $extname == 'sql' || $extname == 'cert' || $extname == 'pptx' || $extname == 'xlsx' || $extname == 'docx') {
			$format = $extname;
		}
	}
	else {
		return '';
	}

	if ($format == '' && 2 <= strlen($str)) {
		if (substr($str, 0, 4) == 'MThd' && $extname != 'txt') {
			$format = 'mid';
		}
		else {
			if (substr($str, 0, 4) == 'RIFF' && $extname == 'wav') {
				$format = 'wav';
			}
			else if (substr($str, 0, 3) == "\xff\xd8\xff") {
				$format = 'jpg';
			}
			else {
				if (substr($str, 0, 4) == 'GIF8' && $extname != 'txt') {
					$format = 'gif';
				}
				else if (substr($str, 0, 8) == '�PNG') {
					$format = 'png';
				}
				else {
					if (substr($str, 0, 2) == 'BM' && $extname != 'txt') {
						$format = 'bmp';
					}
					else {
						if ((substr($str, 0, 3) == 'CWS' || substr($str, 0, 3) == 'FWS') && $extname != 'txt') {
							$format = 'swf';
						}
						else if (substr($str, 0, 4) == "\xd0\xcf\x11\xe0") {
							if (substr($str, 512, 4) == "\xec\xa5\xc1\x00" || $extname == 'doc') {
								$format = 'doc';
							}
							else {
								if (substr($str, 512, 2) == '	' || $extname == 'xls') {
									$format = 'xls';
								}
								else {
									if (substr($str, 512, 4) == "\xfd\xff\xff\xff" || $extname == 'ppt') {
										$format = 'ppt';
									}
								}
							}
						}
						else if (substr($str, 0, 4) == 'PK') {
							if (substr($str, 512, 4) == "\xec\xa5\xc1\x00" || $extname == 'docx') {
								$format = 'docx';
							}
							else {
								if (substr($str, 512, 2) == '	' || $extname == 'xlsx') {
									$format = 'xlsx';
								}
								else {
									if (substr($str, 512, 4) == "\xfd\xff\xff\xff" || $extname == 'pptx') {
										$format = 'pptx';
									}
									else {
										$format = 'zip';
									}
								}
							}
						}
						else {
							if (substr($str, 0, 4) == 'Rar!' && $extname != 'txt') {
								$format = 'rar';
							}
							else if (substr($str, 0, 4) == '%PDF') {
								$format = 'pdf';
							}
							else if (substr($str, 0, 3) == "0\x82\n") {
								$format = 'cert';
							}
							else {
								if (substr($str, 0, 4) == 'ITSF' && $extname != 'txt') {
									$format = 'chm';
								}
								else if (substr($str, 0, 4) == '.RMF') {
									$format = 'rm';
								}
								else if ($extname == 'sql') {
									$format = 'sql';
								}
								else if ($extname == 'txt') {
									$format = 'txt';
								}
							}
						}
					}
				}
			}
		}
	}

	if ($limit_ext_types && stristr($limit_ext_types, '|' . $format . '|') === false) {
		$format = '';
	}

	return $format;
}

function mysql_like_quote($str)
{
	return strtr($str, array('\\\\' => '\\\\\\\\', '_' => '\\_', '%' => '\\%', '\\\'' => '\\\\\\\''));
}

function real_server_ip()
{
	static $serverip;

	if ($serverip !== NULL) {
		return $serverip;
	}

	if (isset($_SERVER)) {
		if (isset($_SERVER['SERVER_ADDR'])) {
			$serverip = $_SERVER['SERVER_ADDR'];
		}
		else {
			$serverip = '0.0.0.0';
		}
	}
	else {
		$serverip = getenv('SERVER_ADDR');
	}

	return $serverip;
}

function ecs_header($string, $replace = true, $http_response_code = 0)
{
	if (strpos($string, '../upgrade/index.php') === 0) {
		echo '<script type="text/javascript">window.location.href="' . $string . '";</script>';
	}

	$string = str_replace(array('', ''), array('', ''), $string);

	if (preg_match('/^\\s*location:/is', $string)) {
		@header($string . '', $replace);
		exit();
	}

	if (empty($http_response_code) || PHP_VERSION < '4.3') {
		@header($string, $replace);
	}
	else {
		@header($string, $replace, $http_response_code);
	}
}

function ecs_iconv($source_lang, $target_lang, $source_string = '')
{
	static $chs;
	if ($source_lang == $target_lang || $source_string == '' || preg_match("/[\x80-\xff]+/", $source_string) == 0) {
		return $source_string;
	}

	if ($chs === NULL) {
		$chs = new \App\Libraries\Iconv(ROOT_PATH);
	}

	return $chs->Convert($source_lang, $target_lang, $source_string);
}

function ecs_geoip($ip)
{
	static $fp;
	static $offset = array();
	static $index;
	$ip = gethostbyname($ip);
	$ipdot = explode('.', $ip);
	$ip = pack('N', ip2long($ip));
	$ipdot[0] = (int) $ipdot[0];
	$ipdot[1] = (int) $ipdot[1];
	if ($ipdot[0] == 10 || $ipdot[0] == 127 || $ipdot[0] == 192 && $ipdot[1] == 168 || $ipdot[0] == 172 && (16 <= $ipdot[1] && $ipdot[1] <= 31)) {
		return 'LAN';
	}

	if ($fp === NULL) {
		$fp = fopen(dirname(ROOT_PATH) . '/includes/codetable/ipdata.dat', 'rb');

		if ($fp === false) {
			return 'Invalid IP data file';
		}

		$offset = unpack('Nlen', fread($fp, 4));

		if ($offset['len'] < 4) {
			return 'Invalid IP data file';
		}

		$index = fread($fp, $offset['len'] - 4);
	}

	$length = $offset['len'] - 1028;
	$start = unpack('Vlen', $index[$ipdot[0] * 4] . $index[$ipdot[0] * 4 + 1] . $index[$ipdot[0] * 4 + 2] . $index[$ipdot[0] * 4 + 3]);

	for ($start = $start['len'] * 8 + 1024; $start < $length; $start += 8) {
		if ($ip <= $index[$start] . $index[$start + 1] . $index[$start + 2] . $index[$start + 3]) {
			$index_offset = unpack('Vlen', $index[$start + 4] . $index[$start + 5] . $index[$start + 6] . ' ');
			$index_length = unpack('Clen', $index[$start + 7]);
			break;
		}
	}

	fseek($fp, $offset['len'] + $index_offset['len'] - 1024);
	$area = fread($fp, $index_length['len']);
	fclose($fp);
	$fp = NULL;
	return \App\Extensions\Util::auto_charset($area);
}

function trim_right($str)
{
	$len = strlen($str);
	if ($len == 0 || ord($str[$len - 1]) < 127) {
		return $str;
	}

	if (192 <= ord($str[$len - 1])) {
		return substr($str, 0, $len - 1);
	}

	$r_len = strlen(rtrim($str, "\x80..\xbf"));
	if ($r_len == 0 || ord($str[$r_len - 1]) < 127) {
		return sub_str($str, 0, $r_len);
	}

	$as_num = ord(~$str[$r_len - 1]);

	if (1 << 6 + $r_len - $len < $as_num) {
		return $str;
	}
	else {
		return substr($str, 0, $r_len - 1);
	}
}

function move_upload_file($file_name, $target_name = '')
{
	if (function_exists('move_uploaded_file')) {
		if (move_uploaded_file($file_name, $target_name)) {
			@chmod($target_name, 493);
			return true;
		}
		else if (copy($file_name, $target_name)) {
			@chmod($target_name, 493);
			return true;
		}
	}
	else if (copy($file_name, $target_name)) {
		@chmod($target_name, 493);
		return true;
	}

	return false;
}

function json_str_iconv($str)
{
	if (CHARSET != 'utf-8') {
		if (is_string($str)) {
			return addslashes(stripslashes(ecs_iconv('utf-8', CHARSET, $str)));
		}
		else if (is_array($str)) {
			foreach ($str as $key => $value) {
				$str[$key] = json_str_iconv($value);
			}

			return $str;
		}
		else if (is_object($str)) {
			foreach ($str as $key => $value) {
				$str->$key = json_str_iconv($value);
			}

			return $str;
		}
		else {
			return $str;
		}
	}

	return $str;
}

function to_utf8_iconv($str)
{
	if (CHARSET != 'utf-8') {
		if (is_string($str)) {
			return ecs_iconv(CHARSET, 'utf-8', $str);
		}
		else if (is_array($str)) {
			foreach ($str as $key => $value) {
				$str[$key] = to_utf8_iconv($value);
			}

			return $str;
		}
		else if (is_object($str)) {
			foreach ($str as $key => $value) {
				$str->$key = to_utf8_iconv($value);
			}

			return $str;
		}
		else {
			return $str;
		}
	}

	return $str;
}

function get_file_suffix($file_name, $allow_type = array())
{
	$name_array = explode('.', $file_name);
	$file_suffix = strtolower(array_pop($name_array));

	if (empty($allow_type)) {
		return $file_suffix;
	}
	else if (in_array($file_suffix, $allow_type)) {
		return true;
	}
	else {
		return false;
	}
}

function read_static_cache($cache_name)
{
	if (APP_DEBUG) {
		return false;
	}

	return S($cache_name);
}

function write_static_cache($cache_name, $caches)
{
	if (APP_DEBUG) {
		return false;
	}

	return S($cache_name, $caches);
}

function real_cart_mac_ip()
{
	$session_id_ip = cookie('session_id_ip');

	if (empty($session_id_ip)) {
		$session_id_ip = md5(SESS_ID . dirname(__DIR__));
		$time = 3600 * 24 * 365;
		cookie('session_id_ip', $session_id_ip, $time);
	}

	return $session_id_ip;
}

function mdate($time = NULL)
{
	$text = '';
	$time = $time === NULL || gmtime() < $time ? gmtime() : intval($time);
	$t = gmtime() - $time;
	$y = date('Y', $time) - date('Y', gmtime());

	switch ($t) {
	case $t == 0:
		$text = '刚刚';
		break;

	case $t < 60:
		$text = $t . '秒前';
		break;

	case $t < 60 * 60:
		$text = floor($t / 60) . '分钟前';
		break;

	case $t < 60 * 60 * 24:
		$text = floor($t / (60 * 60)) . '小时前';
		break;

	case $t < 60 * 60 * 24 * 3:
		$text = floor($time / (60 * 60 * 24)) == 1 ? '昨天 ' . local_date('H:i', $time) : '前天 ' . local_date('H:i', $time);
		break;

	case $t < 60 * 60 * 24 * 30:
		$text = local_date('m月d日 H:i', $time);
		break;

	case $t < 60 * 60 * 24 * 365 && $y == 0:
		$text = date('m月d日', $time);
		break;

	default:
		$text = date('Y年m月d日', $time);
		break;
	}

	return $text;
}

function is_mobile_browser()
{
	if (isset($_SERVER['HTTP_X_WAP_PROFILE'])) {
		return true;
	}

	if (isset($_SERVER['HTTP_VIA'])) {
		return stristr($_SERVER['HTTP_VIA'], 'wap') ? true : false;
	}

	if (isset($_SERVER['HTTP_USER_AGENT'])) {
		$clientkeywords = array('nokia', 'sony', 'ericsson', 'mot', 'samsung', 'htc', 'sgh', 'lg', 'sharp', 'sie-', 'philips', 'panasonic', 'alcatel', 'lenovo', 'iphone', 'ipod', 'blackberry', 'meizu', 'android', 'netfront', 'symbian', 'ucweb', 'windowsce', 'palm', 'operamini', 'operamobi', 'openwave', 'nexusone', 'cldc', 'midp', 'wap', 'mobile');

		if (preg_match('/(' . implode('|', $clientkeywords) . ')/i', strtolower($_SERVER['HTTP_USER_AGENT']))) {
			return true;
		}
	}

	if (isset($_SERVER['HTTP_ACCEPT'])) {
		if (strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') !== false && (strpos($_SERVER['HTTP_ACCEPT'], 'text/html') === false || strpos($_SERVER['HTTP_ACCEPT'], 'vnd.wap.wml') < strpos($_SERVER['HTTP_ACCEPT'], 'text/html'))) {
			return true;
		}
	}

	return false;
}

function url_set_value($url, $key, $value)
{
	$a = explode('?', $url);
	$url_f = $a[0];
	$query = $a[1];
	parse_str($query, $arr);
	$arr[$key] = $value;
	return $url_f . '?' . http_build_query($arr, '', '&');
}

function msubstr_ect($str, $start = 0, $length = 1, $charset = 'utf-8', $suffix = '***', $position = 1)
{
	if (function_exists('mb_substr')) {
		$slice = mb_substr($str, $start, $length, $charset);
		$slice_end = mb_substr($str, 0 - $length, $length, $charset);
	}
	else if (function_exists('iconv_substr')) {
		$slice = iconv_substr($str, $start, $length, $charset);
		$slice_end = iconv_substr($str, 0 - $length, $length, $charset);
	}
	else {
		$re['utf-8'] = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
		$re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
		$re['gbk'] = "/[\x01-\x7f]|[\x81-\xfe][@-\xfe]/";
		$re['big5'] = "/[\x01-\x7f]|[\x81-\xfe]([@-~]|\xa1-\xfe])/";
		preg_match_all($re[$charset], $str, $match);
		$slice = join('', array_slice($match[0], $start, $length));
		$slice_end = join('', array_slice($match[0], 0 - $length, $length));
	}

	return $position == 0 ? $slice . $suffix : $slice . $suffix . $slice_end;
}

function string_to_star($string = '', $num = 3)
{
	if (9 < strlen($string) && $num < strlen($string)) {
		$lenth = strlen($string) - $num * 2;
		$star_length = '';

		for ($x = 1; $x <= $lenth; $x++) {
			$star_length .= '*';
		}

		$result = msubstr_ect($string, 0, $num, 'utf-8', $star_length);
	}
	else {
		$result = $string;
	}

	return $result;
}

function goods_name_strlen($string)
{
	preg_match_all('/[0-9-_|a-zA-Z]{1}/', $string, $match);
	return count($match[0]);
}

function make_trade_no($log_id, $order_amount)
{
	$trade_no = '6';
	$trade_no .= str_pad($log_id, 15, 0, STR_PAD_LEFT);
	$trade_no .= str_pad($order_amount * 100, 16, 0, STR_PAD_LEFT);
	return $trade_no;
}

function parse_trade_no($trade_no)
{
	$log_id = substr($trade_no, 1, 15);
	return intval($log_id);
}

function content_style_replace($content)
{
	$label = array('/style=.+?[*|\\"]/i' => '', '/width\\=\\"[0-9]+?\\"/i' => '', '/height\\=\\"[0-9]+?\\"/i' => '');

	foreach ($label as $key => $value) {
		$content = preg_replace($key, $value, $content);
	}

	return $content;
}

if (!function_exists('file_get_contents')) {
	function file_get_contents($file)
	{
		if (($fp = @fopen($file, 'rb')) === false) {
			return false;
		}
		else {
			$fsize = @filesize($file);

			if ($fsize) {
				$contents = fread($fp, $fsize);
			}
			else {
				$contents = '';
			}

			fclose($fp);
			return $contents;
		}
	}
}

if (!function_exists('file_put_contents')) {
	define('FILE_APPEND', 'FILE_APPEND');
	function file_put_contents($file, $data, $flags = '')
	{
		$contents = is_array($data) ? implode('', $data) : $data;

		if ($flags == 'FILE_APPEND') {
			$mode = 'ab+';
		}
		else {
			$mode = 'wb';
		}

		if (($fp = @fopen($file, $mode)) === false) {
			return false;
		}
		else {
			$bytes = fwrite($fp, $contents);
			fclose($fp);
			return $bytes;
		}
	}
}

if (!function_exists('floatval')) {
	function floatval($n)
	{
		return (double) $n;
	}
}

?>
