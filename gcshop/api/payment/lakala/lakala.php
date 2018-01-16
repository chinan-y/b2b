<?php
/**
 * 拉卡拉接口类
 *
 * 
 
 */

defined('GcWebShop') or exit('Access Invalid!');

class lakala{

     /**
     * 支付状态
     * @var unknown
     */
    private $pay_result;

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


    /**
     * [get_payurl description]
     * @author fulijun
     * @dateTime 2016-09-01T14:19:34+0800
     * @return   [type]                   [description]
     */
    public function get_payurl(){
          require_once "common/common.php";

          //商户号
          $mid = strval($config['mid']);

          $timerand = date('YmdHis').mt_rand(100000, 999999);
          $merDesStr = md5($mid);
          $encKeyStr = $timerand.$merDesStr;
          $encrypted = $maced = '';
        
          //公钥加密  
          openssl_public_encrypt($encKeyStr, $encrypted, $config['pk']);
          //密钥密文
          $encKey = strtoupper(bin2hex($encrypted));

          //组织数据
          //===============
          $ver = "1.0.0";
          $reqType = "B0002";
          $pay_sn = strval($this->order["pay_sn"]);
          $order_type= strval($this->order["order_type"]);
          $title = strval($this->order["subject"]);
          $buyer_id = strval($this->order["buyer_id"]);

          //取得商品货款总额和税款总额
          $order_amount = number_format($this->order['order_list'][0]['order_amount'],2);
          $order_tax = number_format($this->order['order_list'][0]['order_tax'],2);

          $pay_money = sprintf("%.2f",$this->order["api_pay_amount"]);
          //订单日期和时间戳
          $ts_time = date('YmdHis').mt_rand(100000, 999999);
          $order_date = date('YmdHis');
          //==============
          $_orderData = array(
            'merOrderId' => $pay_sn,//商户订单号
            'currency' => 'CNY',
            'ts' => $ts_time,
            'orderAmount' => $pay_money,
            'payeeAmount' => $pay_money,
            'orderTime' => $order_date,
            'bizCode' => '121010',
            'orderSummary' => $title,
            'timeZone' => 'GMT+8',
            'pageUrl' => $config['callback_url'],
            'bgUrl' => $config['notify_url'],
            'merClientId'=>$buyer_id,
            'ext1' => $order_type,
            'ext2' => $buyer_id,
            'cuId'=>6,
            'aqsiqId'=>0,
            'bizTypeCode'=>'I20',
            'goodsFee'=>$order_amount,
            'taxFee'=>$order_tax,
            'buyForexKind'=>'0100'
          );
     
          $json_init = json_encode_s($_orderData);
          $json = des_encrypt($merDesStr, $json_init);
          
          $macStr = $mid.$ver.$ts_time.$reqType.$json;
          $macStr = sha1($macStr);
          openssl_private_encrypt($macStr, $maced, $config['rk']); //私钥加密  
          $mac = strtoupper(bin2hex($maced));
          //表单提交
          $data = array(
            'ver' => $ver,
            'merId' => $mid,
            'ts' => $ts_time,
            'pageUrl' => $config['callback_url'],
            'reqType' => $reqType,
            'encKey' => $encKey,
            'encData' => $json,
            'mac' => $mac
          );
          //表单提交
          build_form($config['ppayGateUrl'], $data);
    }


  /**
   * 返回地址验证(同步)
   * @param 
   * @return boolean
   */
  public function return_verify(){   
        if($_GET['extra_common_param']){
            $this->pay_result = true;            
            return true;
        }else{
            return false;
        }
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
