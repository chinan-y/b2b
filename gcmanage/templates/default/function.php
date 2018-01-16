<?php
/**
 * 函数
 * Created by Ming
 */

 //抛送接口
function _curl_post($url,$data){
    $data = http_build_query($data);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    $response = curl_exec($ch);
    if(curl_errno($ch))
    {
        print curl_error($ch);
    }
    curl_close($ch);
    return $response;
}


function arrtoxml($arr,$dom=0,$item=0){
    if (!$dom){
        $dom = new DOMDocument("1.0");
    }
    if(!$item){
        $item = $dom->createElement("root");
        $dom->appendChild($item);
    }
    foreach ($arr as $key=>$val){
        $itemx = $dom->createElement(is_string($key)?$key:"item");
        $item->appendChild($itemx);
        if (!is_array($val)){
            $text = $dom->createTextNode($val);
            $itemx->appendChild($text);

        }else {
            arrtoxml($val,$dom,$itemx);
        }
    }
    return $dom->saveXML();
}

	//生成唯一消息ID
function guid(){
    if (function_exists('com_create_guid')){
        $uuid =  com_create_guid();
    }else{
        mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
        $charid = strtoupper(md5(uniqid(rand(), true)));
        $hyphen = chr(45);// "-"
        $uuid = chr(123)// "{"
            .substr($charid, 0, 8).$hyphen
            .substr($charid, 8, 4).$hyphen
            .substr($charid,12, 4).$hyphen
            .substr($charid,16, 4).$hyphen
            .substr($charid,20,12)
            .chr(125);// "}"
    }
    return str_replace(array('{','}'),'',$uuid);
}
