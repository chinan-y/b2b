<?php
/**
 * 接口公共方法
 *
 * 公共方法
 *
 
 */
defined('GcWebShop') or exit('Access Invalid!');

/**
 * 数据输出
 */
function output_data($datas, $extend_data = array()) {
    $data = array();
    $data['code'] = 200;

    if(!empty($extend_data)) {
        $data = array_merge($data, $extend_data);
    }

    $data['datas'] = $datas;

    if(!empty($_GET['callback'])) {
        echo $_GET['callback'].'('.json_encode($data).')';die;
    } else {
        echo json_encode($data);die;
    }
}

/**
 * 异常输出
 */
function output_error($message, $extend_data = array()) {
    $datas = array('error' => $message);
    output_data($datas, $extend_data);
}

/**
 * 分页输出
 */
function mobile_page($page_count) {
    //输出是否有下一页
    $extend_data = array();
    $current_page = intval($_GET['curpage']);
    if($current_page <= 0) {
        $current_page = 1;
    }
    if($current_page >= $page_count) {
        $extend_data['hasmore'] = false;
    } else {
        $extend_data['hasmore'] = true;
    }
    $extend_data['page_total'] = $page_count;
    return $extend_data;
}


/**
 * 定义接口段基础类
 *
 * 
 */
class gcclient{
	
	/**
	 *网关地址
	 */
	private $server_gateway = 'http://www.qqbsmall.com/gcshop/api.html?';
	
	/**
	 * 消息验证地址
	 *
	 * @var string
	 */
	private $server_verify_url = 'http://www.qqbsmall.com/gcshop/api.html?';
	
	/**
	 * 接口标识
	 *
	 * @var string
	 */
    private $code = 'server';
	
    /**
	 * 发送至的参数
	 *
	 * @var array
	 */
    public $parameter;
	
    public function __construct(){
		$this->parameter['APPKEY'] = '1234567890';
		$this->parameter['sign_type'] = 'MD5';
    }

	/**
	 * 
	 *
	 * @param string $name
	 * @return 
	 */
	public function __get($name){
	    return $this->$name;
	}

	/**
	 * 远程获取数据
	 * $url 指定URL完整路径地址
	 * @param $time_out 超时时间。默认值：60
	 * return 远程输出的数据
	 */
	public function getHttpResponse($url,$time_out = '60') {
		$urlarr     = parse_url($url);
		$errno      = '';
		$errstr     = '';
		$transports = '';
		$responseText = '';
		if($urlarr['scheme'] == 'https') {
			$transports = 'ssl://';
			$urlarr['port'] = '443';
		} else {
			$transports = 'tcp://';
			$urlarr['port'] = '80';
		}
		$fp=@fsockopen($transports . $urlarr['host'],$urlarr['port'],$errno,$errstr,$time_out);
		if(!$fp) {
			die("ERROR: $errno - $errstr<br />\n");
		} else {
			if (trim(CHARSET) == '') {
				fputs($fp, 'POST '.$urlarr['path']." HTTP/1.1\r\n");
			} else {
				fputs($fp, 'POST '.$urlarr['path'].'?_input_charset='.CHARSET." HTTP/1.1\r\n");
			}
			fputs($fp, 'Host: '.$urlarr['host']."\r\n");
			fputs($fp, "Content-type: application/x-www-form-urlencoded\r\n");
			fputs($fp, 'Content-length: '.strlen($urlarr['query'])."\r\n");
			fputs($fp, "Connection: close\r\n\r\n");
			fputs($fp, $urlarr['query'] . "\r\n\r\n");
			while(!feof($fp)) {
				$responseText .= @fgets($fp, 1024);
			}
			fclose($fp);
			$responseText = trim(stristr($responseText,"\r\n\r\n"),"\r\n");
			return $responseText;
		}
	}

    /**
     * 制作接口的请求地址
     *
     * @return string
     */
    public function create_url() {
		$url        = $this->server_gateway;
		$filtered_array	= $this->para_filter($this->parameter);
		$sort_array = $this->arg_sort($filtered_array);
		$arg        = '';
		while (list ($key, $val) = each ($sort_array)) {
			$arg.=$key.'='.urlencode($val).'&';
		}
		$url.= $arg.'sign=' .$this->parameter['sign'] .'&sign_type='.$this->parameter['sign_type'];
		return $url;
	}

	/**
	 * 取得签名
	 *
	 * @return string
	 */
	public function sign($parameter) {
		$parameter['sign_type'] = isset($parameter['sign_type']) ? $parameter['sign_type'] : 'MD5';
		$mysign = '';
		
		$filtered_array	= $this->para_filter($parameter);
		$sort_array = $this->arg_sort($filtered_array);
		$arg = '';
        while (list ($key, $val) = each ($sort_array)) {
			$arg	.= $key.'='.$this->charset_encode($val,(empty($parameter['_input_charset'])?'UTF-8':$parameter['_input_charset']),(empty($parameter['_input_charset'])?'UTF-8':$parameter['_input_charset'])).'&';
		}
		$prestr = substr($arg,0,-1);  //去掉最后一个&号
		$prestr	.= $parameter['key'];
        if($parameter['sign_type'] == 'MD5') {
			$mysign = md5($prestr);
		}elseif($parameter['sign_type'] =='DSA') {
			//DSA 签名方法待后续开发
			die('DSA 签名方法待后续开发，请先使用MD5签名方式');
		}else {
			die('暂不支持'.$parameter['sign_type'].'类型的签名方式');
		}
		return $mysign;

	}

	/**
	 * 除去数组中的空值和签名模式
	 *
	 * @param array $parameter
	 * @return array
	 */
	public function para_filter($parameter) {
		$para = array();
		while (list ($key, $val) = each ($parameter)) {
			if($key == 'sign' || $key == 'sign_type' || $key == 'key' || $val == '')continue;
			else	$para[$key] = $parameter[$key];
		}
		return $para;
	}

	/**
	 * 重新排序参数数组
	 *
	 * @param array $array
	 * @return array
	 */
	public function arg_sort($array) {
		ksort($array);
		reset($array);
		return $array;

	}

	/**
	 * 实现多种字符编码方式
	 */
	public function charset_encode($input,$_output_charset,$_input_charset='UTF-8') {
		$output = '';
		if(!isset($_output_charset))$_output_charset  = $this->parameter['_input_charset'];
		if($_input_charset == $_output_charset || $input == null) {
			$output = $input;
		} elseif (function_exists('mb_convert_encoding')){
			$output = mb_convert_encoding($input,$_output_charset,$_input_charset);
		} elseif(function_exists('iconv')) {
			$output = iconv($_input_charset,$_output_charset,$input);
		} else die('sorry, you have no libs support for charset change.');
		return $output;
	}
}
