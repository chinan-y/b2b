$(function(){	
	$(".qie_one li a").each(function(a){
		$(this).click(function(){
			$(".qie_one a").removeClass("current");
			$(this).addClass("current");
			$(".qie_div").hide();
			$(".qie_div").eq(a).show();
		})
	})
	
	$(".tan_one .close").click(function(){
		$(".zhe").hide();	
		$(".tan_one").hide();
		
	})
	
})

function center(obj) {
	var screenWidth = $(window).width(), screenHeight = $(window).height(); 
	var scrolltop = $(document).scrollTop();
	var objLeft = (screenWidth - obj.width())/2;
	var objTop = (screenHeight - obj.height()) /  2 + scrolltop;
	obj.css({ left: objLeft + 'px', top: objTop + 'px' });
	$(window).resize(function () {
		screenWidth = $(window).width();
		screenHeight = $(window).height();
		scrolltop = $(document).scrollTop();

		objLeft = (screenWidth - obj.width())/2;
		objTop = (screenHeight - obj.height()) /  2 + scrolltop;

		obj.css({ left: objLeft + 'px', top: objTop + 'px' });

	});
	  $(window).scroll(function () {
		  screenWidth = $(window).width();
		  screenHeight = $(window).height();
		  scrolltop = $(document).scrollTop();

		  objLeft = (screenWidth - obj.width())/2;
		  objTop = (screenHeight - obj.height()) / 2 + scrolltop;

		  obj.css({ left: objLeft + 'px', top: objTop + 'px' });
	  });
}
function sureMessage(title,content,func){
	var left=$(window).width()/2-250+'px';
	var top=$(window).height()/2-150+'px;'
	var menuBox='<div id="sureMessage" style="position:absolute;border:1px solid #ccc;top:'+top+';left:'+left+';width:500px;background:#fff;z-index:999999">'+
					'<div class="title"><img style="float:right" src="templates/default/images/close.jpg" class="close" /></div>'+
					'<div class="content"></div>'+
					'<div class="footer">'+
						'<button id="sure" >确认</button>'+
						'<button id="cancenl">绑定新卡</button>'+
					'</div>'+
				'</div>';
	$('body').append(menuBox);
	$('#sureMessage .title').append('<span>'+title+'</span>');
	$('#sureMessage .content').append(content);
	$('#sureMessage .footer #cancenl').click(function(){
		$('li[payment_code="gzbank"]').attr('bind','false')
		$('li[payment_code="gzbank"]').removeClass('using')
		$('#sureMessage').remove();
	})
	$('#sureMessage .title .close').click(function(){
		$('#sureMessage').remove();
	})
	$('#sureMessage .footer #sure').click(func)
	
}

















