<?php
/**
 * OSS行为
 *
 * 
 */
defined('GcWebShop') or exit('Access Invalid!');
use OSS\OssClient;
use OSS\Core\OssException;



class ossLogic {
	public function __construct(){
	
		require_once(BASE_DATA_PATH.DS."api".DS."aliyunoss".DS."Common.php");

	}
	
	/**
	 * 创建一个存储空间
	 * acl 指的是bucket的访问控制权限，有三种，私有读写，公共读私有写，公共读写。
	 * 私有读写就是只有bucket的拥有者或授权用户才有权限操作
	 * 三种权限分别对应 (OssClient::OSS_ACL_TYPE_PRIVATE，OssClient::OSS_ACL_TYPE_PUBLIC_READ, OssClient::OSS_ACL_TYPE_PUBLIC_READ_WRITE)
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 要创建的存储空间名称
	 * @return null
	 */
	
	public function getBucketAcl($ossClient, $bucket){
		$bucket = Common::getBucketName();
		$ossClient = Common::getOssClient();
		try {
			$res = $ossClient->getBucketAcl($bucket);
		} catch (OssException $e) {
			printf(__FUNCTION__ . ": FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		return $res;
	}
	
	/**
	 * 列出用户所有的Bucket
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @return null
	 */
	public function listBuckets(){
		$ossClient = Common::getOssClient();
		$bucketList = null;
		try {
			$bucketListInfo = $ossClient->listBuckets();
		} catch (OssException $e) {
			showMessage(__FUNCTION__ . ": FAILED\n".$e->getMessage() . "\n");
		}
		$bucketList = $bucketListInfo->getBucketList();

		return $bucketList;
	}

	/**
	 * 判断object是否存在
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	function doesObjectExist($bucket, $object){
		$ossClient = Common::getOssClient();
		
		try {
			$exist = $ossClient->doesObjectExist($bucket, $object);
		} catch (OssException $e) {
			printf(__FUNCTION__ . ": FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		return $exist;
	}
	
	
	/**
	 * 创建虚拟目录
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	public function createObjectDir($bucket, $dirname)
	{
		$ossClient = Common::getOssClient();
		
		try {
			$result = $ossClient->createObjectDir($bucket, $dirname);
			return callback(true,'操作成功');
		} catch (OssException $e) {
            return callback(false,__FUNCTION__ . ": FAILED\n".$e->getMessage() . "\n");
		}
		return $result;

	}

/**
 * 把本地变量的内容到文件
 *
 * 简单上传,上传指定变量的内存值作为object的内容
 *
 * @param OssClient $ossClient OssClient实例
 * @param string $bucket 存储空间名称
 * @return null
 */
function putObject($ossClient, $bucket)
{
    $object = "oss-php-sdk-test/upload-test-object-name.txt";
    $content = file_get_contents(__FILE__);
    $options = array();
    try {
        $ossClient->putObject($bucket, $object, $content, $options);
    } catch (OssException $e) {
        printf(__FUNCTION__ . ": FAILED\n");
        printf($e->getMessage() . "\n");
        return;
    }
    print(__FUNCTION__ . ": OK" . "\n");
}


	/**
	 * 上传指定的本地文件内容
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	function uploadFile($bucket, $object, $filePath, $options){
		$ossClient = Common::getOssClient();
		// 图片裁剪
		$options = array(
			OSS_FILE_DOWNLOAD => 'br_201708172043068447.png',
			OSS_PROCESS => "image/crop,x_100,y_100,w_100,h_100", );

		try {
			$result = $ossClient->uploadFile($bucket, $object, $filePath, $options);
			return callback(true,'操作成功');
		} catch (OssException $e) {
            return callback(false,__FUNCTION__ . ": FAILED\n".$e->getMessage() . "\n");
		}
		return $result;
	}

	/**
	 * 列出Bucket内所有目录和文件, 注意如果符合条件的文件数目超过设置的max-keys， 用户需要使用返回的nextMarker作为入参，通过
	 * 循环调用ListObjects得到所有的文件，具体操作见下面的 listAllObjects 示例
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	function listObjects($bucket, $options){
		//$bucket = Common::getBucketName();
		$ossClient = Common::getOssClient();
		//var_dump($bucket);
		//var_dump($ossClient);
		

		try {
			$listObjectInfo = $ossClient->listObjects($bucket, $options);
		} catch (OssException $e) {
			// printf(__FUNCTION__ . ": FAILED\n");
			// printf($e->getMessage() . "\n");
			// return;
		}
		//print(__FUNCTION__ . ": OK" . "\n");
		//$objectList = $listObjectInfo->getObjectList(); // 文件列表
		//$prefixList = $listObjectInfo->getPrefixList(); // 目录列表

		// if (!empty($objectList)) {
			// print("objectList:\n");
			// foreach ($objectList as $objectInfo) {
				// print($objectInfo->getKey() . "\n");
			// }
		// }
		// if (!empty($prefixList)) {
			// print("prefixList: \n");
			// foreach ($prefixList as $prefixInfo) {
				// print($prefixInfo->getPrefix() . "\n");
			// }
		// }
		return $listObjectInfo;

	}

	/**
	 * 列出Bucket内所有目录和文件， 根据返回的nextMarker循环得到所有Objects
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	function listAllObjects($bucket, $options){
		$ossClient = Common::getOssClient();
			try {
				$listObjectInfo = $ossClient->listObjects($bucket, $options);
			} catch (OssException $e) {
				printf(__FUNCTION__ . ": FAILED\n");
				printf($e->getMessage() . "\n");
				return;
			}
			// 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表
			// $nextMarker = $listObjectInfo->getNextMarker();
			// $listObject = $listObjectInfo->getObjectList();
			// $listPrefix = $listObjectInfo->getPrefixList();

		return $listObjectInfo;
	}


	/**
 * 获取object meta, 也就是getObjectMeta接口
 *
 * @param OssClient $ossClient OssClient实例
 * @param string $bucket 存储空间名称
 * @return null
	 */
	function getObjectMeta($bucket, $object)
	{
		$ossClient = Common::getOssClient();
		
		$options = array();

		try {

			$ObjectMeta = $ossClient->getObjectMeta($bucket, $object, $options);
		} catch (OssException $e) {
			printf(__FUNCTION__ . ": FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		return $ObjectMeta;

	}

/**
 * 拷贝object
 * 当目的object和源object完全相同时，表示修改object的meta信息
 *
 * @param OssClient $ossClient OssClient实例
 * @param string $bucket 存储空间名称
 * @return null
 */
function copyObject($ossClient, $bucket)
{
    $fromBucket = $bucket;
    $fromObject = "oss-php-sdk-test/upload-test-object-name.txt";
    $toBucket = $bucket;
    $toObject = $fromObject . '.copy';
    $options = array();

    try {
        $ossClient->copyObject($fromBucket, $fromObject, $toBucket, $toObject, $options);
    } catch (OssException $e) {
        printf(__FUNCTION__ . ": FAILED\n");
        printf($e->getMessage() . "\n");
        return;
    }
    print(__FUNCTION__ . ": OK" . "\n");
}

/**
 * 修改Object Meta
 * 利用copyObject接口的特性：当目的object和源object完全相同时，表示修改object的meta信息
 *
 * @param OssClient $ossClient OssClient实例
 * @param string $bucket 存储空间名称
 * @return null
 */
function modifyMetaForObject($ossClient, $bucket)
{
    $fromBucket = $bucket;
    $fromObject = "oss-php-sdk-test/upload-test-object-name.txt";
    $toBucket = $bucket;
    $toObject = $fromObject;
    $copyOptions = array(
        OssClient::OSS_HEADERS => array(
            'Cache-Control' => 'max-age=60',
            'Content-Disposition' => 'attachment; filename="xxxxxx"',
        ),
    );
    try {
        $ossClient->copyObject($fromBucket, $fromObject, $toBucket, $toObject, $copyOptions);
    } catch (OssException $e) {
        printf(__FUNCTION__ . ": FAILED\n");
        printf($e->getMessage() . "\n");
        return;
    }
    print(__FUNCTION__ . ": OK" . "\n");
}


	/**
	 * 删除object
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	function deleteObject($bucket,$object)
	{
		$ossClient = Common::getOssClient();

		try {
			$delobject = $ossClient->deleteObject($bucket, $object);
		} catch (OssException $e) {
			printf(__FUNCTION__ . ": FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		return $delobject;
	}


	/**
	 * 批量删除object
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	function deleteObjects($ossClient, $bucket)
	{
		$objects = array();
		$objects[] = "oss-php-sdk-test/upload-test-object-name.txt";
		$objects[] = "oss-php-sdk-test/upload-test-object-name.txt.copy";
		try {
			$ossClient->deleteObjects($bucket, $objects);
		} catch (OssException $e) {
			printf(__FUNCTION__ . ": FAILED\n");
			printf($e->getMessage() . "\n");
			return;
		}
		print(__FUNCTION__ . ": OK" . "\n");
}

	
}