/**
 * @author
 */

var STO;
document.write('<style>.tb_tbox {position:absolute; display:none; z-index:90000}.tb_tinner {overflow:hidden;padding:0px; -moz-border-radius:5px; border-radius:5px; background:#fff url(http://www.tongbu.com/api/image/preload.gif) no-repeat 50% 50%; border-right:1px solid #333; border-bottom:1px solid #333}.tb_tmask {position:absolute; display:none; top:0px; left:0px; height:100%; width:100%; background:#000; z-index:80000}.tb_tclose {position:absolute; top:0px; right:0px; width:30px; height:30px; cursor:pointer; background:url(http://www.tongbu.com/api/image/close.png) no-repeat}.tb_tclose:hover {background-position:0 -30px}#frameless {padding:0}#frameless .tb_tclose {right:1px;top:1px}#bluemask {background:#333}</style>');
TINY={};TINY.box=function(){var j,m,b,g,v,p=0;return{show:function(o){v={opacity:70,close:1,animate:1,fixed:1,mask:1,maskid:'',boxid:'',topsplit:2,url:0,post:0,height:0,width:0,html:0,iframe:0};for(s in o){v[s]=o[s]}if(!p){j=document.createElement('div');j.className='tb_tbox';p=document.createElement('div');p.className='tb_tinner';b=document.createElement('div');b.className='tcontent';m=document.createElement('div');m.className='tb_tmask';g=document.createElement('div');g.className='tb_tclose';g.v=0;document.body.appendChild(m);document.body.appendChild(j);j.appendChild(p);p.appendChild(b);m.onclick=g.onclick=TINY.box.hide;window.onresize=TINY.box.resize}else{j.style.display='none';clearTimeout(p.ah);if(g.v){p.removeChild(g);g.v=0}}p.id=v.boxid;m.id=v.maskid;j.style.position=v.fixed?'fixed':'absolute';if(v.html&&!v.animate){p.style.backgroundImage='none';b.innerHTML=v.html;b.style.display='';p.style.width=v.width?v.width+'px':'auto';p.style.height=v.height?v.height+'px':'auto'}else{b.style.display='none';if(!v.animate&&v.width&&v.height){p.style.width=v.width+'px';p.style.height=v.height+'px'}else{p.style.width=p.style.height='100px'}}if(v.mask){this.mask();this.alpha(m,1,v.opacity)}else{this.alpha(j,1,100)}if(v.autohide){p.ah=setTimeout(TINY.box.hide,1000*v.autohide)}else{document.onkeyup=TINY.box.esc}},fill:function(c,u,k,a,w,h){if(u){if(v.image){var i=new Image();i.onload=function(){w=w||i.width;h=h||i.height;TINY.box.psh(i,a,w,h)};i.src=v.image}else if(v.iframe){this.psh('<iframe src="'+v.iframe+'" width="'+v.width+'" frameborder="0" height="'+v.height+'"></iframe>',a,w,h)}else{var x=window.XMLHttpRequest?new XMLHttpRequest():new ActiveXObject('Microsoft.XMLHTTP');x.onreadystatechange=function(){if(x.readyState==4&&x.status==200){p.style.backgroundImage='';TINY.box.psh(x.responseText,a,w,h)}};if(k){x.open('POST',c,true);x.setRequestHeader('Content-type','application/x-www-form-urlencoded');x.send(k)}else{x.open('GET',c,true);x.send(null)}}}else{this.psh(c,a,w,h)}},psh:function(c,a,w,h){if(typeof c=='object'){b.appendChild(c)}else{b.innerHTML=c}var x=p.style.width,y=p.style.height;if(!w||!h){p.style.width=w?w+'px':'';p.style.height=h?h+'px':'';b.style.display='';if(!h){h=parseInt(b.offsetHeight)}if(!w){w=parseInt(b.offsetWidth)}b.style.display='none'}p.style.width=x;p.style.height=y;this.size(w,h,a)},esc:function(e){e=e||window.event;if(e.keyCode==27){TINY.box.hide()}},hide:function(){clearTimeout(STO);TINY.box.alpha(j,-1,0,3);document.onkeypress=null;if(v.closejs){v.closejs()}},resize:function(){TINY.box.pos();TINY.box.mask()},mask:function(){m.style.height=this.total(1)+'px';m.style.width=this.total(0)+'px'},pos:function(){var t;if(typeof v.top!='undefined'){t=v.top}else{t=(this.height()/v.topsplit)-(j.offsetHeight/2);t=t<20?20:t}if(!v.fixed&&!v.top){t+=this.top()}j.style.top=t+'px';j.style.left=typeof v.left!='undefined'?v.left+'px':(this.width()/2)-(j.offsetWidth/2)+'px'},alpha:function(e,d,a){clearInterval(e.ai);if(d){e.style.opacity=0;e.style.filter='alpha(opacity=0)';e.style.display='block';TINY.box.pos()}e.ai=setInterval(function(){TINY.box.ta(e,a,d)},20)},ta:function(e,a,d){var o=Math.round(e.style.opacity*100);if(o==a){clearInterval(e.ai);if(d==-1){e.style.display='none';e==j?TINY.box.alpha(m,-1,0,2):b.innerHTML=p.style.backgroundImage=''}else{if(e==m){this.alpha(j,1,100)}else{j.style.filter='';TINY.box.fill(v.html||v.url,v.url||v.iframe||v.image,v.post,v.animate,v.width,v.height)}}}else{var n=a-Math.floor(Math.abs(a-o)*.5)*d;e.style.opacity=n/100;e.style.filter='alpha(opacity='+n+')'}},size:function(w,h,a){if(a){clearInterval(p.si);var wd=parseInt(p.style.width)>w?-1:1,hd=parseInt(p.style.height)>h?-1:1;p.si=setInterval(function(){TINY.box.ts(w,wd,h,hd)},20)}else{p.style.backgroundImage='none';if(v.close){p.appendChild(g);g.v=1}p.style.width=w+'px';p.style.height=h+'px';b.style.display='';this.pos();if(v.openjs){v.openjs()}}},ts:function(w,wd,h,hd){var cw=parseInt(p.style.width),ch=parseInt(p.style.height);if(cw==w&&ch==h){clearInterval(p.si);p.style.backgroundImage='none';b.style.display='block';if(v.close){p.appendChild(g);g.v=1}if(v.openjs){v.openjs()}}else{if(cw!=w){p.style.width=(w-Math.floor(Math.abs(w-cw)*.6)*wd)+'px'}if(ch!=h){p.style.height=(h-Math.floor(Math.abs(h-ch)*.6)*hd)+'px'}this.pos()}},top:function(){return document.documentElement.scrollTop||document.body.scrollTop},width:function(){return self.innerWidth||document.documentElement.clientWidth||document.body.clientWidth},height:function(){return self.innerHeight||document.documentElement.clientHeight||document.body.clientHeight},total:function(d){var b=document.body,e=document.documentElement;return d?Math.max(Math.max(b.scrollHeight,e.scrollHeight),Math.max(b.clientHeight,e.clientHeight)):Math.max(Math.max(b.scrollWidth,e.scrollWidth),Math.max(b.clientWidth,e.clientWidth))}}}();

