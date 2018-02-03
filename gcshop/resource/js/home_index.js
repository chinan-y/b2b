(function($) {
	$.fn.fullScreen = function(settings) {//首页焦点区满屏背景广告切换
		var defaults = {
			time: 4000,
			css: 'full-screen-slides-pagination'
		};
		var settings = $.extend(defaults, settings);
		return this.each(function(){
			var $this = $(this);
		    var size = $this.find("li").size();
		    var now = 0;
		    var enter = 0;
		    var speed = settings.time;
		    $this.find("li:gt(0)").hide();
			var btn = '<ul class="' + settings.css + '">';
			for (var i = 0; i < size; i++) {
				btn += '<li>' + '<a href="javascript:void(0)">' + (i + 1) + '</a>' + '</li>';
			}
			btn += "</ul>";
			$this.after(btn);
			var $pagination = $this.next();
			$pagination.find("li").first().addClass('current');
			$pagination.find("li").click(function() {
        		var change = $(this).index();
        		$(this).addClass('current').siblings('li').removeClass('current');
        		$this.find("li").eq(change).css('z-index', '800').show();
        		$this.find("li").eq(now).css('z-index', '900').fadeOut(400,
        		function() {
        			$this.find("li").eq(change).fadeIn(500);
        		});
        		now = change;
			}).mouseenter(function() {
				clearInterval(speed);
        		enter = 1;
        	}).mouseleave(function() {
        		enter = 0;
        	});
        	function slide() {
        		var change = now + 1;
        		if (enter == 0){
        			if (change == size) {
        				change = 0;
        			}
        			$pagination.find("li").eq(change).trigger("click");
        		}
        		setTimeout(slide, speed);
        	}
        	setTimeout(slide, speed);
			//鼠标移入轮播图停止
			$('.full-screen-slides').mouseenter(function(){
				enter = 1;
			}).mouseleave(function() {
        		enter = 0;
        	});
		});
	}
	$.fn.jfocus = function(settings) {//首页焦点广告图切换
		var defaults = {
			time: 5000
		};
		var settings = $.extend(defaults, settings);
		return this.each(function(){
			var $this = $(this);
			var sWidth = $this.width();
			var len = $this.find("ul li").length;
			var index = 0;
			var picTimer;
			var btn = "<div class='pagination'>";
			for (var i = 0; i < len; i++) {
				btn += "<span></span>";
			}
			btn += "</div><div class='arrow pre'></div><div class='arrow next'></div>";
			$this.append(btn);
			$this.find(".pagination span").css("opacity", 0.4).mouseenter(function() {
				index = $this.find(".pagination span").index(this);
				showPics(index);
			}).eq(0).trigger("mouseenter");
			$this.find(".arrow").css("opacity", 0.0).hover(function() {
				$(this).stop(true, false).animate({
					"opacity": "0.5"
				},
				300);
			},
			function() {
				$(this).stop(true, false).animate({
					"opacity": "0"
				},
				300);
			});
			$this.find(".pre").click(function() {
				index -= 1;
				if (index == -1) {
					index = len - 1;
				}
				showPics(index);
			});
			$this.find(".next").click(function() {
				index += 1;
				if (index == len) {
					index = 0;
				}
				showPics(index);
			});
			$this.find("ul").css("width", sWidth * (len));
			$this.hover(function() {
				clearInterval(picTimer);
			},
			function() {
				picTimer = setInterval(function() {
					showPics(index);
					index++;
					if (index == len) {
						index = 0;
					}
				},
				settings.time);
			}).trigger("mouseleave");
			function showPics(index) {
				var nowLeft = -index * sWidth;
				$this.find("ul").stop(true, false).animate({
					"left": nowLeft
				},
				300);
				$this.find(".pagination span").stop(true, false).animate({
					"opacity": "0.4"
				},
				300).eq(index).stop(true, false).animate({
					"opacity": "1"
				},
				300);
			}
		});
	}
	$.fn.jfade = function(settings) {//首页标准模块中间多图广告鼠标触及凸显
		var defaults = {
			start_opacity: "1",
			high_opacity: "1",
			low_opacity: ".1",
			timing: "500"
		};
		var settings = $.extend(defaults, settings);
		settings.element = $(this);
		//set opacity to start
		$(settings.element).css("opacity", settings.start_opacity);
		//mouse over
		$(settings.element).hover(
		//mouse in
		function() {
			$(this).stop().animate({
				opacity: settings.high_opacity
			},
			settings.timing); //100% opacity for hovered object
			$(this).siblings().stop().animate({
				opacity: settings.low_opacity
			},
			settings.timing); //dimmed opacity for other objects
		},
		//mouse out
		function() {
			$(this).stop().animate({
				opacity: settings.start_opacity
			},
			settings.timing); //return hovered object to start opacity
			$(this).siblings().stop().animate({
				opacity: settings.start_opacity
			},
			settings.timing); // return other objects to start opacity
		});
		return this;
	}
})(jQuery);
	function takeCount() {
	    setTimeout("takeCount()", 1000);
	    $(".time-remain").each(function(){
	        var obj = $(this);
	        var tms = obj.attr("count_down");
	        if (tms>0) {
	            tms = parseInt(tms)-1;
                var days = Math.floor(tms / (1 * 60 * 60 * 24));
                var hours = Math.floor(tms / (1 * 60 * 60)) % 24;
                var minutes = Math.floor(tms / (1 * 60)) % 60;
                var seconds = Math.floor(tms / 1) % 60;

                if (days < 0) days = 0;
                if (hours < 0) hours = 0;
                if (minutes < 0) minutes = 0;
                if (seconds < 0) seconds = 0;
                obj.find("[time_id='d']").html(days);
                obj.find("[time_id='h']").html(hours);
                obj.find("[time_id='m']").html(minutes);
                obj.find("[time_id='s']").html(seconds);
                obj.attr("count_down",tms);
	        }
	    });
	}
	function update_screen_focus(){
	    var ap_ids = '';//广告位编号
	    $(".full-screen-slides li[ap_id]").each(function(){
	        var ap_id = $(this).attr("ap_id");
	        ap_ids += '&ap_ids[]='+ap_id;
	    });
	    $(".jfocus-trigeminy a[ap_id]").each(function(){
	        var ap_id = $(this).attr("ap_id");
	        ap_ids += '&ap_ids[]='+ap_id;
	    });
	    if (ap_ids != '') {
    		$.ajax({
    			type: "GET",
    			url: SHOP_SITE_URL+'/index.php?gct=adv&gp=get_adv_list'+ap_ids,
    			dataType:"jsonp",
    			async: true,
    		    success: function(adv_list){
            	    $(".full-screen-slides li[ap_id]").each(function(){
            	        var obj = $(this);
            	        var ap_id = obj.attr("ap_id");
            	        var color = obj.attr("color");
            	        if (typeof adv_list[ap_id] !== "undefined") {
            	            var adv = adv_list[ap_id];
            	            obj.css("background",color+' url('+adv['adv_img']+') no-repeat center top');
            	            obj.find("a").attr("title",adv['adv_title']);
            	            obj.find("a").attr("href",adv['adv_url']);
    					}
            	    });
            	    $(".jfocus-trigeminy a[ap_id]").each(function(){
            	        var obj = $(this);
            	        var ap_id = obj.attr("ap_id");
            	        if (typeof adv_list[ap_id] !== "undefined") {
            	            var adv = adv_list[ap_id];
            	            obj.attr("title",adv['adv_title']);
            	            obj.attr("href",adv['adv_url']);
            	            obj.find("img").attr("alt",adv['adv_title']);
            	            obj.find("img").attr("src",adv['adv_img']);
    					}
            	    });
    		    }
    		});
	    }
	}
