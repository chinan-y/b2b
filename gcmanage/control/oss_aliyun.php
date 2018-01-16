<?php
defined('GcWebShop') or exit('Access Invalid!');
class oss_aliyunControl extends SystemControl{
    const EXPORT_SIZE = 5000;
    public function __construct() {
        parent::__construct ();
        Language::read('oss');
    }

	
	public function indexOp(){
		$oss = Logic('oss');
		$listBuckets = $oss->listBuckets();
		//var_dump($listBuckets);

		Tpl::output('bucketList',$listBuckets);
		Tpl::showpage('oss.bucket.index');
	}


	/**
	 * 列出Bucket内所有目录和文件， 根据返回的nextMarker循环得到所有Objects
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	public function objectlistOp(){
		$oss = Logic('oss');

		$delimiter = '/';
		$prefix = $_GET['prefix'];
		$maxkeys = 500;
		$nextMarker = '';
		
		$options = array(
			'delimiter' => $delimiter,
			'prefix' => $prefix,
			'max-keys' => $maxkeys,
			'marker' => $nextMarker,
			);
		
		if($_GET['bucketname']){
			$bucket = $_GET['bucketname'];
			$listObjectInfo = $oss->listAllObjects($bucket, $options);

			// 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表
			$nextMarker = $listObjectInfo->getNextMarker();
			$listObject = $listObjectInfo->getObjectList();
			$listPrefix = $listObjectInfo->getPrefixList();
			
			Tpl::output('bucket',$_GET['bucketname']);

		}else{
			$listObjectInfo = $oss->listAllObjects('testqqbsmall', $options);

			// 得到nextMarker，从上一次listObjects读到的最后一个文件的下一个文件开始继续获取文件列表
			$nextMarker = $listObjectInfo->getNextMarker();
			$listObject = $listObjectInfo->getObjectList();
			$listPrefix = $listObjectInfo->getPrefixList();
		
			Tpl::output('bucket','testqqbsmall');
		}
		
		$listBuckets = $oss->listBuckets();
		Tpl::output('bucketList',$listBuckets);
		
		Tpl::output('options',$options);
		Tpl::output('nextMarker',$nextMarker);
		Tpl::output('listObject',$listObject);
		Tpl::output('listPrefix',$listPrefix);
		Tpl::showpage('oss.object.index');
	}
	
		
	/**
	 * 创建虚拟目录
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	public function addobjdirOp(){
		$oss = Logic('oss');
		$listBuckets = $oss->listBuckets();
		Tpl::output('bucketList',$listBuckets);
		if($_POST['bucketname'] && $_POST['object_name']){
			$bucketname = trim($_POST['bucketname']);
			$objectname = trim($_POST['specdirectory']). trim($_POST['object_name']);
			$objectexist = $oss->doesObjectExist($bucketname, $objectname);
			if($objectexist==false){	
				$adddir = $oss->createObjectDir($bucketname, $objectname);
				if($adddir){
					showMessage('创建成功');
				}else{
					showMessage('创建失败');
				}
			}else{
				showMessage('对象已存在！');
			}			
		}else{
			Tpl::showpage('oss.object.add');
		}
	}
	

	/**
	 * 上传指定的本地文件内容
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	public function uploadobjectfileOp(){
		$oss = Logic('oss');

		$listBuckets = $oss->listBuckets();
		Tpl::output('bucketList',$listBuckets);
		
		$objectname=array();
		$objlist=array();
		for($i=1;$i<=$_POST['num'];$i++){
			$objectname['finame']	 = $_FILES['objectfile'.$i]['name'];
			$objectname['pathname']  = $_FILES['objectfile'.$i]['tmp_name'];
			$objlist[] = $objectname;
		}

		//上传
		if ($_POST['bucketname'] && count($objlist)>0){
			$bucketname = $_POST['bucketname'];
			foreach($objlist as $k => $v){
				if(!empty($_POST['specdirectory'])){
					$objectname = trim($_POST['specdirectory']). $v['finame'];		//文件名称
				}else{
					$objectname = $v['finame'];		//文件名称
				}

				$options = array();
				$uploadfile = $oss->uploadFile($bucketname, $v['finame'], $v['pathname'], $options);
			}
			
			if($uploadfile){
				showMessage('上传文件成功！');
			}else{
				showMessage('上传文件失败！');
			}
		}else{
			Tpl::showpage('oss.object.file.add');
		}
		
	}
	
	/**
	 * 
	 *
	 * 获取object
	 * 将object下载到指定的文件
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	public function downobjectOp(){
		$oss = Logic('oss');
		$bucket = $_GET['bucketname'];
		$object = $_GET['object_name'];
		$localfile = "test.php";

		$objectmeta = $oss->getObjectMeta($bucket, $object);
		// var_dump('-------');
		// var_dump('-------');
		// var_dump('-------');
		// var_dump('-------');
		// var_dump('-------');
		// var_dump('-------');
		// var_dump('-------');
		// var_dump($objectmeta);
		
		Tpl::output('objectmeta',$objectmeta);
		Tpl::showpage('oss.object.show');
	}
	/**
	 * 删除object
	 *
	 * @param OssClient $ossClient OssClient实例
	 * @param string $bucket 存储空间名称
	 * @return null
	 */
	public function delobjdirOp(){
		$oss = Logic('oss');
		if($_GET['bucketname'] && $_GET['object_name']){
			$bucketname = trim($_GET['bucketname']);
			$objectname = trim($_GET['object_name']);
			$delobject = $oss->deleteObject($bucketname, $objectname);
			if($delobject){
				showMessage('删除成功');
				}
			else{
				showMessage('删除失败');
				}
		}
		Tpl::showpage('oss.object.index');
	}
	
	
}

