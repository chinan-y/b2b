(function($) {
    $.fn.qwhs = function(q) {
        var timer = undefined;
        q = $.extend({
            srcs: "lazy",
            auto: 1,
            showd:1
        },
        q || {});
        return this.each(function() {
            var boxs = $(this);
            var len = boxs.find("li").length;
            var index = 0;
            var picTimer;
			var h_pre=boxs.find(".h_pre");
			var h_nxt=boxs.find(".h_nxt");
			
            var btn = "<div class='btn'>";
            for (var i = 0; i < len; i++) {
                var k = i + 1;
				if(q.showd==0){
				k="";
				}
                btn += "<span>" + k + "</span>"
				
            }
            btn += "</div>";
            boxs.append(btn);
            boxs.find(".btn span").mouseenter(function() {
                index = boxs.find(".btn span").index(this);
                showPics(index)
            }).eq(0).trigger("mouseenter");
            boxs.hover(function() {
                clearInterval(picTimer);
				h_pre.fadeIn(200);
				h_nxt.fadeIn(200);
            },
            function(){
			h_pre.hide();
			h_nxt.hide();
				
				if(q.auto==1){
                picTimer = setInterval(function() {
                    showPics(index);
                    index++;
                    if (index == len) {
                        index = 0
                    }
                },
                4000);
			}
            }).trigger("mouseleave");
			
			h_pre.click(function(){
                    index--;
                    if (index == 0) {
                        index = len;
                    }
					showPics(index);
			});
			h_nxt.click(function(){
				  index++;
                    if (index == len) {
                        index = 0;
                    }
					showPics(index);
			});
			
            function showPics(index) {
                var k = boxs.find("li").eq(index);
				if(q.srcs=="lazy"){
                var m = k.attr("lazy");
                if(m){
                    k.css("background-image", "url(" + m + ")").removeAttr("lazy");

                }
				}else{
				var m1 = k.find("img");
				var m2=m1.attr("src3");
	
                if(m2){
                    m1.attr("src",m2).removeAttr("src3");
                }
				}
				k.addClass("on").siblings("li").removeClass("on");
                k.stop(true, true).fadeIn(300).siblings("li").fadeOut(300);
				
                boxs.find(".btn span").stop(true, true).removeClass("on").eq(index).addClass("on")
            }
        })
    }
})(jQuery);


(function($) {
    $.fn.qwls = function(q) {
        var timer = undefined;
        q = $.extend({xlen:188},q || {});
        return this.each(function() {
            var boxs = $(this);
			var ul=boxs.find("ul");
            var len = boxs.find("li").length;
            var ix = 0;
			ul.css("width",q.xlen*len);
			var pre=boxs.find(".b_pre");
			var nxt=boxs.find(".b_nxt");
pre.click(function(){
ix -= 1;
if (ix == -1) {
  ix = len - 1;
}
showPics(ix);
});
			
nxt.click(function(){
ix += 1;
if (ix == len) {
ix = 0;
}
showPics(ix);
});

function showPics(index) {
    var m = boxs.find("ul li").eq(index).find("img[src3]");
    if(m){
		m.each(function(){
		$(this).attr("src",$(this).attr("src3")).removeAttr("src3")	;
			});
    }
    var nLeft = -index * q.xlen;
    ul.stop(true, false).animate({"left":nLeft},300);
}

});	
    }
})(jQuery);



var hbox=$("#hbox").children();
$("#htab").delegate("li","mouseenter",function(){
var i=$(this).index();
$(this).addClass("on").siblings().removeClass("on");
hbox.eq(i).show().siblings().hide();
});
function ajx(){
	document.getElementById("a_gx").style.display="none";
}

$("#hr_a em").hover(function(){
	$(this).addClass("hron");
	},function(){
	$(this).removeClass("hron");
		});
/*var $ht_r=$(".ht_r li");
$ht_r.mouseenter(function(){
var bx=$(this).parent().attr("data-id");
var pr=$("#"+bx).find(".hbxs");
var i=$(this).index();
var m = pr.eq(i)
var s=m.find("img[src3]");
if(s){
s.each(function(){
$(this).attr("src",$(this).attr("src3")).removeAttr("src3");	
});	
}
$(this).addClass("on").siblings().removeClass("on");	
m.show().siblings().hide();
});*/
		
var $h_bl=$(".h_bl");
var $sfous=$(".sfous");
var h_week=$("#h_week");
var $tml=$("#hloot").find(".timel");

$h_bl.qwls();
$sfous.qwhs({srcs:"lazy",auto:0,showd:0});
$("#focus").qwhs();

h_week.delegate("li","mouseenter",function(){
var b=$(this);
var i=b.hasClass("on")
if(!i){
var a=h_week.find("li.on");

a.stop().animate({"width":"200px"},200,function(){

});
b.stop().animate({"width":"590px"},200).addClass("on").siblings().removeClass("on");
}
});

function updateEndTime(){
    var date=new Date();
    var time=date.getTime()/1000;
    $tml.each(function(i){
        var endTime=this.getAttribute("data-id");
        var sTime=this.getAttribute("data-stime"); 
            sTime=sTime*1+28800;      
        if(sTime>time){
             var ctime=sTime-time;
             var second=Math.floor(ctime%60);
             var minite=Math.floor((ctime/60)%60);
             var hour=Math.floor((ctime/3600)%24);
             var day=Math.floor((ctime/3600)/24);
             $(this).html(""+day+"天"+hour+"时"+minite+"分"+second+"秒 即将开始！");       
        
        }else{
            var lag=(endTime-time);
            if(lag>0){
                 var second=Math.floor(lag%60);
                var minite=Math.floor((lag/60)%60);
                var hour=Math.floor((lag/3600)%24);
                var day=Math.floor((lag/3600)/24);
                $(this).html("仅剩："+day+"天"+hour+"时"+minite+"分"+second+"秒");
        
            }else{
                $(this).html("抢购已经结束！");
            }
        }
        
      });
    setTimeout("updateEndTime()",1000);
}
updateEndTime();