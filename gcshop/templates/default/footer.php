<?php defined('GcWebShop') or exit('Access Invalid!');?>
<?php //echo getChat($layout);?>
<div id="faq">
  <div class="faq-wrapper">
    <?php if(is_array($output['article_list']) && !empty($output['article_list'])){ ?><ul>
    <?php foreach ($output['article_list'] as $k=> $article_class){ ?>
    <?php if(!empty($article_class['list'])){ ?>
   <li> <dl class="s<?php echo ''.$k+1;?>">
      <dt>
        <?php if(is_array($article_class['class'])) echo $article_class['class']['ac_name'];?>
      </dt>
      <?php if(is_array($article_class['list']) && !empty($article_class['list'])){ ?>
	  
	  <?php if($article_class['ac_id'] == 2) { ?>
			
		<?php foreach ($output['sub_class_list'] as $k=>$v){?>
		<dd><i></i><a href="<?php echo urlShop('article', 'article', array('ac_id'=>$v['ac_id']));?>"><?php echo $v['ac_name']?></a></dd>
		<?php }?>
		
	  <?php }else{?>
	  
		<?php foreach ($article_class['list'] as $article){ ?>
		<dd><i></i><a href="<?php if($article['article_url'] != '')echo $article['article_url'];else echo urlShop('article', 'show',array('article_id'=> $article['article_id']));?>" title="<?php echo $article['article_title']; ?>"> <?php echo $article['article_title'];?> </a></dd>
		<?php }?>
		  
      <?php }?>
      <?php }?>
    </dl></li>
    <?php }?>
    <?php }?>	    	
	</ul>	
<div class="help">
		<div class="w1190 clearfix">
    		<div class="contact f-l">
    			<div class="contact-border clearfix">
        			<span class="ic tel t20"><?php echo $GLOBALS['setting_config']['site_tel400']; ?></span>
        			<span class="ic mail"><?php echo $GLOBALS['setting_config']['site_email']; ?></span>
        			<div class="attention cleafix">
					<div class="weibo">
        					<div class="ic sina" style="padding-left: 0px; float:left;"><?php echo rec(8);?></div>
        					<div class="ic qq" style="padding-left: 0px; float:left;"><?php echo rec(7);?></div>
        			</div>
					<!--
        				<div class="weixin">						
						 <img src="<?php //echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$GLOBALS['setting_config']['site_logowx']; ?>" class="f-l jImg img-error" >
						 <img src="<?php //echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS.$GLOBALS['setting_config']['site_logowx']; ?>" class="f-l jImg img-error" >
							<span></span>
        				</div>
					-->
					</div>
    			</div>
    		</div>
		</div>
	</div>			
    <?php }?>
  </div>
</div>
<div id="footer" class="wrapper">
	<!--a href="http://www.customs.gov.cn" target ="_balnk"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS."chinacustoms_logo.jpg"; ?>" width="150px"></a>
	<a href="http://www.aqsiq.gov.cn" target ="_balnk"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS."chinaiq_logo.jpg"; ?>" width="150px"></a>
	<a href="http://www.cqkjs.com" target ="_balnk"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS."cqkj_logo.png"; ?>" width="150px"></a>
	<a href="http://www.alipay.com" target ="_balnk"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS."alipay_logo.jpg"; ?>" width="150px"></a>
	<a href="http://www.chinapay.com" target ="_balnk"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS."union_logo.jpg"; ?>" width="150px"></a>
	<a href="https://pay.weixin.qq.com" target ="_balnk"><img src="<?php echo UPLOAD_SITE_URL.DS.ATTACH_COMMON.DS."weixin_logo.jpg"; ?>" width="150px"></a>
  
  <p>
  <a href="<?php echo SHOP_SITE_URL;?>"><?php echo $lang['nc_index'];?></a>
    <?php if(!empty($output['nav_list']) && is_array($output['nav_list'])){?>
    <?php foreach($output['nav_list'] as $nav){?>
    <?php if($nav['nav_location'] == '2'){?>
    | <a  <?php if($nav['nav_new_open']){?>target="_blank" <?php }?>href="<?php switch($nav['nav_type']){
    	case '0':echo $nav['nav_url'];break;
    	case '1':echo urlShop('search', 'index', array('cate_id'=>$nav['item_id']));break;
    	case '2':echo urlShop('article', 'article',array('ac_id'=>$nav['item_id']));break;
    	case '3':echo urlShop('activity', 'index',array('activity_id'=>$nav['item_id']));break;
    }?>"><?php echo $nav['nav_title'];?></a>
    <?php }?>
    <?php }?>
    <?php }?>
  </p-->
  <div class="shop_tlink">
		<?php $model_link = Model('link');
		      $link_list = $model_link->getIndexLinkList(1000);
			  if(is_array($link_list)) {
				  
				foreach($link_list as $val) {
					if($val['link_pic'] == ''){
			  ?>
		<em>友情链接：</em><span><i></i><a href="<?php echo $val['link_url']; ?>" target="_blank" title="<?php echo $val['link_title']; ?>"><?php echo str_cut($val['link_title'],16);?></a></span>
		<?php
					}
				}
			 }
			 ?>
		<div class="clear"></div>
  </div>
  <?php echo $output['setting_config']['gcweb_copyright'];?> <?php echo $output['setting_config']['icp_number']; ?><br />
  <?php echo html_entity_decode($output['setting_config']['statistics_code'],ENT_QUOTES); ?>
  <?php echo html_entity_decode($output['setting_config']['statistics_code_baidu'],ENT_QUOTES); ?>

  </div>
<?php if (C('debug') == 1){?>
<div id="think_page_trace" class="trace">
  <fieldset id="querybox">
    <legend><?php echo $lang['nc_debug_trace_title'];?></legend>
    <div> <?php print_r(Tpl::showTrace());?> </div>
  </fieldset>
</div>
<?php }?>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/jquery.cookie.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/perfect-scrollbar.min.js"></script>
<script type="text/javascript" src="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.js"></script>
<link href="<?php echo RESOURCE_SITE_URL;?>/js/qtip/jquery.qtip.min.css" rel="stylesheet" type="text/css">
<!-- 对比 -->
<script src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/compare.js"></script>
<script type="text/javascript">
$(function(){
	// Membership card
	$('[nctype="mcard"]').membershipCard({type:'gcshop'});
});

var _mvq = window._mvq || []; 
window._mvq = _mvq;
_mvq.push(['$setAccount', 'm-281651-0']);
_mvq.push(['$setGeneral', '', '', /*用户名*/ '', /*用户id*/ '']);//如果不传用户名、用户id，此句可以删掉
_mvq.push(['$logConversion']);
(function() {
var mvl = document.createElement('script');
mvl.type = 'text/javascript'; mvl.async = true;
mvl.src = ('https:' == document.location.protocol ? 'https://static-ssl.mediav.com/mvl.js' : 'http://static.mediav.com/mvl.js');
var s = document.getElementsByTagName('script')[0];
s.parentNode.insertBefore(mvl, s); 
})();
</script>
<!-- JiaThis Button BEGIN -->
<!-- script type="text/javascript" src="http://v3.jiathis.com/code/jiathis_r.js?uid=1406000856672566&type=left&amp;move=0&amp;btn=l1.gif" charset="utf-8"></script -->
<!-- JiaThis Button END -->