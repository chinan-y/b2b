<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div class="ncc-main">




<!DOCTYPE html><html><head><title>光彩全球三方网站合作技术规范</title><meta charset='utf-8'><link href='https://dn-maxiang.qbox.me/res-min/themes/marxico.css' rel='stylesheet'><style></style></head><body><div id='preview-contents' class='note-content'>
                        <div id="wmd-preview" class="preview-content"></div>
                    <div id="wmd-preview-section-1" class="wmd-preview-section preview-content">

</div><div id="wmd-preview-section-2" class="wmd-preview-section preview-content">

<h3 id="光彩全球三方网站合作技术规范">光彩全球三方网站合作技术规范</h3>

</div><div id="wmd-preview-section-25" class="wmd-preview-section preview-content">

<h6 id="本文档适用于第三方平台和光彩全球之间技术对接参考标准">本文档适用于第三方平台和光彩全球之间技术对接参考标准</h6>

<p>1、应用场景 <br>
1.1、在其他电商网站与我司谈成业务合作后，方可与我司进行业务对接。在对接过程中，我司将会为你的站点生成对应的APPKEY。此APPKEY作为连接我司服务的必要凭证,非授权用户将无法使用我司服务及其接口应用。 <br>
1.2、在使用服务及其应用过程中，如果遇到问题可以与光彩全球技术部门联系。我司将协助解决、调试、验证等相关问题。联系方式：502808966@qq.com。</p>

<p>2、是否需要授权及其相关预设 <br>
2.1、接口是需要授权的，请联系光彩全球技术部们获取相应的APPID和APPKEY。其中APPKEY是客户端和服务器端用于MD5处理传递参数的密钥，服务器和客户端用APPKEY加密传递过程中的数据来验证来源是否正确（防止数据被中途篡改）。 <br>
2.2、需要与光彩全球约定返回地址。返回地址包含显式返回地址和隐式返回地址，具体详情参见下文。</p>

<p>3、请求地址 <br>
<a href="http://qqbsmall/gcshop/api.html" target="_blank">http://qqbsmall/gcshop/api.html</a>? <br>
（如：<a href="https://www.qqbsmall.com/gcshop/api.html?gct=cart&amp;gp=api&amp;auth_type=mobile&amp;auth_value=15528309540&amp;goods_info=GC8809464990047|2;GC8809320938633|1&amp;trade_no=1604260000001&amp;APPID=13&amp;sign=48b8dd35ac40fb140c4920e74d885e11" target="_blank">https://www.qqbsmall.com/gcshop/api.html?gct=cart&amp;gp=api&amp;auth_type=mobile&amp;auth_value=15528309540&amp;goods_info=GC8809464990047|2;GC8809320938633|1&amp;trade_no=1604260000001&amp;APPID=13&amp;sign=48b8dd35ac40fb140c4920e74d885e11</a>  使用时请先将上述地址中的 特殊符号  替换成相应的值）</p>

<p>4、请求方式 <br>
在本系统中所有参数均已GET方式提交，如有特殊情况，将标明以POST或者其它方式提交，传递参数过程中只能使用UTF-8统一编码。</p></div><div id="wmd-preview-section-15" class="wmd-preview-section preview-content">

<h6 id="特别注明请求参数大小写敏感">特别注明：请求参数大小写敏感</h6>

<p>5、通用输入参数解释</p>

<table>
<thead>
<tr>
  <th align="center">名称</th>
  <th align="left">字类型</th>
  <th align="left">是否必需</th>
  <th align="center">描述</th>
  <th align="center">额外</th>
</tr>
</thead>
<tbody><tr>
  <td align="center">gct</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">控制器</td>
  <td align="center">接口中的控制器（如login、cart、buy、pay等）</td>
</tr>
<tr>
  <td align="center">gp</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">控制器方法</td>
  <td align="center">控制器中响应操作的处理方法（如auth、buy_step2等）</td>
</tr>
<tr>
  <td align="center">APPID</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">APPID</td>
  <td align="center">光彩全球中三方平台唯一标识</td>
</tr>
<tr>
  <td align="center">sign</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">处理后加密串</td>
  <td align="center">用于验证请求的合法性，详情参见解释</td>
</tr>
</tbody></table>


<p>其中sign生成方法可以参见如下：</p>

</div><div id="wmd-preview-section-16" class="wmd-preview-section preview-content">

