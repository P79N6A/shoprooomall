<?php
namespace ectouch;
// migrate-201603091430.sql
define('MIGRATE_VERSION_FILE', '.version');
define('MIGRATE_FILE_PREFIX', 'migrate-');
define('MIGRATE_FILE_POSTFIX', '.php');

/**
 * exp:
 * Migrate::init();
 */
class Migrate
{

    public static $version = 0;
    public static $migrate_path = '';
    public static $migrate_version = '';
    public static $migrate_files = array();
    private static $conn = '';

    static public function setPath()
    {
        self::$migrate_path = ROOT_PATH . 'database/migrations/';
        self::$migrate_version = ROOT_PATH . 'storage/migrations/' . MIGRATE_VERSION_FILE;
    }

    /**
     * 迁移初始化
     * @return bool
     */
    public static function init()
    {
        self::setPath();
        self::connect();
        self::create_migrate();
        self::update_db();
    }

    public static function create_migrate()
    {
        $sql = "SELECT `value` FROM `" . config('DB_PREFIX') . "shop_config` where `code`='migrate_version'";
        $result = self::query($sql);
        if (is_null($result)) {
            if (file_exists(self::$migrate_version)) {
                self::$version = floatval(file_get_contents(self::$migrate_version));
            } else {
                self::$version = 0;
            }
            $sql = "INSERT INTO " . config('DB_PREFIX') . "shop_config (`parent_id`, `code`, `type`, `value`, `sort_order`) 
            VALUES (9, 'migrate_version', 'hidden', '" . self::$version . "', 1)";
            $result = self::execute($sql);
            if ($result && file_exists(self::$migrate_version)) {
                @unlink(self::$migrate_version);
            }
        } else {
            self::$version = $result[0];
        }
    }

    /**
     * 获取到migrate文件夹中的所有文件
     */
    public static function get_migrations()
    {
        $dir = opendir(self::$migrate_path);
        while ($file = readdir($dir)) {
            if (substr($file, 0, strlen(MIGRATE_FILE_PREFIX)) == MIGRATE_FILE_PREFIX) {
                self::$migrate_files[] = $file;
            }
        }
        asort(self::$migrate_files);
    }

    /**
     * 根据文件名
     * @param $file
     * @return mixed
     */
    public static function get_version_from_file($file)
    {
        return floatval(substr($file, strlen(MIGRATE_FILE_PREFIX)));
    }

    /**
     * 迁移数据
     */
    public static function update_db()
    {
        self::get_migrations();
        // Check to make sure there are no conflicts such as 2 files under the same version.
        $errors = array();
        $last_file = false;
        $last_version = false;
        foreach (self::$migrate_files as $file) {
            $file_version = self::get_version_from_file($file);
            if ($last_version !== false && $last_version === $file_version) {
                $errors[] = "$last_file --- $file";
            }
            $last_version = $file_version;
            $last_file = $file;
        }
        if (count($errors) > 0) {
            echo "数据迁移文件存在多个相同的版本.\n";
            foreach ($errors as $error) {
                echo " $error\n";
            }
            exit();
        }
        // Run all the new files.
        foreach (self::$migrate_files as $file) {
            $file_version = self::get_version_from_file($file);
            if ($file_version <= self::$version) {
                continue;
            }
            $sqls = file_get_contents(self::$migrate_path . $file);
            $sqls = self::selectsql($sqls);
            $str = null;
            $num = 1;
            self::execute('set names utf8');
            self::execute('BEGIN');
            foreach ((array)$sqls as $val) {
                if (empty($val)) continue;
                if (is_string($val)) {
                    if (!self::execute($val)) {
                        $num = 0;
                    }
                }
            }
            if ($num == 0) {
                self::execute('ROLLBACK');
            } elseif ($num == 1) {
                self::execute('COMMIT');
            }

            $sql = "UPDATE " . config('DB_PREFIX') . "shop_config SET value = '" . $file_version . "' WHERE code = 'migrate_version'";
            $query = self::execute($sql);
            if (!$query) {
                exit('Data migration failed');
            }
        }
    }

    /**
     * 数据库查询操作
     * @param $str
     * @return bool
     */
    public static function query($str)
    {
        $result = self::execute($str);
        return mysqli_fetch_row($result);
    }

    /**
     * 数据库查询操作
     * @param $str
     * @return bool
     */
    public static function execute($str)
    {
        return mysqli_query(self::$conn, $str);
    }

    /**
     * 连接数据库方法
     */
    public static function connect()
    {
        self::$conn = mysqli_connect(config('DB_HOST'), config('DB_USER'), config('DB_PWD'), config('DB_NAME'), config('DB_PORT')) or die('Error:cannot connect to database!!!' . mysql_error());
    }

    /**
     * 判断是否是注释
     * @param $sql   获取到的sql文件内容
     */
    public static function selectsql($sqls)
    {
        $statement = null;
        $newStatement = null;
        $commenter = array('#', '--');
        $sqls = explode(';', trim($sqls));//按sql语句分开
        foreach ($sqls as $sql) {
            if (preg_match('/^(\/\*)(.)+/i', $sql)) {
                $sql = preg_replace('/(\/\*){1}([.|\s|\S])*(\*\/){1}/', '', $sql);
            }
            $sentence = explode('/n', $sql);
            foreach ($sentence as $subSentence) {
                $subSentence = str_replace('{pre}', config('DB_PREFIX'), $subSentence);
                if ('' != trim($subSentence)) {
                    //判断是否注释
                    $isComment = false;

                    foreach ($commenter as $comer) {
                        if (preg_match("/^(" . $comer . ")/", trim($subSentence))) {
                            $isComment = true;
                            break;
                        }

                    }
                    //不是注释就是sql语句
                    if (!$isComment)
                        $newStatement[] = $subSentence;
                }
            }
            $statement = $newStatement;
        }
        return $statement;
    }
}
