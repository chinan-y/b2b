<?php
defined('GcWebShop') or exit('Access Invalid!');

$lang['store_goods_index_goods_limit']			= '  you have reached the limit of adding goods';
$lang['store_goods_index_goods_limit1']			= '，，“” if you want to add to goods, please go to the "shop settings" upgrade shop level';
$lang['store_goods_index_pic_limit']			= '  you have reached the limit of image space';
$lang['store_goods_index_pic_limit1']			= '，“”M，if you want to add to goods, please go to the "shop settings" upgrade shop level ';
$lang['store_goods_index_time_limit']			= '，，“”you have reached the limit of adding goods ,if you want to add to goods, please go to the "shop settings" upgrade shop level ';
$lang['store_goods_index_no_pay_type']			= '，the platform has not set the payment method, please contact the platform in time ';
/**
 * 
 */
$lang['store_goods_upload_pic_limit']			= 'you have reached the upper limit of the picture space ';
$lang['store_goods_upload_pic_limit1']			= '，“”M，if you want to add to goods, please go to the "shop settings" upgrade shop level ';
$lang['store_goods_upload_fail']				= ' uploading fail ';
$lang['store_goods_upload_upload']				= 'upload  ';
$lang['store_goods_upload_normal']				= ' common upload ';
$lang['store_goods_upload_del_fail']			= ' deleting image fail ';
$lang['store_goods_img_upload']					= 'upload image  ';

/**
 * 
 */
$lang['store_goods_import_choose_file']		= ' csvplease select the csv files ';
$lang['store_goods_import_unknown_file']	= 'the unknown files  ';
$lang['store_goods_import_wrong_type']		= ' csv,:the file types should be csv,your file types should be ';
$lang['store_goods_import_size_limit']		= ''.ini_get('upload_max_filesize').'the size should be ('upload_max_filesize'). ';
$lang['store_goods_import_wrong_class']		= '（）please choose to goods sorting (must choose to last level)';
$lang['store_goods_import_wrong_class1']	= '，（）the product category is not available, please re-select the product category (must be selected to the last level) ';
$lang['store_goods_import_wrong_class2']	= 'must be selected at the last level ';
$lang['store_goods_import_wrong_column']	= ',the fields in the file do not match the fields requested by the system. Please read the import instructions carefully ';
$lang['store_goods_import_choose']			= ' ...please choose ';
$lang['store_goods_import_step1']			= '：CSVstep 1:inport csv files  ';
$lang['store_goods_import_choose_csv']		= ' ：please select files ';
$lang['store_goods_import_title_csv']		= '，CSV，the import program defaults to the import from the second line, leaving the header row of the first line of the CSV file, the largest '.ini_get (' upload_max_filesize ') '.ini_get('upload_max_filesize')';
$lang['store_goods_import_goods_class']		= ' ：goods sorting ';
$lang['store_goods_import_store_goods_class']	= '：sorting  ';
$lang['store_goods_import_new_class']			= ' add sorting ';
$lang['store_goods_import_belong_multiple_store_class']	= 'can be subordinate to a number of shop classification ';
$lang['store_goods_import_unicode']			= '：character encoding ';
$lang['store_goods_import_file_type']		= '：file type ';
$lang['store_goods_import_file_csv']		= 'csv files csv ';
$lang['store_goods_import_desc']			= ' ：inport instrction ';
$lang['store_goods_import_csv_desc']		= '1.1.CSVexcel，: 
、、、、、、、、EMS、、、、。<br/> If you modify the CSV file, please be sure to use Microsoft excel software, and must ensure that the first row of the header name contains the following items:
Baby name, baby category, new and old level, baby price, baby number, valid, freight bear, surface mail, EMS, express, window recommended, baby description, new pictures. <br/>
2.，，、、，。<br/>
2. if the Taobao Assistant version of the difference between the name of the table are different, please modify the name of the above can be imported, do not distinguish between new, second-hand, idle and other old and new, after the introduction of commodity types are new. <br/>
3.CSV'.ini_get('upload_max_filesize').'excel。<br/>
3. if the CSV file exceeds '.ini_get (' upload_max_filesize ').' Please use the excel software editor to split into multiple files for import.
4.5。
';4. each item supports up to 5 images.inport
$lang['store_goods_import_submit']			= '  ';
$lang['store_goods_import_step2']			= '：step2: upload goods image  ';
$lang['store_goods_import_tbi_desc']		= 'csvimages(csv)tbiPlease upload the tbi file in the same directory as the csv file (or the directory with the same name as the csv file) ';
$lang['store_goods_import_upload_complete'] = " complete uploading ";
$lang['store_goods_import_doing'] 			= "...uploading ";
$lang['store_goods_import_step3']			= '：step 3 pack up data';
$lang['store_goods_import_remind']			= '，after the first two steps to complete the data can be sorted out to confirm the finishing data?';
$lang['store_goods_import_remind2']			= '（，）If the image is uploaded multiple times, please post in all pictures upload)';
$lang['store_goods_import_pack']			= ' pake up data';
$lang['store_goods_pack_wrong1']			= 'CSVplease inport csv files ';
$lang['store_goods_pack_wrong2']			= ' CSVplease inport correct csv files';
$lang['store_goods_pack_success']			= 'pack up data successfully ';
$lang['store_goods_import_end']				= '，end ';
$lang['store_goods_import_products_no_import']	= 'do not inport any products ';
$lang['store_goods_import_area']			= ' ：locaion';

/**/
$lang['store_goods_import_upload_album'] = ' inport images options';
$lang['store_goods_index_batch_upload']	 = ' upload batches of images';

/**
 * ajax
 */
$lang['store_goods_title_change_tip']		= '，<br/>3，50Click to modify the product title name, the length of at least 3 characters, up to 50 Chinese characters';

/**
 * ajax
 */
$lang['store_goods_stock_change_stock']		= ' alter stock';
$lang['store_goods_stock_change_tip']		= 'click to alter stock ';
$lang['store_goods_stock_stock_sum']		= 'total stock ';
$lang['store_goods_stock_change_more_stock']= 'modify more inventory information';
$lang['store_goods_stock_input_error']		= '!Please fill in a number not less than zero';

/**
 * ajax
 */
$lang['store_goods_price_change_price']		= ' alte price';
$lang['store_goods_price_change_tip']		= ' click and alter price';
$lang['store_goods_price_change_more_price']= 'alter more price information ';
$lang['store_goods_price_input_error']		= '！please fill in correct price ';

/**
 * ajax
 */
$lang['store_goods_commend_change_tip']		= ' choose whether to recommend the goods';

?>
