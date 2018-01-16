function iShare(type) {
    var d = document, e = encodeURIComponent, s1 = window.getSelection, s2 = d.getSelection, s3 = d.selection, s = s1 ? s1() : s2 ? s2() : s3 ? s3.createRange().text : '';
    var url = e(d.location.href);
    var title = e(d.title.substring(0, 76));
    var content = "";
    var pic = "";
    try {
        content = e(unescape(d.getElementById("shareContent").value));
    } catch (e) {
        alert(e.message); return false;
    }
    try{
        pic = e(unescape(d.getElementById("sharePic").value));
    }
    catch(e){pic="";}
    switch (type) {
        case "tsina":
            window.open("http://service.weibo.com/share/share.php?appkey=" + "1408800625" + "&title=" + content + "&pic=" + pic, "_blank", "width=615,height=505");
            break
        case "tqq":
            window.open("http://v.t.qq.com/share/share.php?appkey=" + "6c99e1f9f54f45b28a7c33c51ed7dfea" + "&title=" + content + "&pic=" + pic, "_blank", "width=615,height=505");
            break
        case "baidu":
            window.open("http://cang.baidu.com/do/add?it=" + title + "&iu=" + url + "&fr=ien#nw=1", "_blank", "width=615,height=505");
            break
        case "kaixin001":
            window.open("http://www.kaixin001.com/repaste/share.php?rtitle=" + title + "&rurl=" + url + "&rcontent=" + content, "_blank", "width=615,height=505");
            break
        case "renren":
            window.open("http://share.renren.com/share/buttonshare?title=" + title + "&link=" + url + "&image=" + pic + "&content=" + content, "_blank", "width=615,height=505");
            break
        case "douban":
            window.open("http://www.douban.com/recommend/?url=" + url + "&title=" + title + '&sel=' + e(s) + '&v=1', "_blank", "width=615,height=505");
            break
        case "taobao":
            window.open("http://share.jianghu.taobao.com/share/addShare.htm?title=" + title + "&url=" + url + "&content=" + content, "_blank", "width=615,height=505");
            break
        case "youdao":
            window.open("http://shuqian.youdao.com/manage?a=popwindow&title=" + title + "&url=" + url + "&description=" + content, "_blank", "width=615,height=505");
            break
    }
}
function iShareS(id, type) {
    var d = document, e = encodeURIComponent, s1 = window.getSelection, s2 = d.getSelection, s3 = d.selection, s = s1 ? s1() : s2 ? s2() : s3 ? s3.createRange().text : '';
    var url = e(d.location.href);
    var title = e(d.title.substring(0, 76));
    var content = "";
    try {
        content = e(unescape(d.getElementById(id).value));
    } catch (e) {
        alert(e.message); return false;
    }
    switch (type) {
        case "tsina":
            window.open("http://service.weibo.com/share/share.php?appkey=" + "1408800625" + "&title=" + content + "&pic=" + pic, "_blank", "width=615,height=505");
            break
        case "tqq":
            window.open("http://v.t.qq.com/share/share.php?appkey=" + "6c99e1f9f54f45b28a7c33c51ed7dfea" + "&title=" + content + "&pic=" + pic, "_blank", "width=615,height=505");
            break
        case "baidu":
            window.open("http://cang.baidu.com/do/add?it=" + title + "&iu=" + url + "&fr=ien#nw=1", "_blank", "width=615,height=505");
            break
        case "kaixin001":
            window.open("http://www.kaixin001.com/repaste/share.php?rtitle=" + title + "&rurl=" + url + "&rcontent=" + content, "_blank", "width=615,height=505");
            break
        case "renren":
            window.open("http://share.renren.com/share/buttonshare?title=" + title + "&link=" + url + "&image=" + pic + "&content=" + content, "_blank", "width=615,height=505");
            break
        case "douban":
            window.open("http://www.douban.com/recommend/?url=" + url + "&title=" + title + '&sel=' + e(s) + '&v=1', "_blank", "width=615,height=505");
            break
        case "taobao":
            window.open("http://share.jianghu.taobao.com/share/addShare.htm?title=" + title + "&url=" + url + "&content=" + content, "_blank", "width=615,height=505");
            break
        case "youdao":
            window.open("http://shuqian.youdao.com/manage?a=popwindow&title=" + title + "&url=" + url + "&description=" + content, "_blank", "width=615,height=505");
            break
    }
}
