<?php
//强制设置页面编码为UTF-8
header("Content-type: text/html; charset=utf-8");
error_reporting(0);
//设置公共数据参数
define("_PATH_", dirname(__DIR__));
define('SHOP_SITE_URL', 'http://www.qqbsmall.com/gcshop');

//商户号、公钥、私钥配置
$config = array(
  'mid' => 'DOPCHN000120',
  'public_key' => 'MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQDPg0O4rPQJL1O+jqJ4rBjFVNRAuDmBSoii9pYfPQBaescCVY0irkWWoLyfTT65TjvnPpOx+IfNzBTlB13qCEFm7algREoeUHjFgFNHiXJ2LK/R0+VWgXe5+EDFfbrFCPnmLKG3OcKDGQszP0VOf6VVTM1t56CpgaRMm1/+Tzd2TQIDAQAB',
  'private_key' => 'MIICdgIBADANBgkqhkiG9w0BAQEFAASCAmAwggJcAgEAAoGBAJWmC+oWQM6BPF8fSE/JhZvbebX9NSSs5Y5VT8rJPsYiWOqJi8DFftferDCfeJDWfHBUxkKV4P/5D3tF1/f07cUqtgz6668nfdsgThgIueCDJiWH/odSUUItOk0VihW2tbi+GKXKOLReH4tEsUIPN4Lcr/OyjOzt02WIb9HcGJmhAgMBAAECgYA3pjbaBwp0gKSlruliGkugKN666X1AtTbsVhdFDs9UOOOd3CZnOkowFnMr5bGdEtXVpADSNBAmwEScj91/LMraK2AP6rQ/cye7bs0ktWf2TX3ztd5Hr+QrT68qkvDs4pFZ+mP0hBTyS/cBgZ6cN6iLeqFv4pKZFPtUYYwAautV/QJBAOAGhi8KCKmVtRqP9xOu0T16EGr3ZTRlOkcmlv/gLMWWBhFiSmK2ork+H/S5ZER8CCaYm/WX9ZlDlwDSUl7IMOMCQQCrAe37hEP/18w0hdIFr3nP+A7QDXN11XncffhNcl119WQ12LUBaXwjMQFWqzKBlT4Gs7RCgNr5IMsrb6L7/OarAkEAzrm2gRnFPJiFcml/Go6rTwugstRwc5Ul3hpiJOR87CewmcIV2lmmd0I4wt+BAyFhdBxHbq43WKLiaUOr9wLM+QJASV58kor++cfGj0pS/6l34+iTwmPjA81hiV5qqB/HRZLROeEOV9rALgEah+rPspUrlYiIcHgEexq80JgHH4I0HQJAf35qho2FAXQkjgerfOyWd+b58ejU45Jle+jdEs4x3FW7vug8RKwZ/2q6JaOx1onlzPAOWAWqAX7uLNMulmPmFQ==',
  //回调地址
  'callback_url' => $notify_url = SHOP_SITE_URL."/api/payment/lakala/callback.php",
  'notify_url' => SHOP_SITE_URL."/api/payment/lakala/notify.php",

  //支付、订单查询地址
  'ppayGateUrl' => 'https://intl.lakala.com:7777/ppayGate/CrossBorderWebPay.do',

);
include(_PATH_.'/common/function.php');
include(_PATH_.'/HttpClient.class.php');
//POST数据
$post = $_POST;$_POST = null;
$config['pk'] = rsa_ges($config['public_key'], 'public');
$config['rk'] = rsa_ges($config['private_key'], 'private');

?>
