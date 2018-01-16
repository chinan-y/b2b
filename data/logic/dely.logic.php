<?php
/**
 * 延迟队列
 *
 */
defined('GcWebShop') or exit('Access Invalid!');

class delyLogic {
	
	//支付回调间隔时间
	private $pay_callbak_spilt = array(0, 5, 10, 30, 60, 300, 600);

	/**
     * 发送异步回调通知
     * @param unknown $order_info
     * @throws Exception
     */
    public function payCallback($order_info) {
		import('function.ftp');
		$model_dely = Model('dely');
		$model_dely_log = Model('dely_log');
		
		$order_info_post = $order_info;
		unset($order_info_post['url']);
		unset($order_info_post['dely_id']);
		unset($order_info_post['dely_times']);
		unset($order_info_post['dely_next_time']);

		$content = dfsockopen($order_info['url'], 5, $order_info_post);
		if($content == 'true'){
			$delete = $model_dely->where(array('id'=>$order_info['dely_id']))->delete();
			$insert = $model_dely_log->insert(array(
				'dely_id'=>$order_info['dely_id'],
				'action'=>'payCallback',
				'state'=>1,
				'add_time'=>time(),
				'param'=>serialize($order_info_post),
				'param1'=>$order_info['APPID'],
			));
		}else{
			$current_times = $order_info['dely_times']+1;
			if($current_times < count($this->pay_callbak_spilt)){
				$update = $model_dely->where(array('id'=>$order_info['dely_id']))->update(array('times'=>$current_times, 'next_time'=>$this->pay_callbak_spilt[$current_times]+$order_info['dely_next_time']));
			}else{
				$delete = $model_dely->where(array('id'=>$order_info['dely_id']))->delete();
				$insert = $model_dely_log->insert(array(
					'dely_id'=>$order_info['dely_id'],
					'action'=>'payCallback',
					'state'=>0,
					'add_time'=>time(),
					'param'=>serialize($order_info_post),
					'param1'=>$order_info['APPID'],
				));
			}
		}
		
        if ($update || $delete || $insert) {
            return callback(true);
        } else {
            return callback(false,'记录支付结果失败id:'.$order_info['dely_id']);
        }
    }
}