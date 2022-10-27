<?php
/**
* 删除文件bom头部类
*
* @version        $Id: delbom.class.php 2016年3月31日 14:06  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2016年1月16日 11:47 weimeng
*
*/
class delbom {

	function checkdir($basedir)
	{
		if($dh = opendir($basedir))
		{	
			while (($file = readdir($dh)) !== false)
			{
				if ($file != '.' && $file != '..')
				{
					if(!is_dir($basedir."/".$file) && $file != '')
					{
						if($file<>str_replace('.html','',$file))
						{
							$this->checkBOM("$basedir/$file");
						}
					}
					else
					{
						$dirname = $basedir."/".$file;
						$this->checkdir($dirname);
					}
				}
			}
			closedir($dh);
		}
	}
	
	
	function checkBOM($filename)
	{
		$auto = 1;
		$contents = file_get_contents($filename);
		$charset[1] = substr($contents, 0, 1);
		$charset[2] = substr($contents, 1, 1);
		$charset[3] = substr($contents, 2, 1);
		if(ord($charset[1]) == 239 && ord($charset[2]) == 187 && ord($charset[3]) == 191) {
			if($auto == 1) {
				$rest = substr($contents, 3);
				$this->rewrite ($filename, $rest);
				return ("<font color=red>找到BOM头,已经清除! 文在位置：$filename</font><br/>");
			} else {
				return ("<font color=red>找到BOM头! 文在位置：$filename</font><br/>");
			}
		}
		else
		{
			return ("该文件无BOM头!文在位置：$filename<br>");
		}
	}
	
	
	function rewrite ($filename, $data){
		$filenum = fopen($filename, "w");
		flock($filenum, LOCK_EX);
		fwrite($filenum, $data);
		fclose($filenum);
	}
}
?>