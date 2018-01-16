<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * 
 */

/**
 * index
 */
$lang['store_goods_index_store_close']	 		= ' your store has closed';
$lang['store_goods_index_taobao_import']		= 'taobao manager has imported';
$lang['store_goods_index_new_goods']			= ' new aded goods';
$lang['store_goods_index_add_goods']			= 'publish new goods ';
$lang['store_goods_index_delivery_depot']		= ' delivery warehouse';
$lang['store_goods_index_add_time']				= ' publish time ';
$lang['store_goods_index_store_goods_class']	= 'sorting within the store ';
$lang['store_goods_index_state']	 			= ' conition';
$lang['store_goods_index_show']	 				= ' show goods';
$lang['store_goods_index_unshow']	 			= ' unshow goods';
$lang['store_goods_index_recommend']	 		= 'recommend ';
$lang['store_goods_index_lock']	 				= 'forbid sell';
$lang['store_goods_index_unlock']	 			= 'no ';
$lang['store_goods_index_close_reason']			= 'the reason for forbiding goods ';
$lang['store_goods_close_reason_des']			= ' sorting or unmatched specification information';
$lang['store_goods_index_sort']					= ' sorting';
$lang['store_goods_index_goods_name']	 		= ' goods name';
$lang['store_goods_index_goods_name_help']	 	= '3，50product title name length of at least 3 characters, up to 50 Chinese characters';
$lang['store_goods_index_rec_name_help']	 	= 'recorded product name for the unified version of the push single';
$lang['store_goods_index_goods_class']	 		= 'goods sorting ';
$lang['store_goods_index_brand']	 			= ' brand';
$lang['store_goods_index_price']	 			= ' price';
$lang['store_goods_index_stock']				= ' stock';
$lang['store_goods_index_goods_limit']			= ' you have reached the limit of adding goods';
$lang['store_goods_index_goods_limit1']			= 'if you would like to add goods, please upgrade store level in store level';
$lang['store_goods_index_pic_limit']			= 'you have reached the limit of image space ';
$lang['store_goods_index_pic_limit1']			= '"M，，“”if you would like continue to add goods, please upgrade level in "store level ';
$lang['store_goods_index_time_limit']			= '，，“”you have reached the store life, if you want to continue to increase the goods, please go to the "shop settings" upgrade shop level';
$lang['store_goods_index_no_pay_type']			= '，the platform has not set the payment method, please contact the platform in time';
$lang['store_goods_index_color']				= 'color';
$lang['store_goods_index_size']					= 'size';
$lang['store_goods_index_batch_upload']			= 'upload batches of images ';
$lang['store_goods_index_left']					= 'sort forward ';
$lang['store_goods_index_right']				= 'sort backward ';
$lang['store_goods_index_face']					= 'set as cover ';
$lang['store_goods_index_insert_editor']		= 'insert editor ';
$lang['store_goods_index_goods_class_null']		= ' goods sorting can not be empty';
$lang['store_goods_index_goods_class_error']	= ' （）choose goods sorting';
$lang['store_goods_index_goods_name_null']		= ' goods name can not be empty';
$lang['store_goods_index_rec_name_null']		= 'the record name can not be empty';
$lang['store_goods_index_goods_source_null']	= 'the source of the goods can not be empty';
$lang['store_goods_index_until_null']	        = '';//the legal unit of measure can not be empty
$lang['store_goods_index_Reduced_null']	        = 'the legal quantity can not be empty';
$lang['store_goods_index_line_post_ein_null']	= 'the line number can not be empty';
$lang['stroe_goods_Commodity_HSCODE_null']      ='HSCODEcommodity customs HSCODE can not be empty';                         
$lang['store_goods_index_declare_elements_null']= 'the declaration element can not be empty';
$lang['store_goods_index_declare_unit_null']	= 'the reporting unit can not be empty';
$lang['store_goods_index_goods_weight_null']	= 'the weight of the product can not be empty';
$lang['store_goods_index_store_price_null']		= 'commodity prices can not be empty';
$lang['store_goods_index_store_price_error']	= 'commodity prices can only be numbers';
$lang['store_goods_index_store_price_interval']	= '0.01~9999999the commodity price must be between 0.01 and 9999999';
$lang['store_goods_index_goods_stock_null']		= 'merchandise inventory can not be empty';
$lang['store_goods_index_goods_stock_error']	= 'stock can only fill in numbers';
$lang['store_goods_index_edit_goods_spec']		= 'edit product specifications';
$lang['store_goods_index_goods_spec_tip']		= ' （：）<br/>you can add up to two sizes (eg color and size). Name can be customized. Both specifications must be filled in complete';
$lang['store_goods_index_no']					= 'item number';
$lang['store_goods_index_new_goods_spec']		= 'add a new specification attribute';
$lang['store_goods_index_save_spec']			= 'save specifications';
$lang['store_goods_index_new_class']			= 'add a new category';
$lang['store_goods_index_belong_multiple_store_class']	= '"， " ->  -> " The merchandise can be subordinate to multiple categories of stores, and the shop category can be customized by the "Merchant Center -> Store -> Store Categories';
$lang['store_goods_index_goods_base_info']		= ' the basic information';
$lang['store_goods_index_goods_detail_info']	= ' the detailed description of goods';
$lang['store_goods_index_goods_upload_image']	= "'，'"; //product pictures and product details map must click on the 'next step, upload product pictures' and then come back to upload "; / / new to add
$lang['store_goods_index_goods_transport']		= 'commodity logistics information';
$lang['store_goods_index_goods_szd']			= 'location ';
$lang['store_goods_index_goods_delivery']		= ' delivery';
$lang['store_goods_index_use_tpl']				= ' use freight template';
$lang['store_goods_index_select_tpl']			= 'choose freight template ';
$lang['store_goods_index_goods_other_info']		= 'other information ';
$lang['store_goods_index_upload_goods_pic']		= 'upload goods image ';
$lang['store_goods_index_remote_url']			= 'remote address ';
$lang['store_goods_index_remote_tip']			= 'JPEGGIF，GIF，2M.ctrlshiftsupport JPEG and static GIF format images, do not support animated GIF images, upload pictures can not exceed the size of 2M. Browse files can hold down ctrl or shift key multi-election';
$lang['store_goods_index_goods_brand']			=  'brand';
$lang['store_goods_index_multiple_tag']			= '","Tag "," multiple tags are separated by a comma ';
$lang['store_goods_index_store_price']			= ' goods price';
$lang['store_goods_index_store_price_help']		= '0.01~9999999the price must be between 0.01 and 9999999';
$lang['store_goods_index_goods_stock']			= ' goods stock';
$lang['store_goods_index_goods_stock_checking']	= '0~999999999shop inventory must be an integer between 0 and 999999999';
$lang['store_goods_index_goods_stock_help']		= '0~999999999<br/>，，the number of store stocks must be an integer between 0 and 999999999. If the inventory configuration is enabled, the system automatically calculates the total number of items,';
$lang['store_goods_index_goods_pyprice_null']	= ' lake of snail price';
$lang['store_goods_index_goods_kdprice_null']	= 'lake of express price ';
$lang['store_goods_index_goods_emsprice_error']	= 'ems price format is wrong EMS';
$lang['store_goods_index_goods_select_tpl']		= 'please select the shipping template to use';
$lang['store_goods_index_goods_weight_tag']     = '：(Kg)kg';
$lang['store_goods_index_goods_transfee_charge']= ' freight';
$lang['store_goods_index_goods_transfee_charge_seller']= 'freight charged by seller ';
$lang['store_goods_index_goods_transfee_charge_buyer']= ' freight charged by buyer';
$lang['store_goods_index_goods_no']				= 'seller number ';
$lang['empty_validity']							= 'clear validity period ';
$lang['empty_alarm']							= ' '; // newly added'; //Clear the validity period warning date
$lang['store_goods_index_goods_no_help']		= 'merchandise number refers to the number of merchandise merchandise, buyers can not see up to 20 characters can be entered, support input Chinese, letters, numbers, _, /, - and decimal point';
$lang['srore_goods_index_goods_stock_set']		= 'inventory configuration';
$lang['store_goods_index_goods_spec']			= 'product specifications';
$lang['store_goods_index_open_spec']			= 'open specifications';
$lang['store_goods_index_spec_tip']				= 'you can add up to two kinds of product specifications (such as: color, size) If the product is no specifications do not have to add';
$lang['store_goods_index_edit_spec']			= 'edit specifications ';
$lang['store_goods_index_close_spec']			= ' close specifications';
$lang['store_goods_index_goods_attr']			= 'goods attribute ';
$lang['store_goods_index_goods_show']			= ' goods publish';
$lang['store_goods_index_immediately_sales']	= ' publish instanntly';
$lang['store_goods_index_in_warehouse']			= 'put to warehouse ';
$lang['store_goods_index_goods_recommend']		= ' goods recommend';
$lang['store_goods_index_recommend_tip']		= 'the recommended goodes will be displayed in the first page of the store ';
$lang['store_goods_index_goods_desc']			= ' goods description';
$lang['store_goods_index_goods_video']			= 'goods video ';   //
$lang['store_goods_index_goods_video_format']	= 'http://video.qqbsmall.com/goodsVideo/'; //form
$lang['store_goods_index_upload_pic']			= ' upload images';
$lang['store_goods_index_spec']					= ' specification';
$lang['store_goods_index_edit_goods']			= ' edit goods';
$lang['store_goods_index_add_sclasserror']		= ',the classification has been selected, please select another category';
$lang['store_goods_index_goods_add_success']	= ' adding  goods succeed';
$lang['store_goods_index_goods_add_fail']		= 'adding goods failed ';
$lang['store_goods_index_goods_edit_success']	=  'editting goods succeed';
$lang['store_goods_index_goods_edit_fail']		= ' editting goods failed';
$lang['store_goods_index_goods_del_success']	= 'deleting  goods succeed ';
$lang['store_goods_index_goods_del_fail']		= ' deleting goods failed';
$lang['store_goods_index_goods_unshow_success']	= 'unshow succeed ';
$lang['store_goods_index_goods_unshow_fail']	= ' unshow failed';
$lang['store_goods_index_goods_show_success']	= ' show succeed';
$lang['store_goods_index_goods_show_fail']		= 'show failed';
$lang['store_goods_index_goods_seo_keywords']		    = ' SEO<br/>(keywords)seo keywords';
$lang['store_goods_index_goods_seo_description']		= 'SEO<br/>(description)seo description ';
$lang['store_goods_index_goods_seo_keywords_help']		= '"SEO (keywords)  Meta ，<br/>， "," SEO keywords appear in the Meta tab of the head of the merchandise detail page, and the keywords used to record the items on this page are separated by a comma ';
$lang['store_goods_index_goods_seo_description_help']   = ' "SEO (description)  Meta ，<br/>，120the "SEO description" appears in the Meta tab of the header page of the merchandise detail page, which is used to record the summary and description of the contents of this page. It is recommended that the word';
$lang['store_goods_index_goods_del_confirm']			= '?are you sure you want to delete the image?';
$lang['store_goods_index_goods_not_add']				= 'can not add image more ';
$lang['store_goods_index_goods_the_same']				= ' can not repeat images';
$lang['store_goods_index_default_album']				= '';
$lang['store_goods_index_flow_chart_step1']				= 'choose the category ';
$lang['store_goods_index_flow_chart_step2']				= 'fill in detailed information of goods ';
$lang['store_goods_index_flow_chart_step3']				= 'release goods successfully ';
$lang['store_goods_index_again_choose_category1']       = '，，。the category you selected does not exist, or does not choose the last level, please re-select the category';
$lang['store_goods_index_again_choose_category2']       = '，。your shop does not bind the category, please re-select the category';
$lang['store_goods_add_next']							= ' next step';
$lang['store_goods_step2_image']						= ' （）image';
$lang['store_goods_step2_start_time']					= ' publishing time';
$lang['store_goods_step2_hour']							= 'hour ';
$lang['store_goods_step2_minute']						= ' minute';
$lang['store_goods_step2_goods_form']					= ' goods type';
$lang['store_goods_step2_brand_new']					= ' brand new';
$lang['store_goods_step2_second_hand']					= ' second hand';
$lang['store_goods_step2_exist_image']					= ' existed images';
$lang['store_goods_step2_exist_image_null']				= ' no images';
$lang['store_goods_step2_spec_img_help']				= 'jpg、jpeg、gif、png。<br />310x310、%.2fM。<br />，。support jpg, jpeg, gif, png format images. <br /> Suggested upload size 310x310, size% .2fM within the picture. <br /> Product Details Page When you select a color image, the color image will be displayed in the product display area';
$lang['store_goods_step2_description_one']				= '5。up to 5 product images can be posted';
$lang['store_goods_step2_description_one']				= '，；jpg、gif、png，<font color="red">800x800、1M</font>，。upload the default picture of the product, such as multi-standard value will be the default use of the map or sub-specifications upload the main map; support jpg, gif, png format upload or select from the picture space, it is recommended to use <font color = "red" Size of more than 800x800 pixels, the size of not more than 1M square picture </ font>, upload the picture will be automatically saved in the default image space category.';
$lang['store_goods_step2_description_three']			= '。the picture can be adjusted by the arrows on both sides of the display order';
$lang['store_goods_album_climit']						= ' you upload the number of pictures to reach the limit, please upgrade your shop or contact the administrator';
/**
 * 
 */
