<?php
//SHOPROOO
if (!defined('IN_ECS')) {
	exit('Hacking attempt');
}

if (isset($set_modules) && $set_modules == true) {
	$i = isset($modules) ? count($modules) : 0;
	$modules[$i]['code'] = 'ecjia';
	$modules[$i]['name'] = 'ECJia';
	$modules[$i]['version'] = '1.x';
	$modules[$i]['author'] = 'ECMOBAN R&D TEAM';
	$modules[$i]['website'] = 'http://www.ecmoban.com';
	$modules[$i]['default']['db_host'] = 'localhost';
	$modules[$i]['default']['db_user'] = 'root';
	$modules[$i]['default']['prefix'] = 'uc_';
	$modules[$i]['default']['cookie_prefix'] = 'xnW_';
	return NULL;
}

require_once ROOT_PATH . 'includes/modules/integrates/ucenter.php';
class ecjia extends ucenter
{
	public function login($username, $password, $remember = NULL, $is_oath = 1)
	{
		$username = addslashes($username);

		if (is_mobile($username)) {
			$condition['mobile_phone'] = $username;
		}
		else if (is_email($username)) {
			$condition['email'] = $username;
		}
		else {
			$condition['user_name'] = $username;
		}

		$local_user = DB::table('users')->select('user_id', 'user_name', 'password', 'email', 'mobile_phone', 'ec_salt')->where(key($condition), current($condition))->first();
		$mobile = collect($local_user)->get('mobile_phone', $username);
		$isuid = 6;
		list($uid, $uname, $pwd, $email, $repeat) = Api::uc_user_login($mobile, $password, $isuid);
		if ($uid < 0 && !is_null($local_user)) {
			$ec_salt = collect($local_user)->get('ec_salt');
			$email = collect($local_user)->get('email');
			$local_password = array('password' => $password, 'ec_salt' => $ec_salt);

			if (collect($local_user)->get('password') == $this->compile_password($local_password)) {
				if ($this->add_user($mobile, $password, $email)) {
					list($uid, $uname, $pwd, $email, $repeat) = Api::uc_user_login($mobile, $password, $isuid);
				}
			}
		}

		if (0 < $uid) {
			$connect_user = DB::table('connect_user')->where('connect_code', $this->connectType())->where('open_id', $uid)->leftJoin('users', 'users.user_id', 'connect_user.user_id')->first();
			$ec_salt = rand(1000, 9999);
			$password = $this->compile_password(array('password' => $password, 'ec_salt' => $ec_salt));

			if (is_null($connect_user)) {
				if (is_null($local_user)) {
					$user_id = DB::table('users')->insertGetId(array('user_name' => $uname, 'password' => $password, 'ec_salt' => $ec_salt, 'email' => $email, 'reg_time' => gmtime(), 'last_login' => gmtime(), 'last_ip' => real_ip()));
				}
				else {
					$user_id = collect($local_user)->get('user_id');
				}

				DB::table('connect_user')->insert(array('connect_code' => $this->connectType(), 'user_id' => $user_id, 'open_id' => $uid, 'create_at' => gmtime()));
			}
			else {
				DB::table('users')->where('user_id', collect($local_user)->get('user_id'))->update(array('password' => $password, 'ec_salt' => $ec_salt));
				$uname = collect($local_user)->get('user_name');
			}

			if ($is_oath == 1) {
				$this->set_session($uname);
				$this->set_cookie($uname);
			}

			$this->ucdata = Api::uc_user_synlogin($uid);
			return true;
		}
		else if ($uid == -1) {
			$this->error = ERR_INVALID_USERNAME;
			return false;
		}
		else if ($uid == -2) {
			$this->error = ERR_INVALID_PASSWORD;
			return false;
		}
		else {
			return false;
		}
	}

	public function add_user($username, $password, $email, $gender = -1, $bday = 0, $reg_date = 0, $md5password = '')
	{
		$username = $email['mobile_phone'];
		parent::add_user($username, $password, $email, $gender, $bday, $reg_date, $md5password);
	}

	protected function connectType()
	{
		return 'ecjiauc';
	}
}

?>
