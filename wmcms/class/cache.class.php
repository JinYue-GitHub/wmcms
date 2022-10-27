<?php
/**
* 缓存类
*
* @version        $Id: cache.class.php 2016年月19日 13:51  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime         2016年11月13日 15:56 weimeng
*
*/
class cache{
	//缓存对象名字。总的和分支功能
	public $cache,$fileCache,$redisCache,$memCache;
	//当前模板的类型
	public $pt;
	private $appid,$apikey;
	
	//文件缓存文件路径
	protected $fileFolder = 'Data/';
	//缓存文件后缀
	protected $fileExt = '.cache';
	//缓存文件名字
	public $fileName;
	//文件缓存的分割符
	protected $fileExp = '!!wmcms_cache!!';
	
	//缓存是否开启
	public $siteOpen = false;
	//缓存类型
	public $siteType = 'file';
	//缓存主路径
	protected $cachePath;
	//缓存存储位置
	protected $path = 'Cache/';
	//缓存机制
	public $mechanism = 'page';
	//缓存默认时间
	public $cacheTime = '300';

	//sql文件夹名字
	protected $sqlFolder = 'Sql/';
	//sql文件夹后缀
	protected $sqlExt = '.sql';
	//sql缓存类型
	public $sqlType = 'file';
	//sql缓存是否开启
	public $sqlOpen = false;
	//sql缓存时间
	public $sqlCacheTime = '300';

	//删除缓存是否是文件夹
	protected $isFolder = false;
	//sql缓存类型
	public $queueType = 'file';
	//队列缓存是否开启
	public $queueOpen = false;
	//文件队列名字
	protected $queueFolder = 'Queue/';
	//文件队列后缀
	protected $queueExt = '.queue';
	//队列缓存文件名字
	public $queueName;
	//缓存文件队列分类
	protected $QueueExp = "\r\n";

	//redis的ip、端口
	private $redisHost = '127.0.0.1';
	private $redisPort = '6379';

	//memcached的ip和端口
	private $memcachedHost = '127.0.0.1';
	private $memcachedPort = '11211';

	//缓存配置
	private $cacheConfig;
	
	
	/**
	 * 构造函数
	 * @param 缓存的类型，如果不为空则设置缓存类型为当前传入的方式
	 * @author weimeng 2016/6/14 19:23
	 */
	function __construct()
	{
		$this->cacheConfig = $cacheConfig = C('config.web');
		$this->appid = C('config.api.system.api_appid');
		$this->apikey = C('config.api.system.api_apikey');
		
		//缓存保存住路径
		$this->cachePath = WMROOT.$cacheConfig['cache_path'].'/';
		
		//文件缓存和队列缓存文件夹和文件后缀名字
		$this->fileFolder = $cacheConfig['file_folder'];
		$this->fileExt = $cacheConfig['file_ext'];
		$this->queueFolder = $cacheConfig['queue_folder'];
		$this->queueExt = $cacheConfig['queue_ext'];
		$this->sqlFolder = $cacheConfig['sql_folder'];
		$this->sqlExt = $cacheConfig['sql_ext'];
		
		
		//普通缓存信息
		$this->siteType = $cacheConfig['cache_type'];
		$this->mechanism = $cacheConfig['cache_mechanism'];
		$this->path = $cacheConfig['cache_mechanism'].'/'.Cookie('pt').'/';
		
		//队列缓存信息
		$this->queueCacheTime = $cacheConfig['cache_queuetime'];
		$this->queueType = $cacheConfig['cache_queuetype'];
		$this->queuePath = $this->cachePath.$this->queueFolder.$cacheConfig['cache_queuetype'].'/';
		
		//sql缓存信息
		$this->sqlCacheTime = $cacheConfig['cache_sqltime'];
		$this->sqlType = $cacheConfig['cache_sqltype'];
		$this->sqlPath = $this->cachePath.$this->sqlFolder.$this->queueFolder.$cacheConfig['cache_sqltype'].'/';
		
		//缓存服务器
		$this->redisHost = $cacheConfig['redis_host'];
		$this->redisPort = $cacheConfig['redis_port'];
		$this->memcachedHost = $cacheConfig['memcached_host'];
		$this->memcachedPort = $cacheConfig['memcached_port'];


		//开启缓存
		if( $cacheConfig['cache_open'] == '1' )
		{
			$this->siteOpen = true;
		}
		//没有开启sql缓存
		if( $cacheConfig['cache_sql'] == '1' )
		{
			$this->sqlOpen = true;
		}
		//没有开启队列缓存
		if( $cacheConfig['cache_queue'] == '1' && $this->siteOpen == true )
		{
			$this->queueOpen = true;
		}
		
		//redis缓存
		if( $this->siteOpen && $this->siteType == 'redis' || $this->queueOpen && $this->queueType == 'redis'  || $this->sqlOpen && $this->sqlType == 'redis' )
		{
			if( class_exists('redis') )
			{
				$this->redisCache = new Redis();
				$this->redisCache->connect($this->redisHost,$this->redisPort);
				//选择数据库
				//$this->redisCache->select(0);
			}
			else
			{
				return false;
			}
		}
		//memCache缓存
		else if( $this->siteOpen && $this->siteType == 'memcached' || $this->queueOpen && $this->queueType == 'memcached'  || $this->sqlOpen && $this->sqlType == 'memcached' )
		{
			if( class_exists('memcache') )
			{
				$this->memCache = new Memcache();
				$this->memCache->connect($this->memcachedHost,$this->memcachedPort);
			}
			else
			{
				return false;
			}
		}
		//设置文件缓存的位置
		else if( $this->siteOpen && $this->siteType == 'file' || $this->queueOpen && $this->queueType == 'file'  || $this->sqlOpen && $this->sqlType == 'file' )
		{
			$this->fileFolder = $this->cachePath.$this->fileFolder.'/'.$this->path;
			$this->queueFolder = $this->cachePath.$this->queueFolder.'/';
			$this->sqlFolder = $this->cachePath.$this->sqlFolder.'/';
			$this->fileCache = $this;
		}
	}
	