$lang['store_goods_step1_search_category']				= 'classification search：';
$lang['store_goods_step1_search_input_text']			= 'Please enter the product name or classification attribute name';
$lang['store_goods_step1_search']						= ' search';
$lang['store_goods_step1_return_choose_category']		= 'return to product category selection';
$lang['store_goods_step1_search_null']					= '。do not find the relevant commodity classification';
$lang['store_goods_step1_searching']					= '...searching';
$lang['store_goods_step1_loading']						= ' ...loading';
$lang['store_goods_step1_choose_common_category']		= ' ：common image classification';
$lang['store_goods_step1_please_select']				= 'please select ';
$lang['store_goods_step1_no_common_category']			= ' you have not add the common classification';
$lang['store_goods_step1_please_choose_category']		= 'please select the goods classification ';
$lang['store_goods_step1_current_choose_category']		= ' you current goods classification is';
$lang['store_goods_step1_add_common_category']			= ']add to common classification [';
$lang['store_goods_step1_next']							= ' next step';
$lang['store_goods_step1_max_20']						= 'can only add 20 common categories, please clean up the classification is not commonly used or repeated';
$lang['store_goods_step1_ajax_add_class']				= 'add common classification successfully ';

/**
 * 
 */
$lang['store_goods_step3_goods_release_success']		= '!，！congratulations,  publish merchandise successfully ';
$lang['store_goods_step3_viewed_product']				= 'go to the shop to view product details';
$lang['store_goods_step3_edit_product']					= 're-edit just published items';
$lang['store_goods_step3_more_actions']					= 'you can :';
$lang['store_goods_step3_continue']						= ' continue';
$lang['store_goods_step3_release_new_goods']			= 'publise new goods ';
$lang['store_goods_step3_access']						= ' enter to';
$lang['store_goods_step3_manage']						= ' manage ';
$lang['store_goods_step3_choose_product_add']			= ' choose goods to add application';
$lang['store_goods_step3_choose_add']					= ' choose goods to participate';
$lang['store_goods_step3_groupbuy_activity']			= ' purchase activity';
$lang['store_goods_step3_participation']				= ' participate in shopmall';
$lang['store_goods_step3_special_activities']			= ' topic activity';

