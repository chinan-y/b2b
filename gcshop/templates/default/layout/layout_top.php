<style type="text/css">
	#MEIQIA-BTN-HOLDER{bottom: 290px !important;right: 140px !important;}
</style>
<?php defined('GcWebShop') or exit('Access Invalid!');?>
<div id="append_parent"></div>
<div id="ajaxwaitid"></div>

<?php if($loadadv = loadadv(1080)){?>
<!-- 顶部连接位 -->
<div class = "quick_tou"><?php echo $loadadv;?></div>
<?php }?>

<?php if(isset($output['site_notice']['on'])){ if($output['site_notice']['on']){ ?>
<div class ="ncSiteNotice" id="ncSiteNotice">
	<h3>公告：<?php echo $output['site_notice']['content'];?></h3>
</div>
<script>
$(function(){
	setTimeout(function(){
		 $("#ncSiteNotice").animate({'margin-top': '-80px'}, "slow");
	},10000);
})
</script>
<?php } } ?>

<?php if ($output['hidden_nctoolbar'] != 1) {?>
<div id="ncToolbar" class="nc-appbar">
  <div class="nc-appbar-tabs-" id="appBarTabs">
    <!--<?php if ($_SESSION['is_login']) {?>
    <div class="user" nctype="a-barUserInfo">
      <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/></div>
      <p>我</p>
    </div>
    <div class="user-info" nctype="barUserInfo" style="display:none;"><i class="arrow"></i>
      <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/>
        <div class="frame"></div>
      </div>
      <dl>
        <dt>Hi, 
			<?php if($output['member_info']['member_nickname']){?>
				<?php echo $output['member_info']['member_nickname'];?>
			<?php }else{?>
				<?php echo $_SESSION['member_name'];?>
			<?php }?>
		</dt>
        <dd>当前等级：<strong nctype="barMemberGrade"><?php echo $output['member_info']['level_name'];?></strong></dd>
        <dd>当前经验值：<strong nctype="barMemberExp"><?php echo $output['member_info']['member_exppoints'];?></strong></dd>
      </dl>
    </div>
    <?php } else {?>
    <div class="user" nctype="a-barLoginBox">
      <div class="avatar"><img src="<?php echo getMemberAvatar($_SESSION['avatar']);?>"/></div>
      <p>未登录</p>
    </div>
    <div class="user-login-box" nctype="barLoginBox" style="display:none;"> <i class="arrow"></i> <a href="javascript:void(0);" class="close" nctype="close-barLoginBox" title="关闭">X</a>
      <form id="login_form" method="post" action="index.php?gct=login&gp=login" onsubmit="ajaxpost('login_form', '', '', 'onerror')">
        <?php Security::getToken();?>
        <input type="hidden" name="form_submit" value="ok" />
        <input name="nchash" type="hidden" value="<?php echo getNchash('login','index');?>" />
        <dl>
          <dt><strong>登录名</strong></dt>
          <dd>
            <input type="text" class="text" tabindex="1" autocomplete="off"  name="user_name" autofocus >
            <label></label>
          </dd>
        </dl>
        <dl>
          <dt><strong>登录密码</strong><a href="index.php?gct=login&gp=forget_password" target="_blank">忘记登录密码？</a></dt>
          <dd>
            <input tabindex="2" type="password" class="text" name="password" autocomplete="off">
            <label></label>
          </dd>
        </dl>
        <?php if(C('captcha_status_login') == '1') { ?>
        <dl>
          <dt><strong>验证码</strong><a href="javascript:void(0)" class="ml5" onclick="javascript:document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash('login','index');?>&t=' + Math.random();">更换验证码</a></dt>
          <dd>
            <input tabindex="3" type="text" name="captcha" autocomplete="off" class="text w130" id="captcha2" maxlength="4" size="10" />
            <img src="" name="codeimage" border="0" id="codeimage" class="vt">
            <label></label>
          </dd>
        </dl>
        <?php } ?>
        <div class="bottom">
          <input type="submit" class="submit" value="确认">
          <input type="hidden" value="<?php echo $_GET['ref_url']?>" name="ref_url">
          <a href="index.php?gct=login&gp=register&ref_url=<?php echo urlencode($output['ref_url']);?>" target="_blank">注册新用户</a> </div>
      </form>
    </div>
    <?php }?>-->

    

    <div class="content-box" id="content-compare">
      <div class="top">
        <h3>1商品对比</h3>
        <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
      <div id="comparelist"></div>
    </div>
    <div class="content-box" id="content-cart">
      <div class="top">
        <h3>2我的购物车</h3>
        <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
      <div id="rtoolbar_cartlist"></div>
    </div>
	<div class="content-box" id="content-collect">
	  <div class="top">
        <h3>3我的收藏</h3>
        <a href="javascript:void(0);" class="close" title="隐藏"></a></div>
      <div id="collectlist"></div>
    </div>
</div>
  <div class="nc-appbar-tabs" id="div">
	<ul class="tools">
		<div class="btn">
			<li ><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member&gp=home"  class="pers_centre"></a></li>
			<div class="ovrly member_home"><?php echo $lang['left_member_center'] ?></div>
		</div>  
		<div class="btn go_cart" >
			<?php if (!$output['hidden_rtoolbar_cart']) { ?>
				<li ><a href="javascript:void(0);"  class="cart rtoolbar_cart" ><i id="rtoobar_cart_count" class="new_msg" style="display:none;"></i>
				</a></li>
				<div class="ovrly rtoolbar_cart"><?php echo $lang['left_member_shopping_cart'] ?></div>
			<?php } ?>
		</div>
		<div class="btn">
			<?php if (!$output['hidden_rtoolbar_compare']) { ?>
				<li><a href="javascript:void(0);" class="compare"></a></li>
				<div class="ovrly compare"><?php echo $lang['left_member_commodity_comparison'] ?></div>
			<?php } ?>
		</div>
		<?php if($_SESSION['is_login'] == '1'){?>
			<div class="btn">
				<li ><a href="javascript:void(0);"  class="collect"></a></li>
				<div class="ovrly collect"><?php echo $lang['left_member_mycollection'] ?></div>
			</div>
		<?php }else{ ?>
			<div class="btn">
				<li ><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=login&gp=index&ref_url=<?php echo urlencode($output['ref_url']);?>"  class="collect-l"></a></li>
				<div class="ovrly"><?php echo $lang['left_member_mycollection'] ?></div>
			</div>
		<?php } ?>
		<div class="btn">
			<li ><a href="javascript:void(0)" onclick="_MEIQIA._SHOWPANEL()" class="meiqia"><i id="unreadNum" style="display:none;">0</i></a></li>
			<div class="ovrly" onclick="_MEIQIA._SHOWPANEL()"><?php echo $lang['left_member_onlineservice'] ?></div>
		</div>
		
    </ul>
  </div>
	
  <div class="nc-appbar-tabs-1">
	<ul class="tools">
		<div class="btn">
			<li ><a href="javascript:void(0);"  id="gotop" class="go_top"></a></li>
			<div class="ovrly" id="gotopD"><?php echo $lang['left_member_gototop'] ?></div>
		</div> 
		<div class="btn">
			<li ><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_mallconsult&gp=add_mallconsult" class="consult-a"></a></li>
			<div class="ovrly consult"><?php echo $lang['left_member_Consultation'] ?></div>
		</div>
	</ul>
  </div>
  
  <script>
    /**
     * [处理未读消息]
     * @param  {[string, object]} msg [string: 'hasBeenRead', object: 未读消息数据]
     */
    function getUnreadNum(msg) {
        var num = 0;
        if (msg === 'hasBeenRead') { // 消息已被阅读
            num = 0;
        } else if (typeof(msg) === 'object') {
            var unreadNum = document.getElementById('unreadNum').innerHTML,
            lastMsg = msg[msg.length - 1];
            num = isNaN(+unreadNum) ? msg.length : +unreadNum + msg.length;
            
        }
		if(num > 0){
			$('#unreadNum').show();
		}else{
			$('#unreadNum').hide();
		}
        // 未读消息数量
        document.getElementById('unreadNum').innerHTML = num; 
    }
  </script>

  <script type='text/javascript'>
    (function(m, ei, q, i, a, j, s) {
        m[a] = m[a] || function() {
            (m[a].a = m[a].a || []).push(arguments)
        };
        j = ei.createElement(q),
            s = ei.getElementsByTagName(q)[0];
        j.async = true;
        j.charset = 'UTF-8';
        j.src = i + '?v=' + new Date().getUTCDate();
        s.parentNode.insertBefore(j, s);
    })(window, document, 'script', '//eco-api.meiqia.com/dist/meiqia.js', '_MEIQIA');
    _MEIQIA('entId', 11021);
	_MEIQIA('withoutBtn', true);
	 // 获取未读消息
    _MEIQIA('getUnreadMsg', getUnreadNum);
  </script>
  
</div>
<script type="text/javascript">
//返回顶部
backTop=function (btnId){
	var btn=document.getElementById(btnId);
	var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
	window.onscroll=set;
	btn.onclick=function (){
		btn.style.opacity="1";
		window.onscroll=null;
		this.timer=setInterval(function(){
		    scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
			scrollTop-=Math.ceil(scrollTop*0.1);
			if(scrollTop==0) clearInterval(btn.timer,window.onscroll=set);
			if (document.documentElement.scrollTop > 0) document.documentElement.scrollTop=scrollTop;
			if (document.body.scrollTop > 0) document.body.scrollTop=scrollTop;
		},10);
	};
	function set(){
	    scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
	    btn.style.opacity=scrollTop?'1':"1";
	}
};
backTop('gotop');
backTop('gotopD');
</script>

<!--首页顶部广告位-->
<?php if($loadadv = loadadv(9,'html')){?>
    <div><?php echo $loadadv;?></div>
<?php }?>

<?php } ?>
<div class="public-top-layout w">
  <div class="topbar wrapper">
    <div class="user-entry" style="display:none;">
      <?php if($_SESSION['is_login'] == '1'){?>
      <?php echo $lang['nc_hello'];?> <span>
      <a href="<?php echo urlShop('member','home');?>"><?php echo $_SESSION['member_name'];?></a>
      <?php if ($output['member_info']['level_name']){ ?>
      <div class="nc-grade-mini" style="cursor:pointer;" onclick="javascript:go('<?php echo urlShop('pointgrade','index');?>');"><?php echo $output['member_info']['level_name'];?></div>
      <?php } ?>
      </span> <?php echo $lang['nc_comma'],$lang['welcome_to_site'];?> <a href="<?php echo BASE_SITE_URL;?>"  title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><span><?php echo $output['setting_config']['site_name']; ?></span></a> <span>[<a href="<?php echo urlShop('login','logout');?>"><?php echo $lang['nc_logout'];?></a>] </span>
      <?php }else{?>
      <?php echo $lang['nc_hello'].$lang['nc_comma'].$lang['welcome_to_site'];?> <a href="<?php echo BASE_SITE_URL;?>" title="<?php echo $lang['homepage'];?>" alt="<?php echo $lang['homepage'];?>"><?php echo $output['setting_config']['site_name']; ?></a> <span>[<a href="<?php echo urlShop('login');?>"><?php echo $lang['nc_login'];?></a>]</span> <span>[<a href="<?php echo urlShop('login','register');?>"><?php echo $lang['nc_register'];?></a>]</span>
      <?php }?><span style="margin-left:10px;"><a href="index.php?gct=invite" style="color:red;">邀请返利</a></span>
    </div>
    <div class="quick-menu">
    	<?php if($_SESSION['is_login'] == '1'){?>
    	<dl style="width:200px;" class='quick-dl'>
        	<dt>
             <?php echo $lang['nc_hello'];?>&nbsp; 
			 <a href="<?php echo urlShop('member','home');?>">
				<?php if($output['member_info']['member_nickname']){?>
					<?php echo $output['member_info']['member_nickname'];?>
				<?php }else{?>
					<?php echo $_SESSION['member_name'];?>
				<?php }?>
			 </a>
			  &nbsp;[&nbsp;<a href="<?php echo urlShop('login','logout');?>"><?php echo $lang['nc_logout'];?></a>&nbsp;]
            </dt>
        </dl>
        <?php }else{?>
        <dl style="width:100px;"  class='quick-dl'>
        	<dt>
              <!--
			  [&nbsp;<a href="<?php echo urlShop('login');?>"><?php echo $lang['nc_login'];?></a>&nbsp;]
         	  [&nbsp;<a href="<?php echo urlShop('login','register');?>"><?php echo $lang['nc_register'];?></a>&nbsp;]
			  -->
			  [&nbsp;<a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=login&gp=index&ref_url=<?php echo urlencode($output['ref_url']);?>"><?php echo $lang['nc_login'];?></a>&nbsp;]
			  [&nbsp;<a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=login&gp=register&ref_url=<?php echo urlencode($output['ref_url']);?>"><?php echo $lang['nc_register'];?></a>&nbsp;]
			  
            </dt>
      	</dl>
        <?php }?>
	<?php if(LANG_TYPE){ ?>
	  <dl>
		<dt><a href='javascript:;'><?php echo $lang['select_language'];?></a></dt>
		<dd>
          <ul>
			<?php foreach($output['lang'] as $key=>$value){ ?>
			<?php if($value['status']){?>
			<li><a class='language' nctype='<?php echo $key;?>' href="javascript:;"><?php echo $value['name']?></a></li>
			<?php } ?>
			<?php } ?>
		  </ul>
		</dd>
	  </dl>
	<?php }?>
      <dl>
        <dt> <a href="<?php echo urlShop('member','home');?>"><?php echo $lang['member_center']?></a><i></i></dt>
        <dd>
          <ul>
			<li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_voucher&gp=index"><?php echo $lang['member_myvoucher']?></a></li>
			<li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_snshome&gp=index"><?php echo $lang['member_mypage']?></a></li>
			<li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_points&gp=index"><?php echo $lang['member_mypoint']?></a></li>
			<li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=invite&gp=index"><?php echo $lang['member_invitefriends']?></a></li>
			<li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_snsfriend&gp=inviter"><?php echo $lang['member_ganenren']?></a></li>
			<!--li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_applymyqcode&gp=index">双创平台</a></li-->
          </ul>
        </dd>
      </dl>
	  
	   <dl>
        <dt> <a href="<?php echo urlShop('member_order','home');?>"><?php echo $lang['member_order']?></a><i></i></dt>
        <dd>
          <ul>
			<li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_order&state_type=state_new"><?php echo $lang['member_waitingpayment_order']?></a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_order&state_type=state_send"><?php echo $lang['member_waitingConfirm_order']?></a></li>
            <li><a href="<?php echo SHOP_SITE_URL;?>/index.php?gct=member_order&state_type=state_noeval"><?php echo $lang['member_waitingevaluate_order']?></a></li>
          </ul>
        </dd>
      </dl>
	  
	  <dl class="attention">
        <dt><a style="cursor:default;"><?php echo $lang['scan_code']?></a><i></i></dt>
        <img  class="attention-img" src ="<?php echo BASE_SITE_URL;?>/gcshop/templates/default/images/we-chat.jpg" alt="关注光彩全球微信公众号">
      </dl>
      <dl style="display:none;">
        <dt>客户服务<i></i></dt>
        <dd>
          <ul>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 2));?>">帮助中心</a></li>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 5));?>">售后服务</a></li>
            <li><a href="<?php echo urlShop('article', 'article', array('ac_id' => 6));?>">客服中心</a></li>
          </ul>
        </dd>
      </dl>
      <?php
      if(!empty($output['nav_list']) && is_array($output['nav_list'])){
	      foreach($output['nav_list'] as $nav){
	      if($nav['nav_location']<1){
	      	$output['nav_list_top'][] = $nav;
	      }
	      }
      }
      if(!empty($output['nav_list_top']) && is_array($output['nav_list_top'])){
      	?>
      <dl style="display:none;">
        <dt>站点导航<i></i></dt>
        <dd>
          <ul>
            <?php foreach($output['nav_list_top'] as $nav){?>
            <li><a
        <?php
        if($nav['nav_new_open']) {
            echo ' target="_blank"';
        }
        echo ' href="';
        switch($nav['nav_type']) {
        	case '0':echo $nav['nav_url'];break;
        	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
        	case '2':echo urlShop('article', 'article', array('ac_id'=>$nav['item_id']));break;
        	case '3':echo urlShop('activity', 'index', array('activity_id'=>$nav['item_id']));break;
        }
        echo '"';
        ?>><?php echo $nav['nav_title'];?></a></li>
            <?php }?>
          </ul>
        </dd>
      </dl>
      <?php }?>
	  <dl class="weixin" style="display:none;">
        <dt>关注我们<i></i></dt>
        <dd>
          <h4>扫描二维码<br/>
            关注商城微信号</h4>
          <img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$GLOBALS['setting_config']['site_logowx']; ?>" > </dd>
        </dl>
    </div>
  </div>