	/**
	 * 获得缓存对象
	 * @param 参数1，必须，缓存类型
	 */
	function CacheSer($type = 'site')
	{
		$openName = $type.'Open';
		$typeName = $type.'Type';
		if( $this->$openName)
		{
			switch($this->$typeName){
				case 'file':
					$this->cache = $this->fileCache;
					return $this->fileCache;
					break;
					
				case 'redis':
					$this->cache = $this->redisCache;
					return $this->redisCache;
					break;
					
				case 'memcached':
					$this->cache = $this->memCache;
					return $this->memCache;
					break;
			}
		}
		else
		{
			return false;
		}
	}
	
	
	/**
	 * 重新写入当前页面的缓存时间
	 * @param 参数1，必须，页面表示ID
	 */
	function SetCacheTime($pageType)
	{
		//全站首页
		if( $pageType == 'index' && GetKey($this->cacheConfig,'cache_index') != '0' )
		{
			$this->cacheTime = $this->cacheConfig['cache_index'];
		}
		//模块首页
		else
		{
			$pageArr = explode('_' , $pageType);
			$module = GetKey($pageArr, '0');
			$page = GetKey($pageArr, '1');
			if( $module == 'user' )
			{
				$this->siteOpen = false;
				return false;
			}
			if( $page != '' )
			{
				$cacheTime = intval(GetKey($this->cacheConfig, 'cache_module_'.$page));
				if($cacheTime > 0 )
				{
					$this->cacheTime = $cacheTime;
				}
				//模块内容页
				else if(($page == $module || $page == 'read' ) && GetKey($this->cacheConfig,'cache_module_content') > 0)
				{
					$this->cacheTime = $this->cacheConfig['cache_module_content'];
				}
			}
		}
	}
	