/**
 * 
 */
$lang['store_goods_brand_apply']				= ' brand application';
$lang['store_goods_brand_name']					= 'brand name ';
$lang['store_goods_brand_my_applied']			= ' my application';
$lang['store_goods_brand_icon']					= ' brand icon';
$lang['store_goods_brand_belong_class']			= 'category ';
$lang['store_goods_brand_no_record']			= 'no eligible brand ';
$lang['store_goods_brand_input_name']			= 'please input brand name ';
$lang['store_goods_brand_name_error']			= 'brand name should be less than 100 charcters 100';
$lang['store_goods_brand_icon_null']			= 'please upload brand icon ';
$lang['store_goods_brand_edit']					= 'edit brand';
$lang['store_goods_brand_class']				= ' brand classification';
$lang['store_goods_brand_pic_upload']			= 'upload image ';
$lang['store_goods_brand_upload_tip']			= '88x44。<br />，，。，。It is recommended to upload a brand image of size 88x44. <br /> The purpose of the application for the brand is to facilitate buyers through the brand index page to find goods, apply the brand to fill in the category, to facilitate the owners classified. You can edit or revoke an application before reviewing the webmaster.';
$lang['store_goods_brand_name_null']			= 'brand name can not be empty ';
$lang['store_goods_brand_apply_success']		= ' ，save successfully, wait to review by system';
$lang['store_goods_brand_choose_del_brand']		= 'please choose the contents you need to delete !';
$lang['store_goods_brand_browse']				= ' ...browse';
/**
 * 
 */
