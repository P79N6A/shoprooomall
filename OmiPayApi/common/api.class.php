
<?php

    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
    header('Access-Control-Allow-Methods: GET, POST, PUT');

    header('Content-type:text/json;charset="utf-8"');

    ini_set('date.timezone', 'Asia/Shanghai');
    error_reporting(E_ERROR);
    require_once "../lib/OmiPayApi.php";

    /**
     * 
     * */ 
    class api{
        public $response = null;
        public $get_content = null;
        public $name = "";

        function __construct($opts){
            // *******初始化日志 
            // $logHandler = new CLogFileHandler("../logs/" . date('Y-m-d') . '.log');  
            // $log = Log::Init($logHandler, 15);


            $this->get_content = file_get_contents("php://input");
            $this->response = json_decode($this->get_content, true);

            $this->name = $opts["name"];
        }

        public function log($msg = "", $level = 2){
            switch ($level) {
                case 1:
                    return Log::DEBUG($this->name . $msg);//'debug';
                    break;
                case 2:
                    return Log::INFO($this->name . $msg); //'info';
                    break;
                case 4:
                    return Log::WARN($this->name . $msg); //'warn';
                    break;
                case 8:
                    return Log::ERROR($this->name . $msg); //'error';
                    break;
                default:
    
            }
        }
    }

?>