$(function(){
	setTimeout("takeCount()", 1000);
    //首页Tab标签卡滑门切换
    $(".tabs-nav > li > h3").bind('mouseover', (function(e) {
    	if (e.target == this) {
    		var tabs = $(this).parent().parent().children("li");
    		var panels = $(this).parent().parent().parent().children(".tabs-panel");
    		var index = $.inArray(this, $(this).parent().parent().find("h3"));
    		if (panels.eq(index)[0]) {
    			tabs.removeClass("tabs-selected").eq(index).addClass("tabs-selected");
    			panels.addClass("tabs-hide").eq(index).removeClass("tabs-hide");
    		}
    	}
    }));

	/* $('.jfocus-trigeminy > ul > li > a').jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "200"
	});
	$('.fade-img > a').jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "500"
	});
	$('.middle-goods-list > ul > li').jfade({
		start_opacity: "0.9",
		high_opacity: "1",
		low_opacity: ".25",
		timing: "500"
	});
	$('.recommend-brand > ul > li').jfade({
		start_opacity: "1",
		high_opacity: "1",
		low_opacity: ".5",
		timing: "500"
	}); */

    $(".full-screen-slides").fullScreen();
    $(".jfocus-trigeminy").jfocus();
	$(".right-side-focus").jfocus();
	$(".groupbuy").jfocus({time:8000});
	$("#saleDiscount").jfocus({time:8000});
	/*监听滚动条和左侧菜单点击事件*/
            var _arr = [];
            window.onscroll = function(){
                if(800 < $(document).scrollTop()){
                    $('.nav_Sidebar').fadeIn('slow');
                }else{
                    $('.nav_Sidebar').fadeOut('slow');
                }
                $('.home-standard-layout').each(function(index){
                    var that = $(this);
                    that.index = index;
                    if($(document).scrollTop() + $(window).height()/2 > that.offset().top){
                        _arr.push(index);
                    }
                }); 
                if(_arr.length){
                    $('.nav_Sidebar a').eq(_arr[_arr.length-1]).css({'backgroundImage':'url('+SHOP_SITE_URL+'/templates/default/images/home-nav-icon-hover.png)'}).addClass('current').siblings().css({'backgroundImage':'url('+SHOP_SITE_URL+'/templates/default/images/home-nav-icon.png)'}).removeClass('current');
                    _arr = [];
                }
            }
            $('.nav_Sidebar a').each(function(index){
                $(this).click(function(){
                    $('html,body').animate({scrollTop: $('.home-standard-layout').eq(index).offset().top - 20 + 'px'}, 500);
                }).mouseover(function(){
                    if($(this).hasClass('current')){
                        return;
                    }else{
                        $(this).css({'backgroundImage':'url('+SHOP_SITE_URL+'/templates/default/images/home-nav-icon-hover2.png)'});
                    }
                }).mouseout(function(){
                    if($(this).hasClass('current')){
                        return;
                    }else{
                        $(this).css({'backgroundImage':'url('+SHOP_SITE_URL+'/templates/default/images/home-nav-icon.png)'});
                    }
                });
            });
           
//				此 window.onload 与加载页面后自动执行最小化右侧工具条的 window.onload 事件冲突	BY MING
//           window.onload = window.onresize = function(){
//               if($(window).width() < 1300 || 800 > $(document).scrollTop()){
//                   $('.nav_Sidebar').fadeOut('slow');
//               }else{
//                   $('.nav_Sidebar').fadeIn('slow');
//                }
//            }
			
			/*end*/
				//$("#js_banner_top").show();
				$("#js_banner_top").slideDown(800);
				
				
	
});