$lang['store_goods_upload_pic_limit']			= 'you have reached the upper limit of the picture space';
$lang['store_goods_upload_pic_limit1']			= '，，“”M, if you want to continue to increase the goods, please go to the "shop settings" upgrade shop levelM';
$lang['store_goods_upload_fail']				= 'uploading failed ';
$lang['store_goods_upload_upload']				= 'upload ';
$lang['store_goods_upload_normal']				= 'commom upload ';
$lang['store_goods_upload_del_fail']			= ' deleting  image  failed';
$lang['store_goods_img_upload']					= ' upload image';
/**
 * 
 */
$lang['store_goods_album_goods_pic']			= ' goods image';
$lang['store_goods_album_select_from_album']	= ' select from user image';
$lang['store_goods_album_users']				= ' user iamge';
$lang['store_goods_album_all_photo']			= 'all images ';
$lang['store_goods_album_insert_users_photo']	= ' insert album images';
/**
 * ajax
 */
$lang['store_goods_ajax_find_none_spec']		= 'not find the goods specification ';
$lang['store_goods_ajax_update_fail']			= 'updating   database  failed ';
/**
 * 
 */
$lang['store_goods_import_choose_file']		= 'csvplease select the file to upload csv';
$lang['store_goods_import_unknown_file']	= 'the source of the document is unknown';
$lang['store_goods_import_wrong_type']		= 'csv,:the file type must be csv, and the file type you uploaded is';
$lang['store_goods_import_size_limit']		= ".ini_get('upload_max_filesize').'the file size must be '.ini_get (' upload_max_filesize '). Within ";
$lang['store_goods_import_wrong_class']		= '（）Please select the category (must be selected at the last level';
$lang['store_goods_import_wrong_class1']	= '，（）the product category is not available. Please re-select the product category (must be selected at the last level';
$lang['store_goods_import_wrong_class2']	= 'must be selected at the last level';
$lang['store_goods_import_wrong_column']	= ',The fields in the file do not match the fields requested by the system. Please read the import instructions carefully';
$lang['store_goods_import_choose']			= ' ...please select';
$lang['store_goods_import_step1']			= '：CSVstep 1: Import the CSV file';
$lang['store_goods_import_choose_csv']		= 'please select the file：';
$lang['store_goods_import_title_csv']		= '，CSV，'.ini_get('upload_max_filesize').'the import program defaults to the import from the second line, leaving the header row of the first line of the CSV file, the largest '.ini_get (' upload_max_filesize ');
$lang['store_goods_import_goods_class']		= ' ：goods classification';
$lang['store_goods_import_store_goods_class']	= ' classification：';
$lang['store_goods_import_new_class']			= 'added classifiacation ';
$lang['store_goods_import_belong_multiple_store_class']	= ' can belong to many classification';
$lang['store_goods_import_unicode']			= '：character decoding ';
$lang['store_goods_import_file_type']		= ' ：files format';
$lang['store_goods_import_file_csv']		= 'csv filescsv';
$lang['store_goods_import_desc']			= ' ：input explanation';
$lang['store_goods_import_csv_desc']		= ' <br/>1.CSVexcel，: 
、、、、、、、、EMS、、、、。<br/>1. if you modify the CSV file, please be sure to use Microsoft excel software, and must ensure that the first row of the header name contains the following items:
Baby name, baby category, new and old level, baby price, baby number, valid, freight bear, surface mail, EMS, express, window recommended, baby description, new pictures.
2.x <Br /2.，，、、，。<br/>if the CSV file exceeds '.ini_get (' upload_max_filesize ').' Please use the excel software to edit and split into multiple files for import. 
3.<br/>CSV'.ini_get('upload_max_filesize').'excel。<br/>if the CSV file exceeds '.ini_get (' upload_max_filesize ').' Please use the excel software to edit and split into multiple files for import. 
4.5each item supports up to 5 images。';
$lang['store_goods_import_submit']			= 'import ';
$lang['store_goods_import_step2']			= '：Step 2: upload the product picture';
$lang['store_goods_import_tbi_desc']		= 'csvimages(csv)tbiPlease upload the tbi file in the same directory as the csv file (or the directory with the same name as the csv file)';
$lang['store_goods_import_upload_complete'] = "g complete uploadin";
$lang['store_goods_import_doing'] 			= " ...uploading";
$lang['store_goods_import_step3']			= 'Step 3: organize the data';
$lang['store_goods_import_remind']			= '，After the first two steps to complete the data after finishing, to confirm the data?';
$lang['store_goods_import_remind2']			= '（，）(If the image is uploaded multiple times, please post in all pictures upload)';
$lang['store_goods_import_pack']			= ' pack up data';
$lang['store_goods_pack_wrong1']			= ' CSVplease inport csv files correctly';
$lang['store_goods_pack_wrong2']			= ' CSVplease inport correct csv files';
$lang['store_goods_pack_success']			= 'pack up data successfully ';
$lang['store_goods_import_end']				= '，end';
$lang['store_goods_import_products_no_import']	= 'goods have not been inported ';
$lang['store_goods_import_area']			= 'location ：';

