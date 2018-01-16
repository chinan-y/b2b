<?php defined('GcWebShop') or exit('Access Invalid!');?>
<?php require('groupbuy_head.php');?>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/jquery.min.js" charset="utf-8"></script>
<script type="text/javascript" src="<?php echo SHOP_RESOURCE_SITE_URL;?>/js/panic.js" charset="utf-8"></script>
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/home_group.css" rel="stylesheet" type="text/css">
<link href="<?php echo SHOP_TEMPLATES_URL;?>/css/index.css" rel="stylesheet" type="text/css">

<div class="nch-breadcrumb-layout" style="display: none;">
  <div class="nch-breadcrumb wrapper"> <i class="icon-home"></i> <span> <a href="<?php echo urlShop(); ?>">首页</a> </span> <span class="arrow">></span> <span>天天特价</span></div>
</div>

<div class="ncg-container index-width">
	<?php if (!empty($output['all_groupbuy'])) { ?>
	<div class="everyday">
		<img src="<?php echo SHOP_TEMPLATES_URL;?>/images/tttejia.png" >
		<ul class="qie_one">
		  <?php foreach ($output['all_groupbuy'] as $val) { ?>
			<li <?php if ($output['class_name'] ==$val[0]['time_num']){echo ' class="sel"';}else{echo ' class="two"';} ?>>
				<a href="<?php echo urlShop('show_groupbuy', 'index',array('time_num'=>$val[0]['time_num']));?>">
					<dt><?php echo $val[0]['button_text'];?></dt>
					<dd><?php echo $val[0]['time_s'];?></dd>
				</a>
			</li>
		  <?php } ?>
		</ul>
	</div>
	<?php } ?>
	<div class="home-sale-layout index-width tttj_index mt30">
	  <?php if (!empty($output['groupbuy'])) { ?>
	  <ul>
		<?php foreach ($output['groupbuy'] as $groupbuy) { ?>
		  <li class="<?php echo $output['current'];?>">
			
			<div class="tttj_left">
				<img class='img' src="<?php if(C(OSS_IS_STORAGE) == 0){echo gthumb($groupbuy['groupbuy_image'],'mid');} if(C(OSS_IS_STORAGE) == 1){echo gthumb($groupbuy['groupbuy_image'],'530');}?>" alt="<?php echo $groupbuy['goods_name'];?>">
			</div>
			<div class="tttj_right">
			  <a title="<?php echo $groupbuy['goods_name'];?>" href="<?php echo $groupbuy['goods_url'];?>" target="_blank">
				<div class="groupbuy-title"><span class="title"><?php echo $groupbuy['groupbuy_name'];?></span></div>
				<div class="country">
					<img src="<?php echo UPLOAD_SITE_URL?>/country/<?php echo $groupbuy['country_code'];?>.png"/>
					<span class="title1"><?php echo $groupbuy['goods_name'];?></span> 
				</div>
				
				<div class="country-jingle">
					<span class="jingle"><?php echo $groupbuy['goods_jingle'];?></span> 
				</div>
				
				<?php list($integer_part, $decimal_part) = explode('.', $groupbuy['groupbuy_price']);?>
				<?php $start = time(); ?>
				
				<?php if($groupbuy['start_time'] > $start){?>
					<?php $time = $groupbuy['start_time']; $but = 1 ;?>
				<?php }else if($groupbuy['end_time'] < $start){ ?>
					<?php $but = 2 ;?>
				<?php }else {?>
					<?php $time = $groupbuy['end_time']; $but = 3 ;?>
				<?php } ?>
				<?php if ($groupbuy['goods_storage']==0) { ?>
					<div class="hotdate" >
						<i class="icon-time"></i>
						<span class='sheng'><?php echo '抢购结束';?></span>
					</div>
				<?php }else{ ?>
				<?php $u +=1;?>
					<div class="hotdate">
					<i class="icon-time"></i>
					<span class='sheng'><?php echo $groupbuy['count_down_text'];?>：</span>
					<span class= "tian" id="t_d<?php echo $u+1;?>"></span>
					<span class= "tian" id="t_h<?php echo $u+1;?>">00</span><strong><?php echo $lang['text_hour'];?></strong>
					<span class= "tian" id="t_m<?php echo $u+1;?>">00</span><strong><?php echo $lang['text_minute'];?></strong>
					<span class= "tian" id="t_s<?php echo $u+1;?>">00</span><strong><?php echo $lang['text_second'];?></strong>
					<script type="text/javascript">  
						function getRTime(){
							var uu = <?php echo $time ;?> *1000;//截止时间 / 开始时间
							var NowTime = new Date().getTime();
							var t =uu - NowTime;
							t = t>=0 ? t : 0;
							var d =Math.floor(t/1000/60/60/24);
							var h =Math.floor(t/1000/60/60%24);
							var m =Math.floor(t/1000/60%60);
							var s =Math.floor(t/1000%60);
							if(d < 1){
								document.getElementById("t_d<?php echo $u+1;?>").innerHTML = '' ;
							}else{
								document.getElementById("t_d<?php echo $u+1;?>").innerHTML = d+'<em style="font-size:14px;color:#666;font-weight:600">天</em>' ;
							}
							document.getElementById("t_h<?php echo $u+1;?>").innerHTML = h ;
							document.getElementById("t_m<?php echo $u+1;?>").innerHTML = m ;
							document.getElementById("t_s<?php echo $u+1;?>").innerHTML = s ;
						}
						setInterval(getRTime,1000);
					</script> 
				</div>
				<?php } ?>
				<div class="item-prices">
					<span class="price"><i><?php echo $lang['currency'];?></i><?php echo $integer_part;?><em>.<?php echo $decimal_part;?></em></span>
					<div class="dock"><del class="orig-price"><?php echo $lang['currency'].$groupbuy['goods_marketprice'];?></del> </div>
					<div class="dock">
					<span class="limit-num"><?php echo number_format($groupbuy['groupbuy_rebate'],1);?>&nbsp;<?php echo $lang['text_zhe'];?></span>
					</div>
					<?php if ($groupbuy['goods_storage']==0) { ?>
						<a href="javascript: ;" class="buy-button used-up">已抢光</a>
					<?php } else {?>
						<?php if($but == 3){?>
							<a href="<?php echo $groupbuy['goods_url'];?>" target="_blank" class="buy-button"><?php echo '立即抢';?></a>
						<?php }else if($but == 2){?> 
							<a href="javascript:;" class="buy-button used-up"><?php echo $groupbuy['button_text'];?></a>
						<?php }else if($but == 1){ ?>
							<a href="javascript:;" class="buy-button not-started"><?php echo $groupbuy['button_text'];?></a>
						<?php }?>
					<?php }?>
				</div>	
			  </a>
			</div>
		  </li>
		<?php } ?>
		</ul>
	  <?php } else { ?>
		<div class="no-content" >暂无抢购商品</div>
	  <?php } ?>
	</div>
</div>