<pre class="prettyprint hljs-dark"><code class="hljs xquery">&lt;?php<br>/**<br> * 定义接口段基础类<br> *<br> * <br> */<br>class gcclient{<br><br>    /**<br>     *网关地址<br>     */<br>    private <span class="hljs-variable">$server</span>_gateway = <span class="hljs-string">'https://www.qqbsmall.com/gcshop/api.html?'</span>;<br><br>    /**<br>     * 消息验证地址<br>     *<br>     * @var string<br>     */<br>    private <span class="hljs-variable">$server</span>_verify_url = <span class="hljs-string">'https://www.qqbsmall.com/gcshop/api.html?'</span>;<br><br>    /**<br>     * 接口标识<br>     *<br>     * @var string<br>     */<br>    private <span class="hljs-variable">$code</span> = <span class="hljs-string">'server'</span>;<br><br>    /**<br>     * 发送至的参数<br>     *<br>     * @var array<br>     */<br>    public <span class="hljs-variable">$parameter</span>;<br><br>    public function __construct(){<br>        <span class="hljs-variable">$this-</span>&gt;parameter[<span class="hljs-string">'APPKEY'</span>] = <span class="hljs-string">'1234567890'</span>;<br>        <span class="hljs-variable">$this-</span>&gt;parameter[<span class="hljs-string">'sign_type'</span>] = <span class="hljs-string">'MD5'</span>;<br>    }<br><br>    /**<br>     * <br>     *<br>     * @param string <span class="hljs-variable">$name</span><br>     * @return <br>     */<br>    public function __get(<span class="hljs-variable">$name</span>){<br>        return <span class="hljs-variable">$this-</span>&gt;<span class="hljs-variable">$name</span>;<br>    }<br><br>    /**<br>     * 远程获取数据<br>     * <span class="hljs-variable">$url</span> 指定URL完整路径地址<br>     * @param <span class="hljs-variable">$time</span>_out 超时时间。默认值：<span class="hljs-number">60</span><br>     * return 远程输出的数据<br>     */<br>    public function getHttpResponse(<span class="hljs-variable">$url</span>,<span class="hljs-variable">$time</span>_out = <span class="hljs-string">'60'</span>) {<br>        <span class="hljs-variable">$urlarr</span>     = parse_url(<span class="hljs-variable">$url</span>);<br>        <span class="hljs-variable">$errno</span>      = <span class="hljs-string">''</span>;<br>        <span class="hljs-variable">$errstr</span>     = <span class="hljs-string">''</span>;<br>        <span class="hljs-variable">$transports</span> = <span class="hljs-string">''</span>;<br>        <span class="hljs-variable">$responseText</span> = <span class="hljs-string">''</span>;<br>        if(<span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'scheme'</span>] == <span class="hljs-string">'https'</span>) {<br>            <span class="hljs-variable">$transports</span> = <span class="hljs-string">'ssl://'</span>;<br>            <span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'port'</span>] = <span class="hljs-string">'443'</span>;<br>        } else {<br>            <span class="hljs-variable">$transports</span> = <span class="hljs-string">'tcp://'</span>;<br>            <span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'port'</span>] = <span class="hljs-string">'80'</span>;<br>        }<br>        <span class="hljs-variable">$fp</span>=@fsockopen(<span class="hljs-variable">$transports</span> . <span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'host'</span>],<span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'port'</span>],<span class="hljs-variable">$errno</span>,<span class="hljs-variable">$errstr</span>,<span class="hljs-variable">$time</span>_out);<br>        if(!<span class="hljs-variable">$fp</span>) {<br>            die(<span class="hljs-string">"ERROR: $errno - $errstr&lt;br /&gt;\n"</span>);<br>        } else {<br>            if (trim(CHARSET) == <span class="hljs-string">''</span>) {<br>                fputs(<span class="hljs-variable">$fp</span>, <span class="hljs-string">'POST '</span>.<span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'path'</span>].<span class="hljs-string">" HTTP/1.1\r\n"</span>);<br>            } else {<br>                fputs(<span class="hljs-variable">$fp</span>, <span class="hljs-string">'POST '</span>.<span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'path'</span>].<span class="hljs-string">'?_input_charset='</span>.CHARSET.<span class="hljs-string">" HTTP/1.1\r\n"</span>);<br>            }<br>            fputs(<span class="hljs-variable">$fp</span>, <span class="hljs-string">'Host: '</span>.<span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'host'</span>].<span class="hljs-string">"\r\n"</span>);<br>            fputs(<span class="hljs-variable">$fp</span>, <span class="hljs-string">"Content-type: application/x-www-form-urlencoded\r\n"</span>);<br>            fputs(<span class="hljs-variable">$fp</span>, <span class="hljs-string">'Content-length: '</span>.strlen(<span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'query'</span>]).<span class="hljs-string">"\r\n"</span>);<br>            fputs(<span class="hljs-variable">$fp</span>, <span class="hljs-string">"Connection: close\r\n\r\n"</span>);<br>            fputs(<span class="hljs-variable">$fp</span>, <span class="hljs-variable">$urlarr</span>[<span class="hljs-string">'query'</span>] . <span class="hljs-string">"\r\n\r\n"</span>);<br>            while(!feof(<span class="hljs-variable">$fp</span>)) {<br>                <span class="hljs-variable">$responseText</span> .= @fgets(<span class="hljs-variable">$fp</span>, <span class="hljs-number">1024</span>);<br>            }<br>            fclose(<span class="hljs-variable">$fp</span>);<br>            <span class="hljs-variable">$responseText</span> = trim(stristr(<span class="hljs-variable">$responseText</span>,<span class="hljs-string">"\r\n\r\n"</span>),<span class="hljs-string">"\r\n"</span>);<br>            return <span class="hljs-variable">$responseText</span>;<br>        }<br>    }<br><br>    /**<br>     * 制作接口的请求地址<br>     *<br>     * @return string<br>     */<br>    public function create_url() {<br>        <span class="hljs-variable">$url</span>        = <span class="hljs-variable">$this-</span>&gt;server_gateway;<br>        <span class="hljs-variable">$filtered</span>_array    = <span class="hljs-variable">$this-</span>&gt;para_filter(<span class="hljs-variable">$this-</span>&gt;parameter);<br>        <span class="hljs-variable">$sort</span>_array = <span class="hljs-variable">$this-</span>&gt;arg_sort(<span class="hljs-variable">$filtered</span>_array);<br>        <span class="hljs-variable">$arg</span>        = <span class="hljs-string">''</span>;<br>        while (list (<span class="hljs-variable">$key</span>, <span class="hljs-variable">$val</span>) = each (<span class="hljs-variable">$sort</span>_array)) {<br>            <span class="hljs-variable">$arg</span>.=<span class="hljs-variable">$key</span>.<span class="hljs-string">'='</span>.urlencode(<span class="hljs-variable">$val</span>).<span class="hljs-string">'&amp;'</span>;<br>        }<br>        <span class="hljs-variable">$url</span>.= <span class="hljs-variable">$arg</span>.<span class="hljs-string">'sign='</span> .<span class="hljs-variable">$this-</span>&gt;parameter[<span class="hljs-string">'sign'</span>] .<span class="hljs-string">'&amp;sign_type='</span>.<span class="hljs-variable">$this-</span>&gt;parameter[<span class="hljs-string">'sign_type'</span>];<br>        return <span class="hljs-variable">$url</span>;<br>    }<br><br>    /**<br>     * 取得签名<br>     *<br>     * @return string<br>     */<br>    public function sign(<span class="hljs-variable">$parameter</span>) {<br>        <span class="hljs-variable">$parameter</span>[<span class="hljs-string">'sign_type'</span>] = isset(<span class="hljs-variable">$parameter</span>[<span class="hljs-string">'sign_type'</span>]) ? <span class="hljs-variable">$parameter</span>[<span class="hljs-string">'sign_type'</span>] : <span class="hljs-string">'MD5'</span>;<br>        <span class="hljs-variable">$mysign</span> = <span class="hljs-string">''</span>;<br><br>        <span class="hljs-variable">$filtered</span>_array    = <span class="hljs-variable">$this-</span>&gt;para_filter(<span class="hljs-variable">$parameter</span>);<br>        <span class="hljs-variable">$sort</span>_array = <span class="hljs-variable">$this-</span>&gt;arg_sort(<span class="hljs-variable">$filtered</span>_array);<br>        <span class="hljs-variable">$arg</span> = <span class="hljs-string">''</span>;<br>        while (list (<span class="hljs-variable">$key</span>, <span class="hljs-variable">$val</span>) = each (<span class="hljs-variable">$sort</span>_array)) {<br>            <span class="hljs-variable">$arg</span>   .= <span class="hljs-variable">$key</span>.<span class="hljs-string">'='</span>.<span class="hljs-variable">$this-</span>&gt;charset_encode(<span class="hljs-variable">$val</span>,(empty(<span class="hljs-variable">$parameter</span>[<span class="hljs-string">'_input_charset'</span>])?<span class="hljs-string">'UTF-8'</span>:<span class="hljs-variable">$parameter</span>[<span class="hljs-string">'_input_charset'</span>]),(empty(<span class="hljs-variable">$parameter</span>[<span class="hljs-string">'_input_charset'</span>])?<span class="hljs-string">'UTF-8'</span>:<span class="hljs-variable">$parameter</span>[<span class="hljs-string">'_input_charset'</span>])).<span class="hljs-string">'&amp;'</span>;<br>        }<br>        <span class="hljs-variable">$prestr</span> = substr(<span class="hljs-variable">$arg</span>,<span class="hljs-number">0</span>,-<span class="hljs-number">1</span>);  //去掉最后一个&amp;号<br>        <span class="hljs-variable">$prestr</span>    .= <span class="hljs-variable">$parameter</span>[<span class="hljs-string">'key'</span>];<br>        if(<span class="hljs-variable">$parameter</span>[<span class="hljs-string">'sign_type'</span>] == <span class="hljs-string">'MD5'</span>) {<br>            <span class="hljs-variable">$mysign</span> = md5(<span class="hljs-variable">$prestr</span>);<br>        }elseif(<span class="hljs-variable">$parameter</span>[<span class="hljs-string">'sign_type'</span>] ==<span class="hljs-string">'DSA'</span>) {<br>            //DSA 签名方法待后续开发<br>            die(<span class="hljs-string">'DSA 签名方法待后续开发，请先使用MD5签名方式'</span>);<br>        }else {<br>            die(<span class="hljs-string">'暂不支持'</span>.<span class="hljs-variable">$parameter</span>[<span class="hljs-string">'sign_type'</span>].<span class="hljs-string">'类型的签名方式'</span>);<br>        }<br>        return <span class="hljs-variable">$mysign</span>;<br><br>    }<br><br>    /**<br>     * 除去数组中的空值和签名模式<br>     *<br>     * @param array <span class="hljs-variable">$parameter</span><br>     * @return array<br>     */<br>    public function para_filter(<span class="hljs-variable">$parameter</span>) {<br>        <span class="hljs-variable">$para</span> = array();<br>        while (list (<span class="hljs-variable">$key</span>, <span class="hljs-variable">$val</span>) = each (<span class="hljs-variable">$parameter</span>)) {<br>            if(<span class="hljs-variable">$key</span> == <span class="hljs-string">'sign'</span> || <span class="hljs-variable">$key</span> == <span class="hljs-string">'sign_type'</span> || <span class="hljs-variable">$key</span> == <span class="hljs-string">'key'</span> || <span class="hljs-variable">$val</span> == <span class="hljs-string">''</span>)continue;<br>            else    <span class="hljs-variable">$para</span>[<span class="hljs-variable">$key</span>] = <span class="hljs-variable">$parameter</span>[<span class="hljs-variable">$key</span>];<br>        }<br>        return <span class="hljs-variable">$para</span>;<br>    }<br><br>    /**<br>     * 重新排序参数数组<br>     *<br>     * @param array <span class="hljs-variable">$array</span><br>     * @return array<br>     */<br>    public function arg_sort(<span class="hljs-variable">$array</span>) {<br>        ksort(<span class="hljs-variable">$array</span>);<br>        reset(<span class="hljs-variable">$array</span>);<br>        return <span class="hljs-variable">$array</span>;<br><br>    }<br><br>    /**<br>     * 实现多种字符编码方式<br>     */<br>    public function charset_encode(<span class="hljs-variable">$input</span>,$_output_charset,$_input_charset=<span class="hljs-string">'UTF-8'</span>) {<br>        <span class="hljs-variable">$output</span> = <span class="hljs-string">''</span>;<br>        if(!isset($_output_charset))$_output_charset  = <span class="hljs-variable">$this-</span>&gt;parameter[<span class="hljs-string">'_input_charset'</span>];<br>        if($_input_charset == $_output_charset || <span class="hljs-variable">$input</span> == null) {<br>            <span class="hljs-variable">$output</span> = <span class="hljs-variable">$input</span>;<br>        } elseif (function_exists(<span class="hljs-string">'mb_convert_encoding'</span>)){<br>            <span class="hljs-variable">$output</span> = mb_convert_encoding(<span class="hljs-variable">$input</span>,$_output_charset,$_input_charset);<br>        } elseif(function_exists(<span class="hljs-string">'iconv'</span>)) {<br>            <span class="hljs-variable">$output</span> = iconv($_input_charset,$_output_charset,<span class="hljs-variable">$input</span>);<br>        } else die(<span class="hljs-string">'sorry, you have no libs support for charset change.'</span>);<br>        return <span class="hljs-variable">$output</span>;<br>    }<br>}<br><br><br>//验证sign是否正确<br><span class="hljs-variable">$parameter</span> = <span class="hljs-keyword">array</span>(<br>    <span class="hljs-string">'gct'</span>=&gt;<span class="hljs-variable">$gct</span>,<br>    <span class="hljs-string">'gp'</span>=&gt;<span class="hljs-variable">$gp</span>,<br>    <span class="hljs-string">'auth_type'</span>=&gt;<span class="hljs-variable">$auth</span>_type,<br>    <span class="hljs-string">'auth_value'</span>=&gt;<span class="hljs-variable">$auth</span>_<span class="hljs-keyword">value</span>,<br>    <span class="hljs-string">'goods_info'</span>=&gt;<span class="hljs-variable">$goods</span>_info,<br>    <span class="hljs-string">'APPID'</span>=&gt;<span class="hljs-variable">$APPID</span>,<br>    <span class="hljs-string">'sign'</span>=&gt;<span class="hljs-variable">$sign</span>,<br>);<br><span class="hljs-variable">$client</span> = new gcclient();<br><span class="hljs-variable">$client-</span>&gt;parameter[<span class="hljs-string">'APPKEY'</span>] = <span class="hljs-string">'c23f42b3f10bea6ddfaad48ab0e01adc'</span>;<br><span class="hljs-variable">$client-</span>&gt;parameter[<span class="hljs-string">'sign_type'</span>] = <span class="hljs-string">'MD5'</span>;<br><span class="hljs-variable">$sign</span>_string = <span class="hljs-variable">$client-</span>&gt;sign(<span class="hljs-variable">$parameter</span>);<br><span class="hljs-keyword">if</span>(<span class="hljs-variable">$sign</span>_string == $_GET[<span class="hljs-string">'sign'</span>]){<br>    //@tudo 处理请求<br>}<span class="hljs-keyword">else</span>{<br>    echo <span class="hljs-string">'签名错误或者加密错误'</span>;die;<br>}<br>?&gt;<br></code></pre>

</div><div id="wmd-preview-section-17" class="wmd-preview-section preview-content">

<h3 id="详细接口规范">详细接口规范</h3>

</div><div id="wmd-preview-section-18" class="wmd-preview-section preview-content">

<h6 id="详细接口规范是在遵循通用规范的前提下执行的如果有特殊情况将会注明">详细接口规范是在遵循通用规范的前提下执行的，如果有特殊情况将会注明</h6>

<p>1、数据生成订单</p>

</div><div id="wmd-preview-section-19" class="wmd-preview-section preview-content">

<h6 id="当前接口处于验证阶段暂未投入生成应用">当前接口处于验证阶段，暂未投入生成应用</h6>

<table>
<thead>
<tr>
  <th align="center">名称</th>
  <th align="left">字类型</th>
  <th align="left">是否必需</th>
  <th align="center">描述</th>
  <th align="center">额外</th>
</tr>
</thead>
<tbody><tr>
  <td align="center">gct</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">buy</td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center">gp</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">buy_step1</td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center">APPID</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">APPID</td>
  <td align="center">光彩全球中三方平台唯一标识</td>
</tr>
<tr>
  <td align="center">sign</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">d22704fa8719a996fe61e5d32ad8c5e7</td>
  <td align="center"></td>
</tr>
<tr>
  <td align="center">auth_type</td>
  <td align="left">string</td>
  <td align="left">yes</td>
  <td align="center">mobile</td>
  <td align="center">验证三方注册用户类型</td>
</tr>
<tr>
  <td align="center">auth_value</td>
  <td align="left">string</td>
  <td align="left">yes</td>
  <td align="center">+86155****9537</td>
  <td align="center">三方注册用户主键</td>
</tr>
<tr>
  <td align="center">goods_info</td>
  <td align="left">string</td>
  <td align="left">GC8809464990047|2;GC8809320938633|1</td>
  <td align="center">商品信息</td>
  <td align="center">二维数组包含商品货号，购买数量</td>
</tr>
<tr>
  <td align="center">trade_no</td>
  <td align="left">string</td>
  <td align="left">1604260000001</td>
  <td align="center">三方平台订单号</td>
  <td align="center">长度小于50的三方订单号，用回支付回调中的相关主键</td>
</tr>
</tbody></table>


<p>订单生成实例：服务器生成如下地址，让客户端浏览器跳转到如下地址即可完成后续过程 <br>
<a href="https://www.qqbsmall.com/gcshop/api.html?gct=cart&amp;gp=api&amp;auth_type=mobile&amp;auth_value=15528309540&amp;goods_info=GC8809464990047|2;GC8809320938633|1&amp;trade_no=1604260000001&amp;APPID=13&amp;sign=48b8dd35ac40fb140c4920e74d885e11" target="_blank">https://www.qqbsmall.com/gcshop/api.html?gct=cart&amp;gp=api&amp;auth_type=mobile&amp;auth_value=15528309540&amp;goods_info=GC8809464990047|2;GC8809320938633|1&amp;trade_no=1604260000001&amp;APPID=13&amp;sign=48b8dd35ac40fb140c4920e74d885e11</a></p>

<p>2、支付结果 <br>
支付结果分为显式返回和隐式返回 <br>
2.1、显示返回地址：当用户支付完成后，用户浏览器会根据该地址跳转三方平台提示支付完成，三方平台需按自己的需求做出相应提示。</p>

</div><div id="wmd-preview-section-20" class="wmd-preview-section preview-content">

<h6 id="此地址不作为数据安全的凭证如需确认支付有效的请等待隐式地址请求验证数据">此地址不作为数据安全的凭证，如需确认支付有效的请等待隐式地址请求验证数据</h6>

<p>2.2、隐式返回地址：当用户支付完成后，光彩全球服务器会按照间隔 0 ，5，10，30，60，300秒时间间隔给预设的隐式地址发送相关请求，直到三方平台服务器返回”true”为止。</p>

</div><div id="wmd-preview-section-43" class="wmd-preview-section preview-content">

<h6 id="光彩全球服务器请求三方平台数据格式如下显式返回和隐式返回光彩全球系统参数相同">光彩全球服务器请求三方平台数据格式如下（显式返回和隐式返回光彩全球系统参数相同）</h6>

<table>
<thead>
<tr>
  <th align="center">名称</th>
  <th align="left">字类型</th>
  <th align="left">是否必需</th>
  <th align="center">描述</th>
  <th align="center">额外</th>
</tr>
</thead>
<tbody><tr>
  <td align="center">sign</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">处理后加密串</td>
  <td align="center">本处为光彩全球请求三方api的加密字符串，  请三方系统中务必验证当前接口的正确性，  以避免该接口被非法请求</td>
</tr>
<tr>
  <td align="center">out_auth_type</td>
  <td align="left">string</td>
  <td align="left">yes</td>
  <td align="center">mobile</td>
  <td align="center">三方平台注册用户验证类型</td>
</tr>
<tr>
  <td align="center">out_auth_value</td>
  <td align="left">string</td>
  <td align="left">yes</td>
  <td align="center">+86155****9537</td>
  <td align="center">三方平台注册用户主键</td>
</tr>
<tr>
  <td align="center">out_member_id</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">123456</td>
  <td align="center">光彩全球中用户ID</td>
</tr>
<tr>
  <td align="center">out_trade_no</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">7000000000005101</td>
  <td align="center">光彩全球系统中的订单号码</td>
</tr>
<tr>
  <td align="center">out_trade_state</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">WAIT_SELLER_SEND_GOODS</td>
  <td align="center">订单状态</td>
</tr>
<tr>
  <td align="center">out_pay_no</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">590488389227277002</td>
  <td align="center">外部支付单号</td>
</tr>
<tr>
  <td align="center">trade_no</td>
  <td align="left">string</td>
  <td align="left">YES</td>
  <td align="center">1604260000001</td>
  <td align="center">三方平台订单号</td>
</tr>
</tbody></table>


<p>out_trade_state对应状态有 WAIT_SELLER_SEND_GOODS、TRADE_FINISHED、TRADE_SUCCESS、TRADE_NOT_EXIST、具体待定</p></div><div id="wmd-preview-section-11" class="wmd-preview-section preview-content"></div><div id="wmd-preview-section-footnotes" class="preview-content"></div></div></body></html>





</div>