	/**
	 * 获得缓存的文件路径以及名字
	 * @param 字符串 ，缓存文件名字
	 * @param 字符串，缓存类型，
	 * @param 选填，是否创建新的文件夹
	 * @author weimeng 2016/6/14 19:33
	 */
	private function GetFileName($cacheName = '' , $type = 'site' , $isCreate = false)
	{
		//创建文件夹
		if( $type == 'site' )
		{
			$filePath = $this->fileFolder.$cacheName.$this->fileExt;
			$folderPath = $this->fileFolder.$cacheName;
		}
		//sql缓存
		else if( $type == 'sql' )
		{
			$filePath = $this->sqlFolder.$cacheName.$this->sqlExt;
			$folderPath = $this->sqlFolder.$cacheName;
		}
		//队列名字
		else if( $type == 'queue')
		{
			$filePath = $this->queueFolder.$cacheName.$this->queueExt;
			$folderPath = $this->queueFolder.$cacheName;
		}
		
		
		//如果要创建才执行
		if( $isCreate == true )
		{
			$this->CreateFolder($filePath , 1);
		}
		
		//如果指定文件夹则直接返回
		if( $this->isFolder )
		{
			return $folderPath;
		}
		else
		{
			return $filePath;
		}
	}
	
	
	/**
	 * 循环创建文件夹
	 * @param 文件夹路径
	 * @param 路径是否包含了文件名字
	 * @author weimeng 2016/6/15 15:41
	 */
	function CreateFolder($floderpatch , $isfile='0')
	{
		if($isfile == '1')
		{
			$arr = explode('/',$floderpatch);
			$filename = $arr[count($arr)-1];
			$floderpatch = str_replace($arr[count($arr)-1],'',$floderpatch);
			//提取出最后的文件夹位置，调用删除文件夹
			$this->CreateFolder($floderpatch);
		}
		else
		{
			if(!file_exists($floderpatch))
			{
				mkdir($floderpatch, 0777, true);
			}
		}
	}
	

	/**
	 * 设置缓存
	 * @param 参数1，必须，缓存类型
	 * @param 参数2，必须，缓存的名字
	 * @param 参数3，必须，缓存的值
	 * @param 参数4，选填，缓存的时间，单位秒。默认为持久缓存
	 * @author weimeng 2016/6/14 19:43
	 */
	function Set($type , $name , $data = '' , $time = '0')
	{
		//对数据进行序列化存储
		$data = serialize($data);
		$typeStr = $type.'Type';
		
		if( $this->CacheSer($type) === false )
		{
			return false;
		}
		switch ($this->$typeStr)
		{
			//文件缓存
			case "file":
				$content = time().$this->fileExp.$time.$this->fileExp.$data;
				file_put_contents($this->GetFileName($name, $type ,true), $content);
				break;
				
			//如果是redis缓存
			case "redis":
				if( !class_exists('redis') )
				{
					return false;
				}
				else
				{
					$this->cache->set($name,$data,$time); 
				}
				break;
				
			//如果是memcached缓存
			case "memcached":
				if( !class_exists('memcached') )
				{
					return false;
				}
				else
				{
					$this->cache->set($name,$data,false,$time);
				}
				break;
		}
	}
	
	
	/**
	 * 获得缓存的内容
	 * @param 参数1，必须，缓存类型
	 * @param 参数2，必须，缓存的名字
	 * @author weimeng 2016/6/14 19:43
	 */
	function Get($type , $name = '')
	{
		$data = '';
		$typeStr = $type.'Type';
		if( $this->CacheSer($type) === false )
		{
			return false;
		}
		
		switch ($this->$typeStr)
		{
			//文件缓存
			case "file":
				$content = '';
				if( file_exists($this->GetFileName($name,$type)) )
				{
					$content = file_get_contents($this->GetFileName($name,$type));
				}
				//如果存在值
				if( $content > 1 )
				{
					$data = explode($this->fileExp, $content);
					//如果设置的保存时间大于0，并且超过了缓存时间就删除缓存
					if( $data[1] > 0 && time()-$data[0] > $data[1] )
					{
						unlink($this->GetFileName($name,$type));
					}
					$data = $data[2];
				}
				break;

			//如果是redis缓存
			case "redis":
				if( !class_exists('redis') || !$this->cache->exists($name) )
				{
					return false;
				}
				else 
				{
					$data = $this->cache->get($name);
				}
				break;
				
			//如果是memcached缓存
			case "memcached":
				if( !class_exists('memcached') )
				{
					return false;
				}
				else
				{
					$data = $this->cache->get($name);
				}
				break;
		}

		//存在数据就返回数据
		return unserialize($data);
	}
	
	
	/**
	 * 删除缓存
	 * @param 参数1，缓存类型
	 * @param 参数2，缓存的名字
	 * @param 参数3，是否是文件夹
	 * @author weimeng 2016/6/14 19:50
	 */
	function Del($type , $name = '' , $isFolder = false)
	{
		$this->isFolder = $isFolder;
		$typeStr = $type.'Type';
		if( $this->CacheSer($type) === false )
		{
			return false;
		}
		
		switch ($this->$typeStr)
		{
			//文件缓存
			case "file":
				//如果指定删除文件夹
				if( $isFolder )
				{
					file::DelDir($this->GetFileName($name,$type));
					$this->isFolder = false;
				}
				else
				{
					unlink($this->GetFileName($name,$type));
				}
				break;

			//如果是redis缓存
			case "redis":
				$this->cache->del($name);
				break;
				
			//如果是memcached缓存
			case "memcached":
				$this->cache->delete($name);
				break;
		}
	}
	