/**/
$lang['store_goods_import_goodsname'] = ' goods name';
$lang['store_goods_import_goodscid'] = ' goods category';
$lang['store_goods_import_goodsprice'] = ' goods price';
$lang['store_goods_import_goodsnum'] = ' goods number';
$lang['store_goods_import_goodstuijian'] = ' window recommendation';
$lang['store_goods_import_goodsdesc'] = 'goods description ';
$lang['store_goods_import_goodspic'] = 'new image ';
$lang['store_goods_import_goodsproperties'] = 'selling attribute binding ';
$lang['store_goods_import_upload_album'] = 'inport image ';
$lang['para_error'] = 'parameter erroe';

/**
 * ajax
 */
$lang['store_goods_title_change_tip']		= '，<br/>3，50click to modify the product title name, the length of at least 3 characters, up to 50 Chinese characters';

/**
 * ajax
 */
$lang['store_goods_stock_change_stock']		= ' alter stock';
$lang['store_goods_stock_change_tip']		= 'click and alter stock ';
$lang['store_goods_stock_stock_sum']		= ' stock number';
$lang['store_goods_stock_change_more_stock']= 'alter more stock information ';
$lang['store_goods_stock_input_error']		= 'please fill in numbers more  than 0!';

/**
 * ajax
 */
$lang['store_goods_price_change_price']		= ' alter price';
$lang['store_goods_price_change_tip']		= 'click and alter price ';
$lang['store_goods_price_change_more_price']= 'alter more price infpormation ';
$lang['store_goods_price_input_error']		= ' please fill in right price！';

/**
 * ajax
 */
$lang['store_goods_commend_change_tip']		= ' choose to whether to be the recommended goods';

?>
