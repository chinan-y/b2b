<?php
/**
 * 延迟队列
 *
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class delyControl {

    public function queueOp() {
        if (ob_get_level()) ob_end_clean();
		
        $model_queue = Logic('dely');
        $worker = Model('dely');
		
        while (true) {
            $list_key = $worker->scan();
            $working = false;
			
            if (!empty($list_key) && is_array($list_key)) {
                foreach ($list_key as $key) {
					$method = $key['action'];
					$arg = unserialize($key['param']);
					if(empty($method)) continue;
					if(empty($arg)) continue;
					
					$working = true;
					$arg['dely_id'] = $key['id'];
					$arg['dely_times'] = $key['times'];
					$arg['dely_next_time'] = $key['next_time'];
					
                    $model_queue->$method($arg);
                    echo date('Y-m-d H:i:s',time()).' '.$method .' id='.$arg['dely_id']."\n";
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