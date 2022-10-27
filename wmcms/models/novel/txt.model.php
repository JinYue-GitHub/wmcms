<?php
/**
* 小说txt导入模型
*
* @version        $Id: txt.model.php 2019年02月20日 19:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class TXTModel
{
	/**
	 * 构造函数
	 */
	function __construct(){}

	
	/**
	 * 分割小说章节内容
	 * @param array.exp_path 文件路径
	 * @param array.exp_type 章节分割模式
	 * @param array.exp_str 分章格式
	 * @param array.exp_title 自定义章回名
	 * @param array.exp_volume 识别分卷
	 * @param array.exp_indent 内容缩进模式
	 */
	function ExpChapter( $expData )
	{
	    #获取文件内容
	    //路径存在且不为空
		if( isset($expData['exp_path']) && !empty($expData['exp_path']) )
		{
			$content = file::GetFile($expData['exp_path']);
		}
		//指定了内容
		else
		{
			$content = $expData['exp_content'];
		}
		//指定自定义章回名字
		if( $expData['exp_type'] == '1' )
		{
			$expData['exp_title'] = '章';
		}
			
		//不识别分卷，开始分割章节
	    if( $expData['exp_volume'] == '' )
	    {
	        $result = array('volume'=>1,'chapter'=>0,'list'=>array());
	        $chapterList = $this->MatchChapter($content,$expData);
	        $result['list'][] = array('volume'=>'正文','chapter'=>$chapterList);
	        $result['chapter'] = count($chapterList);
	    }
	    else
	    {
	        $result = $this->MatchVolume($content,$expData);
	    }
	    return $result;
	}
	
	
	/**
	 * 匹配章节分卷
	 * @param string $content 内容
	 * @param string $expData 分割数据
	 */
	private function MatchVolume($content,$expData)
	{
	    $expTitle = '卷';
	    $result = array('volume'=>0,'chapter'=>0,'list'=>array());
	    
		preg_match_all("/第[0-9零一二两三四五六七八九十百千万]*".$expTitle."\s+[\s\S]*?(?:(?=第[0-9零一二两三四五六七八九十百千万]*".$expTitle."\s+)|$)/i",$content,$matches);
	    //分卷独立模式
	    if( $expData['exp_volume'] == '1' )
	    {
    		//如果匹配到的内容不为空
    		if( !empty($matches[0]) )
    		{
    		    foreach ($matches[0] as $k=>$v)
    		    {
        		    $contentArr = explode("\r\n",$v);
        			$volume = $contentArr[0];
        			$content =  $this->MatchChapter($v,$expData);
        			$result['list'][] = array('volume'=>$volume,'chapter'=>$content);
        			$result['volume'] = $result['volume']+1;
        			$result['chapter'] = $result['chapter']+count($content);
    		    }
    		}
	    }
	    //分卷同行模式
	    else if( $expData['exp_volume'] == '2' )
	    {
    		//如果匹配到的内容不为空
    		if( !empty($matches[0]) )
    		{
    			//以分卷名字为键，分卷数量为值，来进行分卷分组。
    		    $volumeKey = array();
    		    foreach ($matches[0] as $k=>$v)
    		    {
	                preg_match_all("/(第[0-9零一二两三四五六七八九十百千万]*".$expTitle."\s+[\s\S]*?)(第[0-9零一二两三四五六七八九十百千万]*".$expData['exp_title'].".*)+([\s\S]*)/i",$v,$lists);
	                
	                if( !empty($lists[1][0]) && !empty($lists[2][0]) && !empty($lists[3][0]) )
	                {
	                    $volume = trim($lists[1][0]);
		                $format = $this->FormatContent($lists[2][0].$lists[3][0],$expData);
		                if( isset($volumeKey[$volume]) )
		                {
            			    $result['list'][$volumeKey[$volume]]['chapter'][] = $format;
		                }
		                else
		                {
            			    $result['volume'] = $result['volume']+1;
            			    $volumeKey[$volume] = $result['volume'];
            			    $result['list'][$volumeKey[$volume]]['volume'] = $volume;
            			    $result['list'][$volumeKey[$volume]]['chapter'][] = $format;
		                }
            			$result['chapter'] = $result['chapter']+1;
	                }
    		    }
    		}
	    }
		return $result;
	}
	
	/**
	 * 匹配章节内容
	 * @param string $content 内容
	 * @param string $expData 分割数据
	 */
	private function MatchChapter($content,$expData)
	{
	    $expType = $expData['exp_type'];
	    $expStr = $expData['exp_str'];
	    $expTitle = $expData['exp_title'];
	    $expIndent = $expData['exp_indent'];
	    $matchesI = 0;
	    //分割模式为自动和自定义章回名
		if( $expType == 1 || $expType == 3 )
		{
			preg_match_all("/第[0-9零一二两三四五六七八九十百千万]*".$expTitle."\s+[\s\S]*?(?:(?=第[0-9零一二两三四五六七八九十百千万]*".$expTitle."\s+)|$)/i",$content,$matches);
		}
		//自定义分章格式
		else
		{
			preg_match_all("/".$expStr."([\s\S]*?(?:(?=".$expStr.")|$))/i",$content,$matches);
	        $matchesI = 1;
		}
		
		//如果匹配到的内容不为空
		if( !empty($matches[$matchesI]) )
		{
		    foreach ($matches[$matchesI] as $k=>$v)
		    {
    			$result[] = $this->FormatContent($v,$expData);
		    }
		}
		return $result;
	}
	
	/**
	 * 格式化处理内容
	 * @param string $contentObj 内容对象
	 * @param string $expData 分割数据
	 */
	private function FormatContent($contentObj,$expData)
	{
	    $contentArr = explode("\r\n",$contentObj);
	    //小于三行就只判断\r
		if( count($contentArr) <= 3 )
		{
			$contentArr = explode("\r",$contentObj);
		}
	    //小于三行就只判断\r
		if( count($contentArr) < 3 )
		{
			$contentArr = explode("\n",$contentObj);
		}
		$title = $contentArr[0];
		unset($contentArr[0]);
    	//如果第一行是空的就移除
    	if( $contentArr[1] == '' )
    	{
    	    unset($contentArr[1]);
    	}
    	
    	$content = '';
    	foreach($contentArr as $key=>$val)
    	{
    		if( !empty($val) )
    		{
    		    //系统自动缩进||不自动缩进
    		    if( $expData['exp_indent'] == '1' || $expData['exp_indent'] == '2' )
    		    {
    	            //系统自动缩进
    			    if( $expData['exp_indent'] == '1' )
        		    {
    			        $content .= '　　'.$val;
        		    }
    		        //不自动缩进
        		    else if( $expData['exp_indent'] == '2' )
        		    {
    			        $content .= $val;
        		    }
    		    }
    		    //清除原有缩进使用系统缩进||只清除原有缩进
    		    else if( $expData['exp_indent'] == '3' || $expData['exp_indent'] == '4')
    		    {
    	            $val = strtr(trim($val),array("\t"=>''));
    	            $val = ltrim($val,"　");
    	            //清除原有缩进使用系统缩进
    			    if( $expData['exp_indent'] == '3' )
        		    {
    			        $content .= '　　'.$val;
        		    }
        		    //只清除原有缩进
        		    else if( $expData['exp_indent'] == '4' )
        		    {
        			    $content .= $val;
        		    }
    		    }
    		    
    		}
    		if( $key < count($contentArr) )
    		{
    			$content .= "\r\n";
    		}
    	}
    	$title = strtr(trim($title),array("\r\n"=>'',"\r"=>'',"\n"=>''));
    	return array('title'=>$title,'content'=>$content);
	}
}
?>