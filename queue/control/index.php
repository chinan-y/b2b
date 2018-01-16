<?php
/**
 * 队列
*
*
*
*

*/
defined('GcWebShop') or exit('Access Invalid!');

class indexControl {

    public function queueOp() {
        if (ob_get_level()) ob_end_clean();

        $model_queue = Logic('queue');

        $worker = new QueueServer();
        while (true) {
            $list_key = $worker->scan();
            $working = false;
            if (!empty($list_key) && is_array($list_key)) {
                foreach ($list_key as $key) {
                    $content = $worker->pop($key, 0);
                    if (empty($content)) continue;
                    $working = true;
                    $method = key($content);
                    $arg = current($content);
                    $model_queue->$method($arg);
                    echo date('Y-m-d H:i:s',time()).' '.$method."\n";
//                     $content['time'] = date('Y-m-d H:i:s',time());
//                     print_R($content);
//                     echo "\n";
                    flush();
                    ob_flush();
                }
            }
            if(!$working){
                sleep(4);
                DB::ping();
            }
            sleep(1);
        }
    }
}