$(function() {
	//瀑布流加载首页商品
	var masonryCurrent = 1;
	var masonryUrl = 'index.php?gct=index&gp=josn_index_goods&curpage=';
	//点击载入
	function loadMeinv() {
		$.ajax({
			type : "GET",
			url: masonryUrl + masonryCurrent,
			data: {},
			success: function(data){
				if(data.length < 15){
					$("#loadMeinvMOre").hide();
				}
				for (var i = 0; i < data.length; i++) {//每次加载时模拟随机加载图片
					var html = "";				
					html='<li><div class="goods-content"><div class="goods-pic"><a target="_blank" title="'+data[i]['goods_name']+'" href="index.php?gct=goods&gp=index&goods_id='+data[i]['goods_id']+'"><img src = "'+ data[i]['goods_image'] + '" alt="'+ data[i]['goods_name'] +'" ></a></div><div class="goods-info"><div class="goods-name">'+data[i]['goods_name']+'</div><div class="goods-price"><em class="sale-price" >￥'+data[i]['goods_promotion_price']+'</em></div><div>';
					if(data[i]['num1']){
						html+='<input type="hidden" value="'+data[i]['num1']+'" class="input1"/><div class="lt-item"><span class="rule-num"><em>'+data[i]['num1']+'</em> 件起</span><span class="rule-price">￥'+data[i]['price1']+'</span></div>';
					}
					if(data[i]['num2']){
						html+='<input type="hidden" value="'+data[i]['num2']+'" class="input2"/><div class="lt-item"><span class="rule-num"><em>'+data[i]['num2']+'</em> 件起</span><span class="rule-price">￥'+data[i]['price2']+'</span></div>';
					}
					if(data[i]['num3']){
						html+='<input type="hidden" value="'+data[i]['num3']+'" class="input3"/><div class="lt-item"><span class="rule-num"><em>'+data[i]['num3']+'</em> 件起</span><span class="rule-price">￥'+data[i]['price3']+'</span></div>';
					}
					if(data[i]['num4']){
						html+='<input type="hidden" value="'+data[i]['num4']+'" class="input4"/><div class="lt-item"><span class="rule-num"><em>'+data[i]['num4']+'</em> 件起</span><span class="rule-price">￥'+data[i]['price4']+'</span></div>';
					}
					if(data[i]['num5']){
						html+='<input type="hidden" value="'+data[i]['num5']+'" class="input5"/><div class="lt-item"><span class="rule-num"><em>'+data[i]['num5']+'</em> 件起</span><span class="rule-price">￥'+data[i]['price5']+'</span></div>';
					}
					html+='</div><div class="add-cart"><a href="'+data[i]['goods_href']+'" nctype="add_cart" goods_id="'+data[i]['goods_id']+'" store_id="'+data[i]['store_id']+'" src = "'+ data[i]['goods_image'] + '"><i class="icon-shopping-cart"></i>'+data[i]['add_cart']+'</a></div></div></div></li>';
					$minUl = getMinUl();
					$minUl.append(html);
				}
			},
			async: true,
			dataType : "JSON"
		});
		masonryCurrent++;
	}
	loadMeinv();
	//无限加载
	$(window).on("scroll", function() {
		$minUl = getMinUl();
		if ($minUl.height() <= $(window).scrollTop() + $(window).height()) {
			//当最短的ul的高度比窗口滚出去的高度+浏览器高度大时加载新图片
			//loadMeinv();//加载新图片
		}
	})
	function getMinUl() {//每次获取最短的ul,将图片放到其后
		var $arrUl = $("#masonry_box .col");
		var $minUl = $arrUl.eq(0);
		$arrUl.each(function(index, elem) {
			if ($(elem).height() < $minUl.height()) {
				$minUl = $(elem);
			}
		});
		return $minUl;
	}
	//鼠标点击显示更多加载
	$("#loadMeinvMOre").click(function() {
		$minUl = getMinUl();
		loadMeinv();
	});
	
	 // 加入购物车
    /*$('a[nctype="add_cart"]').live('click' ,function() {
        var _parent = $(this).parent(), thisTop = _parent.offset().top, thisLeft = _parent.offset().left;
		var img = $(this).attr('src');
		var store_id = $(this).attr('store_id');
        animatenTop(img, thisTop, thisLeft), !1;
        eval('var goods_id = ' + $(this).attr('goods_id'));
        addcart(store_id,goods_id,1,'');
    });*/
	
	function animatenTop(img, thisTop, thisLeft) {
		var CopyDiv = '<img id="img" src="'+img+'" style="top:' + thisTop + "px;left:" + thisLeft + 'px;">', topLength = $(".go_cart").offset().top, leftLength = $(".go_cart").offset().left;
		$("body").append(CopyDiv), $("body").children("#img").animate({
			"width": "0",
			"height": "0",
			"margin-top":"0",
			"top": topLength,
			"left": leftLength-18,
			"opacity": 0.2
		}, 1000, function() {
			$(this).remove();
		});
	}
	
	var $content=$(".col li");
	$content.live("mouseover",function(){
		if($(this).find('.input5').val()){
			$(this).find(".goods-info").stop(true,false).animate({top:"-70px"},200)
		}else if($(this).find('.input4').val()){
			$(this).find(".goods-info").stop(true,false).animate({top:"-50px"},200)
		}else if($(this).find('.input3').val()){
			$(this).find(".goods-info").stop(true,false).animate({top:"-30px"},200)
		}else if($(this).find('.input2').val()){
			$(this).find(".goods-info").stop(true,false).animate({top:"-10px"},200)
		}else if($(this).find('.input1').val()){
			$(this).find(".goods-info").stop(true,false).animate({top:"10px"},200)
		}else{
			$(this).find(".goods-info").stop(true,false).animate({top:"30px"},200)
		}
	});
	$content.live("mouseout",function(){$(this).find(".goods-info").stop(true,false).animate({top:"70px"},400)});
	
	
	/* 平拍列表每隔5秒循环 */
	var index =0;
	// 3秒轮播一次
	var timer = setInterval(function(){
	index = (index == 3) ? 0 : index + 1;
	// 某个div显示，其他的隐藏
		$(".inde_ping").hide().eq(index).show();    
	}, 5000);
	
	
	/* 首页弹出广告 */
	layer.config({
				skin:'layer-ext-espresso',
				extend:'skin/espresso/style.css'
			});
	layer.open({
		type: 1,
		title: false,
		closeBtn: true,
		shade: 0.7,
		area: ['650px', '418px'],
		//skin: 'layui-layer-nobg', //没有背景色
		shadeClose: true,
		content: $('#dd'),
	});
})