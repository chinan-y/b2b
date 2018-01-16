<?php
defined('GcWebShop') or exit('Access Invalid!');
/**
 * index
 */
$lang['db_index_min_size']		= 'subsection size for at least 10k';
$lang['db_index_name_exists']	= 'Backup name already exists, please fill in another name. ';
$lang['db_index_choose']		= 'Please select the database table you want to back up. ';
$lang['db_index_backup_to_wait']	= 'Backup is in progress, please wait.';
$lang['db_index_back_to_db']	= 'Return to database and backup.';
$lang['db_index_backup_succ']	= 'backup is ok ';
$lang['db_index_backuping']		= 'backuping';
$lang['db_index_backup_succ1']	= '#subsection data';
$lang['db_index_backup_succ2']	= 'Successfully created, the program will automatically continue';
$lang['db_index_db']			= 'database';
$lang['db_index_backup']		= 'backup';
$lang['db_index_restore']		= 'restore';
$lang['db_index_backup_method']	= 'backup mode';
$lang['db_index_all_data']		= 'backup all data';
$lang['db_index_spec_table']	= 'backup selected table';
$lang['db_index_table']			= 'data sheet';
$lang['db_index_size']			= 'subsection size';
$lang['db_index_name']			= 'backup name';
$lang['db_index_name_tip']		= 'Backup name is made of nubers from 1 to 20 , letters or underscores  ';
$lang['db_index_backup_tip']	= 'To ensure data integrity, please ensure that your site is off,  are you sure you want to immediately perform the current operation.';
$lang['db_index_help1']			= 'The data backup function backups all data or specified data according to your selection,exported data files can be led in by "data recovery" function or phpMyAdmin';
$lang['db_index_help2']			= 'regular database backup recommended';
/**
 * restore
 */
$lang['db_restore_file_not_exists']		= 'deleted file do not exist';
$lang['db_restore_del_succ']			= 'detele backup successfully';
$lang['db_restore_choose_file_to_del']	= 'please select the content to be deleted';
$lang['db_restore_backup_time']			= 'backup time';
$lang['db_restore_backup_size']			= 'backup size';
$lang['db_restore_volumn']				= 'subsection number';
$lang['db_restore_import']				= 'lead-in';
/**
 * lead-in
 */
$lang['db_import_back_to_list']			= 'back to database for backup';
$lang['db_import_succ']					= 'lead-in successfully';
$lang['db_import_going']				= 'leading-in';
$lang['db_import_succ2']				= 'lead-in successfully, program will contiue automatically';
$lang['db_import_fail']					= 'fail to lead in data';
$lang['db_import_file_not_exists']		= 'lead-in file does not exist';
$lang['db_import_help1']				= 'clicklead-in options and restore database ';
/**
 * delete
 */
$lang['db_del_succ']					= 'detele backup successfully';