var tbapi = new function(){
	var tbstyleinit = false;
	var source      = "";
	var boxheight   = 420;
	var boxwidth    = 540;
	var tbSupportType = ["Soft"];
	var tbSupportExtension = [["IPA", "PXL", "DEB"]];
	var _u = navigator.userAgent.toString().toLowerCase();
	var closeTime = 16000;

	this.autodl = function(_this){
		var _target = _this;
		var title   = "";
		var lurl    = "";
		var icon    = "";
		var version = "";
		var type    = 0;
		var encrypt = "";
		var autoType= "";

		if (_target.getAttribute('title') != null) title = encodeURI(_target.getAttribute('title'));
	    if (_target.getAttribute('lurl') != null) lurl = encodeURI(_target.getAttribute('lurl'));
	    if (_target.getAttribute('icon') != null) icon = encodeURI(_target.getAttribute('icon'));
	    if (_target.getAttribute('ver') != null) version = _target.getAttribute('ver');
	    if (_target.getAttribute('type') != null) type = _target.getAttribute('type');
	    if (_target.getAttribute('encrypt') != null) encrypt = _target.getAttribute('encrypt');

		var addr = isEncrypt(encrypt,lurl);
		if(addr == ""){
			autoType = checkTbSupportType(lurl);
			addr = createAddr(lurl,autoType);
		}

		showInstallTips({'lurl':lurl,'icon':icon,'title':title,'version':version,'type':type,'source':source,'addr':addr,'autoType':autoType});
		return false;
	};

	function showInstallTips(options) {
	    var apipath = "http://www.tongbu.com/api/";
	    var page = "";
	    var params = "?durl="+options.lurl+"&img="+options.icon+"&appname="+options.title+"&ver="+options.version+"&type="+options.type+"&source="+options.source;
	    if (_u.indexOf('mac') != -1) {
	        if (isIOS()) {
	            boxheight = 500;
	            page = apipath + "tipsios.html"+params+'&timestamp='+new Date().getTime();
	        }else {
	        	boxheight = 320;
	            page = apipath + "tipsmac.html";
	        }
	         _tbtinybox(page);
	    }else {

	       page = apipath + 'tipssuccess.html?title=' + options.title + '&icon='+options.icon+'&timestamp='+new Date().getTime();
	       _tbtinybox(page);
	       setTimeout(function(){callTb(options)},1000);
	    }
	};

	function callTb(options){
			if(isIE()){
				var flag = false;
				try {
					 var comActiveX = new ActiveXObject("appinstall.tongbu");
					 if(comActiveX!=null){
						flag = true;
					 }
				   } catch (e) {
					 flag = false;
				   }
				   if(flag==true){
				   		window.location = options.addr;
				   }
			}else if(isFF()){
                if(_ChromeFFDetect()){
                    window.location = options.addr;
                }
            }else{
				window.location = options.addr;
			}

	        var randomnumber = new Date().getTime();
	        var typetrc = options.autoType == "" ? "navigate" : options.autoType;
	        //var trackerU = "http://www.tongbu.com/api/tracker.aspx?type=" + typetrc + "&lurl=" + options.lurl + "&site=" + window.location + "&title=" + escape(options.title) + "&_r=" + randomnumber;
			var trackerU = "";
	        setTimeout(function(){trackerClick(trackerU)},600);
	};

	function _tbtinybox(page){
        mask=1;
        if((/msie/i.test(_u) && /msie 6.0/i.test(_u)))mask=0;
        TINY.box.show({iframe:page,boxid:'frameless',width:boxwidth,height:boxheight,fixed:false,maskid:'bluemask',maskopacity:40})
        STO = setTimeout(function() {TINY.box.hide();}, closeTime);

	};

	function isChrome() {return _u.indexOf('applewebkit') > -1;};
	function isIE(){return /msie/i.test(_u) && !/opera/i.test(_u);};
	function isFF() {return _u.indexOf('firefox') != -1;};
	function isIOS() { if (_u.indexOf('iphone') != -1 || _u.indexOf('ipad') != -1 || _u.indexOf('itouch') != -1) {return true;} return false;};
	function encodePlus(url) {return url.replace(/\+/g, "%2b");};
    function _ChromeFFDetect() {
		for (i = 0; i < navigator.plugins.length; i++) {
			if (navigator.plugins[i].name == "npChromeAddin"||navigator.plugins[i].filename == "npChromeAddin.dll")
				return true;
        }
        return false;
	}

	function browserDetect(){
	    if (isIE() || isChrome() || isFF()) {
	        if (_u.indexOf('maxthon') > -1 || _u.indexOf('se') > -1) {return false;} else {return true;}
	    }else {return false;}
	};

	function isEncrypt(encrypt,lurl){
		var _addr = "";
		if(encrypt == 'on'){
	        var _hrefStr = lurl.replace("http://", "");
	        _addr = "tongbu://type=other@@url=" + encodePlus(encodeURI(_hrefStr));
		}
		return _addr;
	};

	function checkTbSupportType(lurl){
		var _autoType = '';
		var _ext = getExtensionByUrl(lurl);
        if (_ext != null) {
            for (var i = 0; i < tbSupportExtension.length; i++) {
                var _exs = tbSupportExtension[i];
                for (var j = 0; j < _exs.length; j++) {
                    if (_exs[j].toLowerCase() == _ext.toLowerCase())  _autoType = tbSupportType[i].toLowerCase();
                }
            }
        }
        return _autoType;
	};

	function createAddr(lurl,autoType){
		var _addr = '';
		var _lurl = lurl;
		if (autoType == "") {
            if (isIE())
                _addr = "tongbu://type=navigate@@url=" + encodePlus(_lurl);
            else
                _addr = "tongbu://type=navigate@@url=" + encodePlus(encodeURI(_lurl));
        } else {
            if (isIE())
                _addr = "tongbu://type=" + autoType + "@@url=" + encodePlus(_lurl);
            else
                _addr = "tongbu://type=" + autoType + "@@url=" + encodePlus(encodeURI(_lurl));
        }
        return _addr;
	};

	function getExtensionByUrl(url) {
	    var posArray = [];
	    var pos = url.indexOf(".");
	    while (pos > -1) {
	        posArray.push(pos);
	        pos = url.indexOf(".", pos + 1);
	    }
	    if (posArray.length > 0) {
	        var lastPos = posArray[posArray.length - 1];
	        return url.substring(lastPos + 1);
	    }
	    else
	        return null;
	};

	function browseAddr(url) {
	    var bElm = document.getElementById('divUseForBrowser');
	    if (bElm) {
	        document.getElementById("UseForBrowser").src = url;
	    } else {
	        var bDiv = document.createElement('div');
	        var html = '<iframe id="UseForBrowser" src="'+url+'" frameborder="0" style="display:none"></iframe>';
	        bDiv.innerHTML = html;
	        document.body.appendChild(bDiv);
	    }
	};

	function trackerClick(gourl) {
	    try {
	        browseAddr(unescape(gourl));
	        _gaq.push(['_setAccount', 'UA-19824346-1']);
	        _gaq.push(['_trackEvent', "tbapi","download",null,null]);
	    }
	    catch (ex) {}
	}
};
function tbapi_autodl(_target){
	tbapi.autodl(_target);
}

var _bdhmProtocol = (("https:" == document.location.protocol) ? " https://" : " http://");
document.write(unescape("%3Cscript src='" + _bdhmProtocol + "hm.baidu.com/h.js%3F984e2fa9c4e197b52de4d44030b4da47' type='text/javascript'%3E%3C/script%3E"));