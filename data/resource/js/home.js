$(function(){
	//代金券兑换功能
    $("[nc_type='exchangebtn']").live('click',function(){
    	var data_str = $(this).attr('data-param');
	    eval( "data_str = "+data_str);
	    ajaxget('index.php?gct=pointvoucher&gp=voucherexchange&dialog=1&vid='+data_str.vid);
	    return false;
    });
});