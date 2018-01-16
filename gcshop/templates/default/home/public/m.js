/*
* 
* 
*/


/*homeFlash*/

function sendWapSms(phone, code) {
    var url = "http://app.zhui.cn/softstore/WapSendSMS.ashx";
    $.ajax({
        type: "post",
        url: url,
        data: { phonenum: phone, verifcode: code, jsoncallback: "?" },
        dataType: "json",
        beforeSend: function() {
        },
        success: function(json) {
            if (json[0].StatusInfo) {
                var Status = json[0].StatusInfo.Status;
                var ReturnCode = json[0].StatusInfo.ReturnCode;
                var rst = $('#wapSendRst');
                if (rst != null) {
                    rst.html(ReturnCode);
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
//                        alert(XMLHttpRequest.status);
//                        alert(XMLHttpRequest.readyState);
//                        alert(textStatus);
        }
    })
}

function topDownListCall(id, tid) {
    var url = "http://app.zhui.cn/softstore/TopDownList.ashx";
    //$("#" + id).empty();
    $.ajax({
        type: "post",
        url: url,
        data: { tid: tid, topNum: 0, jsoncallback: "?" },
        dataType: "json",
        beforeSend: function() {
        },
        success: function(json) {
            if (json[0].StatusInfo) {
                var Status = json[0].StatusInfo.Status;
                var ReturnCode = json[0].StatusInfo.ReturnCode;
                if (Status == "1") {
                    if (json[0].CItems) {
                        var num = 1;
                        $.each(json[0].CItems, function(len, item) {
                            var Id = item.SID;
                            var Name = item.Name;
                            var IconID = item.IconID;
                            var GroupID = item.GroupID;
                            var DateDiff = item.DateDiff;
                            var linkUrl = "Info_" + Id + ".aspx";
                            var imgUrl = "http://app.zhui.cn/UI/IconImg.ashx?iconid=" + IconID + "&groupid=" + GroupID + "&Title=" + encodeURIComponent(Name) + "&rnd=" + Math.random() * 1000000;
                            var li = $("#" + id + " li:nth-child(" + num + ")");
                            //var li = $("<li/>");
                            var a = $("<a></a>");
                            a.attr("href", linkUrl);
                            var img = $("<img/>");
                            img.attr("width", "32px");
                            img.attr("height", "32px");
                            img.attr("src", imgUrl);
                            var strong = $("<strong></strong>");
                            strong.html(Name);
                            var span = $("<span></span>");
                            span.html(DateDiff);
                            a.append(img);
                            a.append(strong);
                            a.append(span);
                            li.html(a);
                            num++;
                            //$("#" + id).append(li);
                        });
                    }
                    return true;
                }
                else {
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            //            alert(XMLHttpRequest.status);
            //            alert(XMLHttpRequest.readyState);
            //            alert(textStatus);
        }
    })
}

function listItemsCall(id, tid, cid, isRcmd, isNew, sort, keyword, curPage) {
    var url = "http://app.zhui.cn/softstore/SoftList.ashx"; //?&cid=" + cid + "&sortcol=" + sort;
    $("#" + id).empty();
    $("#pager").empty();
    $("#load").show();
    $.ajax({
        type: "post",
        url: url,
        data: { tid: tid, cid: cid, iRcmd: isRcmd, iNew: isNew, sortcol: sort, skeyword: keyword, topNum: 0, jsoncallback: "?" },
        dataType: "json",
        beforeSend: function() {
        },
        complete: function() { $("#load").hide(); },
        success: function(json) {
            if (json[0].StatusInfo) {
                var Status = json[0].StatusInfo.Status;
                var ReturnCode = json[0].StatusInfo.ReturnCode;
                if (json[0].ListTitle) {
                    $("#listTitle").html(unescape(json[0].ListTitle));
                }
                if (Status == "1") {
                    //alert(Status);
                    if (json[0].CItems) {
                        $.each(json[0].CItems, function(len, item) {
                            var Id = item.SID;
                            var Name = item.Name;
                            var Des = item.Des;
                            var IconID = item.IconID;
                            var GroupID = item.GroupID;
                            var UserCount = item.UserCount;
                            var shopLevel = (item.ShopLevel != null || item.ShopLevel != undefined) ? item.ShopLevel : "";
                            var linkUrl = "Info_" + Id + ".aspx";
                            var imgUrl = "http://app.zhui.cn/UI/IconImg.ashx?iconid=" + IconID + "&groupid=" + GroupID + "&Title=" + encodeURIComponent(Name) + "&rnd=" + Math.random() * 1000000;
                            var li = $("<li/>");
                            var a = $("<a></a>");
                            a.attr("href", linkUrl);
                            var img = $("<img/>");
                            img.attr("width", "50px");
                            img.attr("height", "50px");
                            img.attr("src", imgUrl);
                            var div = $("<div></div>");
                            div.addClass("des");
                            var strong = $("<strong></strong>");
                            strong.html(Name);
                            if (shopLevel != "") {
                                if (gcshop.indexOf(shopLevel) != -1) {
                                    var ii = $("<img/>");
                                    ii.attr("src", "i/taobao_shop.png");
                                    strong.html(ii);

                                } else {
                                    var i = $("<img/>");
                                    i.attr("src", shopLevel);
                                    strong.html(i);
                                }
                            }
                            var label = $("<label></label>");
                            label.html("用户数：");
                            var span = $("<span></span>");
                            span.html(UserCount);
                            label.append(span);
                            var p = $("<p></p>");
                            p.css({ 'height': '46px', "line-height": "23px", "text-overflow": "ellipsis" });
                            p.css({ 'overflow': 'hidden' });
                            p.html(Des);
                            div.append(strong);
                            div.append(label);
                            div.append(p);
                            a.append(img);
                            a.append(div);
                            li.html(a);
                            $("#" + id).append(li);
                        });

                        ShowContent(1);
                    }
                    return true;
                }
                else {
                    return false;
                }
            }
        },
        error: function(XMLHttpRequest, textStatus, errorThrown) {
            alert(XMLHttpRequest.status);
            alert(XMLHttpRequest.readyState);
            alert(textStatus);
        }
    })
}

QueryString = {
//    data: {},
//    Initial: function() {
//        var aPairs, aTmp;
//        var queryString = new String(window.location.search);
//        queryString = queryString.substr(1, queryString.length); //remove "?"   
//        aPairs = queryString.split("&");
//        for (var i = 0; i < aPairs.length; i++) {
//            aTmp = aPairs[i].split("=");
//            this.data[aTmp[0].toUpperCase()] = aTmp[1];
//        }
//    },
//    GetValue: function(key, dval) {
//        return this.data[key] != null ? this.data[key] : dval;
//    }


    //获取地址栏参数 para（key="大写参数名",参数索引，默认返回第一个）
   GetValue: function(key, dval) {
        var result = location.search.toUpperCase().match(new RegExp("[\?\&]" + key + "=([^\&]+)", "i"));
        if (result == null || result.length < 1) {
            return "";
        }
        if (!isNaN(dval)) {
            return result[1];
        } else {
            return result[dval];
        }
    }
}
