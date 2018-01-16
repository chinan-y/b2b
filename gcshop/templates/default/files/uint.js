var qu_car=1;
var vh=$(window).height();
var vw=$(window).width();
var page_h=document.domain;
var page_t=$(document).attr("title");
var isIE6=navigator.appVersion.indexOf("MSIE 6")>-1;
var pez="top=100,left=100,width=735,height=402,location=yes,menubar=no,resizable=yes,scrollbars=yes,status=no,toolbar=no";
function changeTab(lis,divs,cur){lis.each(function(i){var els=$(this);els.mouseenter(function(){lis.removeClass(cur);divs.stop().hide();$(this).addClass(cur);divs.eq(i).show()})})}
(function(a){a.fn.Qlazyload=function(b,c){if(this.length){var d,e,f=a.extend({type:null,offsetParent:null,source:"src2",threshold:100},b||{}),g=this,h=function(a){for(var b=a.scrollLeft,c=a.scrollTop,d=a.offsetWidth,e=a.offsetHeight;a.offsetParent;)b+=a.offsetLeft,c+=a.offsetTop,a=a.offsetParent;return{left:b,top:c,width:d,height:e}},i=function(){var a=document.documentElement,b=document.body,c=window.pageXOffset?window.pageXOffset:a.scrollLeft||b.scrollLeft,d=window.pageYOffset?window.pageYOffset:a.scrollTop||b.scrollTop,e=a.clientWidth,f=a.clientHeight;return{left:c,top:d,width:e,height:f}},j=function(a,b){var c,d,e,g,h,i,j=f.threshold?parseInt(f.threshold):0;return c=a.left+a.width/2,d=b.left+b.width/2,e=a.top+a.height/2,g=b.top+b.height/2,h=(a.width+b.width)/2,i=(a.height+b.height)/2,Math.abs(c-d)<h+j&&Math.abs(e-g)<i+j},k=function(a,b,d){a&&(d.attr("src",b).removeAttr(f.source),c&&c(b,d))},l=function(b,d,e){if(b){var g=a("#"+d);g.html(e.val()).removeAttr(f.source),e.remove(),c&&c(d,e)}},m=function(a,b,d){a&&(d.removeAttr(f.source),c&&c(b,d))},n=function(){e=i(),g=g.filter(function(){return a(this).attr(f.source)}),a.each(g,function(){var b=a(this).attr(f.source);if(b){var c=f.offsetParent?h(a(f.offsetParent).get(0)):e,d=h(this),g=j(c,d);switch(f.type){case"image":k(g,b,a(this));break;case"textarea":l(g,b,a(this));break;case"module":m(g,b,a(this))}}})},o=function(){g.length>0&&(clearTimeout(d),d=setTimeout(function(){n()},10))};n(),f.offsetParent?a(f.offsetParent).bind("scroll",function(){o()}):a(window).bind("scroll",function(){o()}).bind("reset",function(){o()})}}})(jQuery);
(function($){var ajax = $.ajax;var pendingRequests = {};var synced=[];var syncedData =[];$.ajax = function(settings){settings=jQuery.extend(settings,jQuery.extend({},jQuery.ajaxSettings, settings));var port=settings.port;switch(settings.mode) {case "abort": if(pendingRequests[port]){pendingRequests[port].abort();}return pendingRequests[port] = ajax.apply(this, arguments);}return ajax.apply(this, arguments);};})(jQuery);
function scheck(obj){var w=obj.keywords.value;if(w==""||w==null){alert("请输入关键字!");return false}}
function addcollect(){var ctrl=(navigator.userAgent.toLowerCase()).indexOf('mac')!=-1?'Command/Cmd':'CTRL';if(document.all){window.external.addFavorite('http://www.xgqqg.com','西港全球购')}else if(window.sidebar){window.sidebar.addPanel('西港全球购','http://www.xgqqg.com',"")}else{alert('您可以尝试通过快捷键'+ctrl+' + D 加入到收藏夹~')}}
function qq(){window.open('http://wpa.b.qq.com/cgi/wpa.php?ln=1&key=XzgwMDA5ODkyNV8xNzgzMzJfODAwMDk4OTI1XzJf','在线客服',pez)}
function updateC(){if(qu_car==1){$.ajax({url:'/flow.php?step=shopping_cart',type:'GET',dataType:'html',beforeSend:function(){$("#acar").html("<p class='alC lh35'>加载中，请稍后...</p>")},error:function(){alert('Error')},success:function(data){$("#acar").html(data);if(data.indexOf("空的")!=-1){$("#qcar").html(0)}qu_car=0}})}}function gotop(){$('html,body').stop().animate({scrollTop:'0px'},600)}
function obj2str(o){var r=[];if(typeof o=="string"){return "\"" + o.replace(/([\'\"\\])/g, "\\$1").replace(/(\n)/g, "\\n").replace(/(\r)/g, "\\r").replace(/(\t)/g, "\\t") + "\"";}if(typeof o=="undefined"){return "undefined";}if(typeof o=="object"){if(o===null){return "null";}else if(!o.sort){for(var i in o){if (i!="toJSONString"){r.push("\"" + i + "\"" + ":" + obj2str(o[i]));}}r="{"+r.join()+"}";}else{for(var i= 0;i<o.length;i++) r.push(obj2str(o[i]));r="["+r.join()+"]";}return r;}return o.toString();}
function sendHashMail(){$.ajax({url:'/user.php?gct=send_hash_mail',type:'GET',dataType:'json',error:function(){alert('Error')},success:function(data){alert(data.message)}})}function getSelectedAttributes(formBuy){var spec_arr=new Array();var j=0;for(i=0;i<formBuy.elements.length;i++){var prefix=formBuy.elements[i].name.substr(0,5);if(prefix=='spec_'&&(((formBuy.elements[i].type=='radio'||formBuy.elements[i].type=='checkbox')&&formBuy.elements[i].checked)||formBuy.elements[i].tagName=='SELECT')){spec_arr[j]=formBuy.elements[i].value;j++}}return spec_arr}
function addToCart(goodsId,parentId,one,isgo){
$.get('/user.php?gct=get_user_id',function(data){
    if(data.code == 0){layerLogin(); }else{var goods=new Object();var spec_arr=new Array();var fittings_arr=new Array();var number=1;var formBuy=document.forms['ECS_FORMBUY'];var quick=0;if(formBuy){spec_arr=getSelectedAttributes(formBuy);if(formBuy.elements['number']){number=formBuy.elements['number'].value}quick=1}goods.quick=quick;goods.spec=spec_arr;goods.goods_id=goodsId;goods.number=number;goods.isgo=(isgo)?"1":"0";goods.parent=(typeof(parentId)=="undefined")?0:parseInt(parentId);$.post('/flow.php',{step:'add_to_cart',one:one,goods:obj2str(goods)},function(res){if(res.error>0){if(res.error==2){if(confirm(res.message)){alert("对不起该商品缺货，敬请等待...")}}else if(res.error==3){if(confirm(res.message)){window.location.href='/flow.php?step=checkout';}}else if(res.error==6){alert("请选择商品规格")}else{alert(res.message)}}else{if(isgo){qwToCart()}else{cart_url='/flow.php?step=checkout';location.href=cart_url}}},'JSON'); }
},"JSON");}
function pclose(){$("#pbox").hide();$("#mask").hide();}
function addPackageToCart(packageId){var package_info = new Object();var number = 1;package_info.package_id = packageId;package_info.number = number;
$.post('/flow.php',{step:'add_package_to_cart',package_info:obj2str(package_info)},function(result){
if(result.error > 0){if(result.error == 2){if(confirm(result.message)){location.href='/user.php?gct=add_booking&id=' + result.goods_id;}}else{ alert(result.message);}}
else{var cart_url ='/flow.php?step=checkout';location.href = cart_url;}},'JSON');}
function lgout(){$.get('/user.php',{gct:'ajax_logout'},function(data){if(data=="TRUE"){location.reload();}},'TEXT');}
function ie6fix(){var ks=$(window).scrollTop();var wh=$(window).height()-370;$("#fixed").css({"top":wh+ks});}
function adfav(goodsId){$.ajax({url: '/user.php?gct=collect&id='+goodsId,type: 'GET',dataType: 'json',success: function(data){alert(data.message)}});}
function bank(code){
    var order_sn = $('#order_sn').text();

    if(order_sn != '' && code != ''){
        $.ajax({
            url: '/flow.php?step=payment&order_sn='+order_sn+'&bank='+code,
            type: 'GET',
            dataType:'json',
            success: function(result){
                if(result.code == 0){
                    $('#sign').html(result.data);
                    $('#bank-list > a').removeClass('on');
                    $('#'+code).addClass('on');
                }else{
                    alert(result.msg);
                }
            }
        });
    }else{
        alert('订单号和银行代码不能为空！');
    }
}
$(function(){
var yvv = $("#keywords");
var cv=yvv.attr("rel");
var $fanv=$("#fnav");
var $lnav=$("#lnav");
var $lnli=$("#lnav li");
$("#search").hover(function(){var yvvs=yvv.val();if(yvvs==cv){yvv.attr("value","")}},function(){var yvvs=yvv.val();if(yvvs==""){yvv.attr("value",cv)}});
yvv.bind("keyup",function(e){var v=$(this).val();if(v==""){$("#r_id").hide();return false}if(e.keyCode==40||e.keyCode==38){return false}
var tmd=setTimeout(function(){$.ajax({url:'/search.php?gct=q&w='+v,type:'GET',dataType:'html',success:function(data){if(data!=""){$("#r_id").show().html(data)}else{$("#r_id").hide()}},mode:'abort'})},500)}).bind("blur",function(){var hmd=setTimeout(function(){$("#r_id").hide()},500)});
$("#tcar").hover(function(){$(this).addClass("hv");updateC()},function(){$(this).removeClass("hv")});
$lnli.hover(function(){$(this).addClass("on");var m=$(this).find("img");var t=m.attr("src3");if(t){m.attr("src",t).removeAttr("src3")}},function(){$(this).removeClass("on")});
$("#tul li.nbt").hover(function(){$(this).addClass("on")},function(){$(this).removeClass("on")});
$fanv.hover(function(){$lnav.show()},function(){$lnav.hide()});
$("img[src2]").Qlazyload({type:"image"});
if(isIE6){document.execCommand("BackgroundImageCache",false,true);}
var fl=$("#fixed").length;
$(window).scroll(function(){if(fl>0&&isIE6){ie6fix();}});
  
  $(".order_content ul li").hover(function(){
        $(this).children(".edit_and_defut").show();
  },function(){
        $(this).children(".edit_and_defut").hide();
  });
});
//发送验证码
var wait=60;
function time(o,is_get) {
    if (wait == 0) {
        o.removeAttribute("disabled");
        o.value="获取验证码";
        $('#tpyzm').click();
        wait = 60;
    } else {
        o.setAttribute("disabled", true);
        o.value="重新发送(" + wait + ")";
        wait--;
        setTimeout(function() {
                time(o,false)
            },
            1000)
    }
    if(is_get == true){
        var phone = $("#mobile_phone").val();
        var yz = $("#captcha").val();
        $.ajax({
            url: '/user.php?gct=get_sms_code&mobile_phone=' + phone + '&captcha=' + yz,
            type: 'GET',
            dataType:'json',
            success: function(result){
                if(result.code == 1){
                    alert(result.msg);
                    wait = 0;
                }else{
                    alert(result.msg);
                }
            }
        });
    }
}

function checkCode(){
    var code = $("#sms_code").val();
    if(code.length != 6 || isNaN(code)){
        alert('请输入6位数字');
        return false;
    }else{
        return true;
    }
}

//编辑收货地址
function edit_address(address_id){
    layer.open({
        type: 2, 
        title:"修改收货地址",
        area: ['500px', '430px'],
        shadeClose: true,
        move:false,
        content: ['/show.php?gct=add_address&address_id='+address_id,'no']
    }); 
};
//设置默认收货地址
function set_defu_addr(address_id,obj){
    $.get('/show.php?gct=set_defu_addr&address_id='+address_id,function(data){
        if(data.code == 'ok'){
            layer.msg(data.msg);
            var li = $(obj).parents("li");
            li.addClass("hover").siblings().removeClass("hover");
            li.children(":first-child").addClass("surname_back");
            li.siblings().children(":first-child").removeClass("surname_back");
        }else{
            layer.msg(data.msg,function(){});
        }
    },"json");
}
//隐藏显示收货地址
function toggle_address(obj){
    $('.hideAddr').toggle();
    $(obj).children().toggleClass("back");
}
//删除收货地址
function delete_address(address_id,obj){
    layer.confirm("亲，确定删除地址吗？",{"title":"提示"},function(){
        $.get('/show.php?gct=delete_address&address_id='+address_id,function(data){

            layer.msg(data.msg);
            if(data.code == 'ok'){
                $(obj).parents("li").fadeOut(200).remove();
            }
        },"json");
    });
}
function open_address_frame(){
        layer.open({
        type: 2, 
        title:"新增收货地址",
        area: ['500px', '430px'],
        shadeClose: true,
        move:false,
        content: ['/show.php?gct=add_address','no'],
        success:function(layero,index){
            var index = parent.layer.getFrameIndex(window.name); //先得到当前iframe层的索引
            parent.layer.close(index); //再执行关闭
        }
    }); 
}
//是否有默认地址&如果输入密码，则校验密码，没有则提示用户添加
function chkAddress(){
   var is_use = $("#use_balance").attr("checked"),pass = $("#confirm_password").val();
   if(is_use == 'checked'){
        if(pass.length == 0){
            layer.msg("亲，请输入您账号的密码！");
            return false;
        }
   }
  $.post("/show.php?gct=get_user_defut_addr",{"use_balance":is_use,"confirm_pass":pass},function(data){
    if(data.code == 0){
        layer.msg(data.msg);
        return false;
    }else{
        $("#theFormDone").submit();
    }
  },'JSON');

}