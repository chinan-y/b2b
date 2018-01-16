<?php

require_once(dirname(__DIR__) . '/comm/config.php');
require_once(dirname(__DIR__) . '/comm/utils.php');

function get_user_info()
{
    $graph_url = "https://api.weixin.qq.com/sns/userinfo?access_token="
        . $_SESSION['access_token']. '&openid=' . $_SESSION['openid'];

    $response  = get_url_contents($graph_url);
    $result = json_decode($response, true);
    if (!empty($result['errcode'])) {
        echo "<h3>error:</h3>" . $result['errcode'];
        echo "<h3>msg  :</h3>" . $result['errmsg'];
        exit;
    }
    $arr = $result;
    return $arr;
}

?>
