$(function () {
	
	//手机验证规则  
	jQuery.validator.addMethod("mobile", function (value, element) {
		var mobile = /^1[3|4|5|7|8]\d{9}$/;
		return this.optional(element) || (mobile.test(value));
	}, "手机格式不对");


	//身份证验证规则 
	jQuery.validator.addMethod("idCard", function (value, element) {
		var isIDCard1=/^[1-9]\d{7}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}$/;//(15位)
		var isIDCard2=/^[1-9]\d{5}[1-9]\d{3}((0\d)|(1[0-2]))(([0|1|2]\d)|3[0-1])\d{3}([0-9]|X|x)$/;//(18位)
		return this.optional(element) || (isIDCard1.test(value)) || (isIDCard2.test(value));
	}, "身份证格式不对");

	//护照编号验证
	jQuery.validator.addMethod("passport", function(value, element) { 
		return this.optional(element) || checknumber(value);     
	}, "请正确输入您的护照编号"); 

	//验证护照是否正确
	function checknumber(number){
		var str=number;
		//在JavaScript中，正则表达式只能使用"/"开头和结束，不能使用双引号
		var Expression=/(P\d{7})|(G\d{8})/;
		var objExp=new RegExp(Expression);
		if(objExp.test(str)==true){
		   return true;
		}else{
		   return false;
		} 
	};
	
	//借记卡
	$('#reg').validate({
		onsubmit:true,// 是否在提交是验证 
		onfocusout:false,// 是否在获取焦点时验证 
		onkeyup :false,// 是否在敲击键盘时验证 
		rules : {
			bankCard : {
				required : true,
				minlength : 16
			},
			idCard: { 
		     	required: true 
			},
			chinaName: { 
		     	required: true
			},
			mobile: { 
		     	required: true,    
			 	mobile: true  
			}
		},
		messages : {
			bankCard : {
				required : '银行卡号不能为空',
				minlength : '必须是数字，银行卡号不得小于16位'
			},
			idCard: { 
				required: "证件号不能为空"
			},
			chinaName: { 
		     	required: "姓名不能为空"
			},
			mobile: { 
				required: "手机号不能为空", 
				mobile: "手机号格式不正确"
			}		 
		},
		submitHandler: function(form) {
			var param = $("#reg").serialize(); 
			$.ajax({ 
				url : "index.php?gct=payment&gp=bind", 
				type : "post", 
				dataType : "json", 
				data: param, 
				success : function(result) {
					if(result.state==200){
						$('#bind .province').remove();
						$('#bind .shouhuore_tan').append('<form id="codeForm" align="center"><input type="hidden" name="txSNBinding" value="'+result.message+'" ><p><lable style="color:red;margin-left:135px;">请输入手机验证码</lable><input type="text" name="code"></p><br><p><input style="margin-left:116px;" type="button" id="checkCode" value="提交"></p></form>')
					}else{
						alert(result.message)
					}
				}
			})
		},
		invalidHandler: function(form, validator) {  //不通过回调 
			return false; 
        } 
	});

	//信用卡
	$('#regb').validate({
		onsubmit:true,// 是否在提交是验证 
		onfocusout:false,// 是否在获取焦点时验证 
		onkeyup :false,// 是否在敲击键盘时验证 
		rules : {
			bankCard : {
				required : true,
				minlength : 16
			},
			idCard: { 
		     	required: true
			},
			chinaName: { 
		     	required: true
			},
			mobile: { 
		     	required: true,    
			 	mobile: true  
			},
			cnv2:{
				required : true,
				minlength : 3
			},
			validDate:{
				required : true
			}
		},
		messages : {
			bankCard : {
				required : '银行卡号不能为空',
				minlength : '必须是数字，银行卡号不得小于16位'
			},
			idCard: { 
				required: "证件号不能为空"
			},
			chinaName: { 
		     	required: "姓名不能为空"
			},
			mobile: { 
				required: "手机号不能为空", 
				mobile: "手机号格式不正确"
			},
			cnv2 : {
				required : '信用卡背面的三位数不能为空',
				minlength : '请输入三位数'
			},
			validDate:{
				required : '请选择信用卡有效期'
			}
		},
		submitHandler: function(form) {
			var param = $("#regb").serialize(); 
			$.ajax({ 
				url : "index.php?gct=payment&gp=bind", 
				type : "post", 
				dataType : "json", 
				data: param, 
				success : function(result) {
					if(result.state==200){
						$('#bind .province').remove();
						$('#bind .shouhuore_tan').append('<form id="codeForm" align="center"><input type="hidden" name="txSNBinding" value="'+result.message+'" ><p><lable style="color:red;margin-left:135px;">请输入手机验证码</lable><input type="text" name="code"></p><br><p><input style="margin-left:116px;" type="button" id="checkCode" value="提交"></p></form>')
					}else{
						alert(result.message)
					}
				}
			})
		},
		invalidHandler: function(form, validator) {  //不通过回调 
			return false; 
        } 
	});

});