</div>

<script type="text/javascript">

//动画显示边条内容区域
$(function() {
$('.language').click(function(){
	var language=$(this).attr('nctype');
	$.ajax({
		url:'index.php?gct=index&gp=setLanguage',
		type:'post',
		dataType:'json',
		data:{language:language},
		success:function(re){
			if(re==1){
				
				location.reload(true);
			}
			
		}
		
	})
	
	
})
	$(".quick-app").hover(function() {
        $('.quick-img').addClass("uu");
        $('.app_ios').addClass("uu");
        $('.app_apk').addClass("uu");
      },
      function() {
        $('.quick-img').removeClass("uu");
        $('.app_ios').removeClass("uu");
        $('.app_apk').removeClass("uu");
      });
	  
	$(".attention").hover(function() {
        $('.attention-img').addClass("uu");
      },
      function() {
        $('.attention-img').removeClass("uu");
      });



	$(function() {
		$('#activator').click(function() {
			$('#content-cart').animate({'right': '-250px'});
			$('#content-compare').animate({'right': '-150px'});
			$('#ncToolbar').animate({'right': '-60px'}, 300,
			function() {
				$('#ncHideBar').animate({'right': '59px'},	300);
			});
	        $('div[nctype^="bar"]').hide();
		});
		$('#ncHideBar').click(function() {
			$('#ncHideBar').animate({
				'right': '-79px'
			},
			300,
			function() {
				$('#content-cart').animate({'right': '-250px'});
				$('#content-compare').animate({'right': '-250px'});
				$('#content-collect').animate({'right': '-250px'});
				$('#ncToolbar').animate({'right': '0'},300);
			});
		});
	});
    $(".compare").click(function(){
    	if ($("#content-compare").css('right') == '-250px') {
 		   loadCompare(false);
 		   $('#content-cart').animate({'right': '-250px'});
		   $('#content-collect').animate({'right': '-250px'});
  		   $("#content-compare").animate({right:'0px'});
  		   $(".btn").animate({right:'197px'});
    	} else {
    		$(".close").click();
    		$(".chat-list").css("display",'none');
        }
	});
    $(".rtoolbar_cart").click(function(){
        if ($("#content-cart").css('right') == '-250px') {
         	$('#content-compare').animate({'right': '-250px'});
			$('#content-collect').animate({'right': '-250px'});
    		$("#content-cart").animate({right:'0px'});
			$(".btn").animate({right:'197px'});
			
    		if (!$("#rtoolbar_cartlist").html()) {
    			$("#rtoolbar_cartlist").load('index.php?gct=cart&gp=ajax_load&type=html');
    		}
        } else {
        	$(".close").click();
        	$(".chat-list").css("display",'none');
        }
	});
	$(".collect").click(function(){
        if ($("#content-collect").css('right') == '-250px') {
			$('#content-compare').animate({'right': '-250px'});
    		$("#content-cart").animate({right:'-250px'});
         	$('#content-collect').animate({'right': '0px'});
			$(".btn").animate({right:'197px'});
			
    		if (!$("#collectlist").html()) {
    			$("#collectlist").load('index.php?gct=member_favorites&gp=fglist&type=html');
    		}
        } else {
        	$(".close").click();
        	$(".chat-list").css("display",'none');
        }
	});
	
	$(".consult").click(function(){
		location.href = SHOP_SITE_URL+'/index.php?gct=member_mallconsult&gp=add_mallconsult';
	});
	$(".member_home").click(function(){
		location.href = SHOP_SITE_URL+'/index.php?gct=member&gp=home';
	});
	
	$(".close").click(function(){
		$(".content-box").animate({right:'-250px'});
		$(".btn").animate({right:'-12px'});
      });
	 
	// var _flag = false; // 全局变量，用于记住鼠标是否在DIV上，点击空白处隐藏(购物车 对比 收藏)弹出框
	// document.getElementById('div').onmouseover = function (){
		// _flag = true;
	// };
	// document.getElementById('div').onmouseout = function (){
		// _flag = false;
	// };
	// document.body.onclick = function (){
		// if(!_flag){
			// $(".content-box").animate({right:'-250px'});
			// $(".btn").animate({right:'-12px'});
		// }
	// };

	$(".quick-menu dl").hover(function() {
		$(this).addClass("hover");
	},
	function() {
		$(this).removeClass("hover");
	});

    // 右侧bar用户信息
    $('div[nctype="a-barUserInfo"]').click(function(){
        $('div[nctype="barUserInfo"]').toggle();
    });
    // 右侧bar登录
    $('div[nctype="a-barLoginBox"]').click(function(){
        $('div[nctype="barLoginBox"]').toggle();
        document.getElementById('codeimage').src='<?php echo SHOP_SITE_URL?>/index.php?gct=seccode&gp=makecode&nchash=<?php echo getNchash('login','index');?>&t=' + Math.random();
    });
    $('a[nctype="close-barLoginBox"]').click(function(){
        $('div[nctype="barLoginBox"]').toggle();
    });
    <?php if ($output['cart_goods_num'] > 0) { ?>
    $('#rtoobar_cart_count').html(<?php echo $output['cart_goods_num'];?>).show();
    <?php } ?>
});

</script>
