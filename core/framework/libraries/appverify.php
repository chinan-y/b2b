<?php
/**
 * APP验证管理
 *
 
 */
defined('GcWebShop') or exit('Access Invalid!');

class Appverify {

	/**
	 *	数据基础验证-是否是数字类型 
	 * 	@param  string $value 需要验证的值
	 *  @return bool
	 */
	public function isNumber($value)
	{
		return preg_match('/\d+$/', trim($value));
	}
	
	/**
	 *	数据基础验证-是否是身份证
	 * 	@param  string $value 需要验证的值
	 *  @return bool
	 */
	public function isCard($value)
	{
		return preg_match("/^(\d{15}|\d{17}[\dx])$/i", $value);
	}
	
	/**
	 *	数据基础验证-是否是电话 验证：0571-xxxxxxxx
	 * 	@param  string $value 需要验证的值
	 *  @return bool
	 */
	public function isPhone($value)
	{
		return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(\(0\d{2,3}\)|0\d{2,3}-)?[1-9]\d{6,7}(\-\d{1,4})?$/', trim($value));
	}
	
	/**
	 *	数据基础验证-是否是移动电话 验证：1385810XXXX
	 * 	@param  string $value 需要验证的值
	 *  @return bool
	 */
	public function isMobile($value)
	{
		return preg_match('/^((\(\d{2,3}\))|(\d{3}\-))?(13|14|15|18|17)\d{9}$/', trim($value));
	}

	/**
	 *	数据基础验证-是否是中文
	 * 	@param  string $value 需要验证的值
	 *  @return bool
	 */
	public function isChinese($value)
	{
		return preg_match("/^([\xE4-\xE9][\x80-\xBF][\x80-\xBF]){2,5}$/", trim($value));
	}

	/**
	 *	数据基础验证-是否是Email 验证：xxx@qq.com
	 * 	@param  string $value 需要验证的值
	 *  @return bool
	 */
	public function isEmail($value)
	{
		return preg_match('/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/', trim($value));
	}
}