	//设置普通缓存
	function SetSite($name = '' , $data = '' , $time = '0' )
	{
		return $this->Set('site' , $name, $data, $time);
	}
	//获取普通缓存
	function GetSite($name)
	{
		return $this->Get('site' , $name);
	}
	//删除普通缓存
	function DelSite($name = '' , $isFolder = false)
	{
		return $this->Del('site' , $name,$isFolder);
	}
	
	//设置sql缓存
	function SetSql($name = '' , $data = '' , $time = '0' )
	{
		return $this->Set('sql' , $name, $data, $time);
	}
	//获取sql缓存
	function GetSql($name)
	{
		return $this->Get('sql' , $name);
	}
	//删除sql缓存
	function DelSql($name = '' , $isFolder = false)
	{
		return $this->Del('sql' , $name,$isFolder);
	}
	
	
	/**
	 * 入队列
	 * @param 队列名字
	 * @param 入队的值
	 * @author weimeng 2016/8/23 9:40
	 */
	function InQueue( $name , $val )
	{
		if( $this->CacheSer('queue') === false )
		{
			return false;
		}
		//对数据进行序列化存储
		$val = serialize($val);
		switch ($this->queueType)
		{
			//文件队列
			case "file":
				$queueArr = explode("\r\n",@file_get_contents($this->GetFileName($name,'queue',true)));
				//删除最后一个空的值。
				array_pop($queueArr);
				//如果不存在队列。就加入队列
				if( !in_array($val, $queueArr) )
				{
					file_put_contents($this->GetFileName($name,'queue'), $val.$this->QueueExp,FILE_APPEND);
				}
				break;
	
			//redis队列
			case "redis":
				$this->cache->rPushx($name , $val);
				break;
	
			//如果是memcached缓存
			case "memcached":
				die('没有memcached队列操作！');
				break;
		}
	}
	
	/**
	 * 出队列
	 * @param 队列名字
	 * @param 出队列的数量，默认为一条
	 */
	function OutQueue($name , $number = 1)
	{
		if( $this->CacheSer('queue') === false )
		{
			return false;
		}
		switch ($this->queueType)
		{
			//文件队列
			case "file":
				$queueArr = explode("\r\n",file_get_contents($this->GetFileName($name,'queue')));
				array_pop($queueArr);
				$i = 1;
				foreach ($queueArr as $k=>$v)
				{
					if( $i <= $number )
					{
						//加入新的数组并且移出队列
						$data[] = unserialize(array_shift($queueArr));
					}
					else
					{
						break;
					}
					$i++;
				}
				//重新写入队列
				file_put_contents($this->GetFileName($name,'queue'), implode("\r\n", $queueArr).$this->QueueExp);
				break;
	
			//redis队列
			case "redis":
				for( $i=1 ; $i <= $number ; $i++ )
				{
					$data[] = unserialize($cache->cache->lpop($name));
				}
				break;
	
				//如果是memcached缓存
			case "memcached":
				die('没有memcached队列操作');
				break;
		}

		return $data;
	}
}
?>