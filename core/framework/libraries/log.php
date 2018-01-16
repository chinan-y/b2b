<?php
/**
 * 记录日志 
 ***/
defined('GcWebShop') or exit('Access Invalid!');
class Log{

    const SQL       = 'SQL';
    const ERR       = 'ERR';
    private static $log =   array();

    public static function record($message,$level=self::ERR) {
        $now = @date('Y-m-d H:i:s',time());
        switch ($level) {
            case self::SQL:
               self::$log[] = "[{$now}] {$level}: {$message}\r\n";
               break;
            case self::ERR:
                $log_file = BASE_DATA_PATH.'/log/'.date('Ymd',TIMESTAMP).'.log';
                $url = $_SERVER['REQUEST_URI'] ? $_SERVER['REQUEST_URI'] : $_SERVER['PHP_SELF'];
                $url .= " ( gct={$_GET['gct']}&gp={$_GET['gp']} ) ";
                $content = "[{$now}] {$url}\r\n{$level}: {$message}\r\n";
                file_put_contents($log_file,$content, FILE_APPEND);
                break;
        }
    }

    public static function read(){
    	return self::$log;
    }
}