
<?php
/**
 * 微信支付接口类
 
 */
defined('GcWebShop') or exit('Access Invalid!');

class wxpay{

        /**
         * [构造方法]
         * @author fulijun
         * @dateTime 2016-07-23T12:01:07+0800
         */
        public function __construct($payment_info=array(),$order_info=array()){
            @ini_set('date.timezone','Asia/Shanghai');
            if(!empty($payment_info) and !empty($order_info)){
                $this->payment  = $payment_info;
                $this->order    = $order_info;
            } 
        }

        public function get_payurl(){       
            require_once "lib/WxPay.Api.php";
            require_once "lib/WxPay.NativePay.php";
            //require_once 'log.php';
            //-------------------------------fulijun配置订单金额等参数
            $pay_id = intval($this->order["pay_id"]);
            $pay_sn = strval($this->order["pay_sn"]);
            $buyer_id = intval($this->order["buyer_id"]);
            $title = strval($this->order["subject"]);
            $order_type= strval($this->order["order_type"]);
            $pay_money = $this->order["api_pay_amount"]*100;
            $notify_url = SHOP_SITE_URL."/api/payment/wxpay/return_url.php";
            //$notify_url = "https://www.qqbsmall.com.gotunnel.org/shop/api/payment/wxpay/return_url.php";
         
            //-------------------------------fulijun配置订单金额等参数

            //模式二
            /**
             * 流程：
             * 1、调用统一下单，取得code_url，生成二维码
             * 2、用户扫描二维码，进行支付
             * 3、支付完成之后，微信服务器会通知支付成功
             * 4、在支付成功通知中需要查单确认是否真正支付成功（见：notify.php）
             */
            $input = new WxPayUnifiedOrder();
            $input->SetBody($title);
            $input->SetAttach($order_type);
            $input->SetOut_trade_no($pay_sn);
            $input->SetTotal_fee($pay_money);
            $input->SetTime_start(date("YmdHis"));
            $input->SetTime_expire(date("YmdHis", time() + 600));
            $input->SetGoods_tag($title);
            $input->SetNotify_url($notify_url);
            $input->SetTrade_type("NATIVE");
            $input->SetProduct_id("123456789");

            $notify = new NativePay();
            $result = $notify->GetPayUrl($input);
            $url = $result["code_url"];
            if(isset($url)){
                return array('url'=>$url,'pay_sn'=>$pay_sn,'buyer_id'=>$buyer_id,'price'=>$this->order["api_pay_amount"]);
            }
            return array();
        }

    /**
     * 
     * 取得订单支付状态，成功或失败
     * @param array $param
     * @return array
     */
    public function getPayResult($param){
        return $param['trade_status'] == 'TRADE_SUCCESS';
    }

}
?>

