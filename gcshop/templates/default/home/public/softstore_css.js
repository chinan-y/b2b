 /*
 *  Autor:Minnie
 *  Time :2012/2/23
 *  Last Edit Time:_
 *  Description : SoftStore.html的辅助JS文件
 */

$(document).ready(function() {
    $("#btnsearch").click(function() {
        var keyword = $(".t").val();
        if (keyword == "" || keyword.length == 0) {
            return false;
        }
    })
    $(".top_sort_ul li:nth-child(odd)").addClass("odd");
    $(".top_sort_ul li:nth-child(even)").addClass("even");
    $(".fine_soft_tops li:nth-child(odd)").css("background-color", "#ffffff");
    $(".fine_soft_tops li:nth-child(even)").css("background-color", "#f7f5f5");
    $(".top10_ul li:nth-child(odd)").css("background", "#ffffff");
    $(".top10_ul li:nth-child(even)").css("background", "#f7f5f5")

    $(".top10_ul li").bind("mouseover", function(e) {
        $(this).addClass("cur").siblings().removeClass("cur");
    });
    $(".top_sort_ul li").bind("mouseover", function(e) {
        $(this).addClass("curr");
    }).bind("mouseout", function(e) {
        $(this).removeClass("curr");
    })

    $("#btnwapsend").bind("click", function(e) {
        sendWapSms($("#phonenum").val(), $("#verifcode").val());
    })

    $("#wap_link a").toggle(function() {
        $(this).parent().addClass("link_on");
        $(this).show();
        $("#wap_link_down ").show();
        return false;
    }, function() {
        $(this).parent().removeClass("link_on");
        $(this).show();
        $("#wap_link_down ").hide();
        return false;
    })
    if ($("#fine_soft_arry").size() != "") {
        var lis = document.getElementById("fine_soft_arry").getElementsByTagName("li");
        var j = 0;
        for (var i = 0; i < lis.length; i++) {
            if (j < 3) {
                lis[i].style.background = "#f7f5f5";
            } else {
                lis[i].style.background = "#fff";
            }
            j++;
            if (j == 6) { j = 0; }
        }
    }
    if ($("#fine_soft_arry2").size() != "") {
        var lis = document.getElementById("fine_soft_arry2").getElementsByTagName("li");
        var j = 0;
        for (var i = 0; i < lis.length; i++) {
            if (j < 3) {
                lis[i].style.background = "#f7f5f5";
            } else {
                lis[i].style.background = "#fff";
            }
            j++;
            if (j == 6) { j = 0; }
        }
    }

    $("#media_soft li").bind("mouseover", function() {
        $(this).addClass("currs").siblings().removeClass("currs"); ;
        $(this).find('.media_des').css("display", "block");
        $(this).siblings().find('.media_des').css("display", "none");
    });
    $(".mobile_actives li").hover(function() { $(this).addClass("cur"); $(this).siblings().removeClass("cur") })
    $(".mobile_actives li").find(".active_product").hover(function() {
        $(this).parent().find(".active_product").css("display", "none").parent().siblings().find(".active_product").css("display", "block");
        $(this).parent().find(".active_des_item").removeClass("dn").css("display", "block").parent().siblings().find(".active_des_item").addClass("dn").css("display", "none");
    });
    var lenw = "748";
    var minwid = "2200";
    $("#pre").click(function() {
        if (Math.abs($("#mobile_actives").position().left) <= Math.abs(434)) {
            $(".nxt").css("display", "inline-block");
            $(".pre").css("display", "none");
            $("#mobile_actives").animate({
                "left": "0px"
            }, 500);
        } else {
            $("#mobile_actives").animate({
                "left": "+=" + lenw + "px"
            }, 500);
            $(".pre").css("display", "inline-block");
        }
    });
    $("#nxt").click(function() {
        if (Math.abs($("#mobile_actives").position().left) >= Math.abs(minwid)) {
            $("#mobile_actives").animate({
                "left": "0px"
            }, 500);
            $(".nxt").css("display", "inline-block");
            $(".pre").css("display", "none");
        } else {
            $(".pre").css("display", "inline-block");
            $(".nxt").css("display", "inline-block");
            $("#mobile_actives").animate({
                "left": "-=" + lenw + "px"
            }, 500);
        }
    });


    $(".previewlink_help").colorbox({ width: "960",
        height: "550",
        iframe: true,
        opacity: 0.3
    });

    $(".colbox_zhuiwap").colorbox({ width: "540",
        height: "440",
        iframe: true,
        opacity: 0.4
    });


    $("#PreviewDemo").colorbox({ width: "355px;", height: "550px;", iframe: true,scrolling:false });
    $(".to_mobile").colorbox({ width: "580px;", height: "480px;", iframe: true });
    $(".to_mob").colorbox({ width: "580px;", height: "480px;", iframe: true });
    $(".to_weixin").colorbox({ width: "580px;", height: "480px;", iframe: true });
    $(".capture").colorbox({ width: "600px;", height: "500px;", iframe: true });
    $(".iShareMail").colorbox({ width: "470px;", height: "200px;", iframe: true });
    $(".soft_shares a:gt(3)").hide();
    $(".mdd").click(function() { $(this).hide(); $(".soft_shares a:gt(3)").show(); return false; })
    $(".cdd").click(function() { $(this).show(); $(".soft_shares a:gt(3)").hide(); $(".mdd").show(); return false; })
    $(".btn_help").css("top", "170px");
    //$(".tooltiplink").tooltip();
    $(".main_col_home").css("height", $(".my_orders").height() + 180);
    $(".soft_aiw li").bind("mouseover", function() {
        $(this).find(".version_down").removeClass("dn").show();
        $(this).siblings().find(".version_down").hide();
        $(this).siblings().find(".GotoStore_a").hide();
        $(this).siblings().find(".a_div").show();
        $(this).find(".versions").hide();
        $(this).find(".a_div").hide();
        $(this).siblings().find(".versions").removeClass("dn").show();
        $(this).find(".GotoStore_a").show(); 
    });

    //提交搜索
    $("form").submit(function() {
        if ($("#keyword").val() == "") {
            return false;
        }
    });

    //初始化搜索框关键字
    var result = location.search.match(new RegExp("[\?\&]keyword=([^\&]+)", "i"));
    if (result == null || result.length < 1) {
        return;
    }


});

        function showContent(id, flag) {
            if (flag) {
                $(id).css({ 'display': 'block' });
            }else {
                $(id).css({ 'display': 'none' });
            }
        }
        
        function setCurr(id, ido1, ido2) {
            $(ido1).removeClass("curr");
            $(ido2).removeClass("curr");
            $(id).addClass("curr");
        }
        
         function showInfo(id, flag) {
            if (flag) {
                $(id).show();
            }else {
                $(id).hide();
            }
        }
        $.ellipsis = function() {
            $(this).css({ 'overflow': 'hidden' });
            var docS = document.documentElement.style;
            if ('textOverflow' in docS || 'OTextOverflow' in docS) {
                $(this).css({'text-overflow': 'ellipsis','-o-text-overflow': 'ellipsis'});
            } else {
                $(this).each(function() {
                    var $el = $(this);
                    if (!$el.data('text')) $el.data('text', $el.text());
                    var text = $el.attr('text') || $el.text(),
                w = $el.width(),
                a = 0,
                b = text.length,
                c = b,
                $t = $el.clone().css({
                    'position': 'absolute',
                    'width': 'auto',
                    'visibility': 'hidden',
                    'overflow': 'hidden'
                }).insertAfter($el);
                    $el.text(text);
                    $t.text(text);
                    if ($t.width() > w) {
                        while ((c = Math.floor((b + a) / 2)) > a) {
                            $t.text(text.substr(0, c) + '…');
                            if ($t.width() > w) b = c;
                            else a = c;
                        }
                        $el.text(text.substr(0, c) + '…');
                    }
                    $t.remove();
                });
            }
            return this;
        };