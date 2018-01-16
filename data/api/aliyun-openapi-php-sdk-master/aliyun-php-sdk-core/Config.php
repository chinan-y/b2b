<?php
/*
 * Licensed to the Apache Software Foundation (ASF) under one
 * or more contributor license agreements.  See the NOTICE file
 * distributed with this work for additional information
 * regarding copyright ownership.  The ASF licenses this file
 * to you under the Apache License, Version 2.0 (the
 * "License"); you may not use this file except in compliance
 * with the License.  You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing,
 * software distributed under the License is distributed on an
 * "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY
 * KIND, either express or implied.  See the License for the
 * specific language governing permissions and limitations
 * under the License.
 */
include_once 'Autoloader/Autoloader.php';


//config sdk auto load path.
Autoloader::addAutoloadPath("aliyun-php-sdk-core");
// Autoloader::addAutoloadPath("aliyun-php-sdk-batchcompute");
// Autoloader::addAutoloadPath("aliyun-php-sdk-sts");
// Autoloader::addAutoloadPath("aliyun-php-sdk-push");
// Autoloader::addAutoloadPath("aliyun-php-sdk-ram");
// Autoloader::addAutoloadPath("aliyun-php-sdk-ubsms");
// Autoloader::addAutoloadPath("aliyun-php-sdk-ubsms-inner");
// Autoloader::addAutoloadPath("aliyun-php-sdk-green");
// Autoloader::addAutoloadPath("aliyun-php-sdk-dm");
// Autoloader::addAutoloadPath("aliyun-php-sdk-iot");

//config http proxy	
define('ENABLE_HTTP_PROXY', FALSE);
define('HTTP_PROXY_IP', '127.0.0.1');
define('HTTP_PROXY_PORT', '8888');


Autoloader::addAutoloadPath("aliyun-php-sdk-cdn");
include_once 'Regions/EndpointConfig.php';
class liveAPI{
	private $accessKeyId="Nl6fDInvCKsnVFxS";
	private $accessSecret="evwsRR2laIQbsE549xxLsA8vPYOrIi";
	private $client;
	public function __construct(){
		Autoloader::autoload("defaultprofile");
		Autoloader::autoload("Cdn\Request\V20141111\DescribeLiveStreamsOnlineListRequest");
		Autoloader::autoload("Cdn\Request\V20141111\DescribeLiveStreamOnlineUserNumRequest");
		Autoloader::autoload("Cdn\Request\V20141111\ForbidLiveStreamRequest");
		Autoloader::autoload("Cdn\Request\V20141111\ResumeLiveStreamRequest");
		Autoloader::autoload("Cdn\Request\V20141111\CreateLiveStreamRecordIndexFilesRequest");
		Autoloader::autoload("Cdn\Request\V20141111\DescribeLiveStreamRecordIndexFilesRequest");
		$iClientProfile = DefaultProfile::getProfile("cn-hangzhou", $this->accessKeyId, $this->accessSecret);
		$this->client = new DefaultAcsClient($iClientProfile);
		
	}
	
	public function send($appname=null,$DomainName){
		$data=time();
		$request = new Cdn\Request\V20141111\DescribeLiveStreamsOnlineListRequest();
		$request ->setDomainName($DomainName);
		if($appname!=null){
		   $request->setAppName($appname);
		}
		$response = $this->client->getAcsResponse($request);
		return $response;
		
		
	}
	public function addLive($appname,$DomainName){
		
		$request = new Cdn\Request\V20141111\AddLiveAppRecordConfigRequest();
		$request ->setDomainName($DomainName);
		$request ->setAppName($appname);
		$request->setOssEndpoint('oss-cn-hangzhou.aliyuncs.com');
		$request->setOssBucket('hd01invideo');
		$request->setOssObjectPrefix('record/guangcaiquanqiu/'.$appname.'/{StreamName}/{UnixTimestamp}_{Sequence}');
		$response = $this->client->getAcsResponse($request);
		return $response;
		
		
	}
	
	public function selectOnLineNum($DomainName){
		$request = new Cdn\Request\V20141111\DescribeLiveStreamOnlineUserNumRequest();
		$request ->setDomainName($DomainName);
		// $request ->setAppName($appname);
		// $request ->setStreamName($streamName);
		$response = $this->client->getAcsResponse($request);
		return $response;
		
	}
	public function close_live_room($DomainName,$appname,$streamName){
		$request = new Cdn\Request\V20141111\ForbidLiveStreamRequest();
		// $request ->setOwnerId($DomainName);
		// $request ->setSecurityToken($DomainName);
		$request ->setDomainName($DomainName);
		// $request ->setResumeTime($DomainName);
		$request ->setAppName($appname);
		$request ->setStreamName($streamName);
		$request ->setLiveStreamType('publisher');
		$response = $this->client->getAcsResponse($request);
		return $response;
	}
	public function open_live_room($DomainName,$appname,$streamName){
		$request = new Cdn\Request\V20141111\ResumeLiveStreamRequest();
		// $request ->setOwnerId($DomainName);
		// $request ->setSecurityToken($DomainName);
		$request ->setDomainName($DomainName);
		// $request ->setResumeTime($DomainName);
		$request ->setAppName($appname);
		$request ->setStreamName($streamName);
		$request ->setLiveStreamType('publisher');
		$response = $this->client->getAcsResponse($request);
		return $response;
	}
	public function createLiveRecord($DomainName,$appname,$StreamName,$StartTime,$EndTime){
		$request = new Cdn\Request\V20141111\CreateLiveStreamRecordIndexFilesRequest();
		$request->setDomainName($DomainName);
		$request ->setAppName($appname);
		$request ->setStreamName($StreamName);
		$request ->setStartTime($StartTime);
		$request ->setEndTime($EndTime);
		$request->setOssEndpoint('oss-cn-hangzhou.aliyuncs.com');
		$request->setOssBucket('hd01invideo');
		$request->setOssObject('record/demand/'.$appname.'/'.$StreamName.time().'.m3u8');	
		// header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r('record/demand/'+$appname+'/'+$StreamName+time()+'.m3u8');exit;
		$response = $this->client->getAcsResponse($request);
		
		return $response;
	}
	public function selectLiveRecord($DomainName,$appname,$StreamName,$StartTime,$EndTime){
		$request = new Cdn\Request\V20141111\DescribeLiveStreamRecordIndexFilesRequest();
		$request->setDomainName($DomainName);
		$request ->setAppName($appname);
		$request ->setStreamName($StreamName);
		$request ->setStartTime($StartTime);
		$request ->setEndTime($EndTime);
		header("Content-type: text/html; charset=utf-8");echo '<pre>';print_r($request);exit;
		$response = $this->client->getAcsResponse($request);
		return $response;
	}
	
}