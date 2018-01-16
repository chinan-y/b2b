<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * 
 */

$lang['taobao_import_online_skey_tip1']	 		= '，。sessionKey is a link to the seller is shop, through the Taobao open platform assigned to the userSessionKey';
$lang['taobao_import_online_skey_tip2']	 		= 'click here to login for authorization ';
$lang['taobao_import_online_skey_tip3']	 		= 'SessionKey，SessionKey，，<br/>SessionKey，，。get the authorized SessionKey, please paste the SessionKey into the input box, the mall does not save the value, please keep their own live storage session of the time is about a day, if the value expires, you need to log in again Get authorization';
$lang['taobao_import_online_pageno']	 		= 'grab the first few pages';
$lang['taobao_import_online_pagesize']	 		= 'number of items per page';
$lang['taobao_import_online_tip4']	 			= '1。：260，：<br/>： “1”，，。<br/>： ，100，，。<br/>： “2”，，。<br/>： ，200，，。<br/>： “3”，，。<br/>，260。。Defaults to 1. Use the scene: my shop in the total number of goods sold 260, the import process is: the first step: crawl the first few pages fill in "1", enter the other items after the submission, waiting for all the import is completed. Step 2: At this point, a total of 100 pieces are imported, the page is refreshed, and a second import is prepared. The third step: fill the first few pages fill in "2", enter the other items after the submission, waiting for all the import is completed. Step 4: At this point, a total of 200 pieces are imported, the page is refreshed, and the third import is prepared. Step 5: Grab the first few pages into the "3", enter the other items after the submission, waiting for all the import is completed. At this point, a total of 260 were introduced. The import is complete';
$lang['taobao_import_online_begintime']	 		= 'begintime';
$lang['taobao_import_online_endtime']	 		= 'endtime';
$lang['taobao_import_online_spec_key']	 		= 'specification name ';
$lang['taobao_import_online_tip5']	 			= '，<a href="%s" target="_blank">，</a>，，，，，，。the name is separated by a comma, <a href="%s" target="_blank"> is not sure what is the name of the name, click here </a>, when the mall can not distinguish between Taobao data source of the product attributes which specifications , The attributes containing the above name will be saved as specifications, and the other will be saved as attributes. If you do not fill in, the system will automatically distinguish between specifications and attributes, but the accuracy will be reduced';
$lang['taobao_import_online_spec_key_value']	= ',,color, color classification, size';
$lang['taobao_import_online_tip6']	 			= '，：<br/>，，，，“taobao”，，，，。after the goods import is completed, it is important to check whether the details of each item after import are correct, and if the imported information does not match the Taobao information, please change it manually. The classification of the goods is placed To the name of "taobao" under the top of the classification, such as the need to adjust the classification, please contact the system administrator, if the individual import failed goods, please manually import';
$lang['taobao_import_online_allow_import']	 	= '\r\n：\r\n 1. taobao_app_keytaobao_secret_key\r\n 2. \r\ntaobao_app_key and taobao_secret_key \ r \ n 2. Synchronous Taobao commodity category \ r \ n If you have questions with the management of the product, Contact';
$lang['taobao_import_online_importing']	 		= '...uploadding ';
$lang['taobao_import_online_input_sessionkey']	= 'sessionKey is requiredSessionKey';
$lang['taobao_import_online_type_error']	 	= 'the format is incorrect';
$lang['taobao_import_online_import_success']	= 'upload successfully';
$lang['taobao_import_online_import_fail']	 	= 'uploadding fails ';
$lang['taobao_import_online_import_ok']	 		= 'all is uploadding ';
$lang['taobao_import_online_goods_limit']		= '，“”your product is full, please go to the "Shop Settings" upgrade shop level to get more publishing rights';
$lang['taobao_import_online_time_limit']		= '，，“”you have reached the limit of store life, if you want to continue to increase the goods, please go to the "shop settings" upgrade shop level';
$lang['taobao_import_online_goods_none']		= 'not find the goods ';
?>