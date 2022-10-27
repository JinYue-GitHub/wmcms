<?php
/**
* 发送验证码
*
* @version        $Id: sendcode.php 2022年03月21日 15:38  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

//语音合成接口
$api = str::IsEmpty(Request('api'),$lang['system']['par']['err']);
//语速
$speed = Request('speed');
//发音人
$voicet = str::Int(Request('voicet'));
//tts的sessionid解密
$ttsArr = str::A(Request('tts'));
if( !isset($ttsArr[0]) || empty($ttsArr[0]) )
{
	ReturnData($lang['system']['par']['err'] , $ajax , 500);
}
$tts = $ttsArr[0];
//设置sessionId
SetSessionId($tts);
$ttsData = json_decode(Session('TTS'),true);
$open = C('config.api.'.$api.'.api_open');
//接口开关
if( $open == '0' || empty($open) )
{
	ReturnData($lang['sys']['tts_api_close'] , $ajax , 500);
}
//数据不完整
else if( !is_array($ttsData)|| !isset($ttsData['time']) || !isset($ttsData['module']) || !isset($ttsData['type']) || !isset($ttsData['tid']) || !isset($ttsData['cid']) )
{
	ReturnData($lang['system']['par']['err'] , $ajax , 500);
}
//id过期
else if( time()-$ttsData['time'] > 3000000 )
{
    Session('TTS','delete');
	ReturnData($lang['sys']['tts_time_err'] , $ajax , 500);
}
//正常
else
{
    //小说
    if( $ttsData['module'] == 'novel' )
    {
        $chapterMod = NewModel('novel.chapter');
        $filePath = $chapterMod->GetChapterFileName($ttsData['tid'] , $ttsData['nid'] , $ttsData['cid'],'mp3');
        if( !file_exists($filePath) )
        { 
            $data = $chapterMod->GetById($ttsData['cid']);
            $content = explode('，',str::ClearHtml($data['content']));
        }
    }
    else
    {
        ReturnData($lang['system']['par']['err'] , $ajax , 500);
    }
	//将文件名字增加渠道和播放速度缓存
    $filePath = implode('_'.$api.'_'.$speed.'_'.$voicet.'.mp3',explode('.mp3',$filePath));
    //设置不超时
    set_time_limit(0);
	header("Content-type: audio/mpeg");
    //文件不存在
    if( !file_exists($filePath) )
    {
        ignore_user_abort(true);
        ob_end_clean();
        //写入空文件
        file::CreateFile( $filePath ,'','',false);
        //输出api
        $ai = NewApi('ai.'.$api,null,'Ai');
        $ttsData['speed'] = $speed;
        $ttsData['voicet'] = $voicet;
        $ttsData['text'] = '';
        $curText = '';
        foreach($content as $k=>$v)
        {
            $ttsData['text'] = $curText;
            $ttsData['text'] .= '，'.$v;
            //小于200个字符继续循环，并且不是最后一个
            if( mb_strlen($ttsData['text'],'utf-8') > 120 || $k+1==count($content) )
            {
        		if( $k+1==count($content) )
        		{
        			$curText = $ttsData['text'];
        		}
                $ttsData['text'] = $curText;
                $result = $ai->Synthesis($ttsData);
                if( $result['code']=='200' )
                {
                    //写入文件
                    file::CreateFile( $filePath , $result['data'],'',false);
                    //当场输出
                    echo $result['data'];
                    flush();//刷新输出缓冲 
                }
                else
                {
                    //删除空文件
                    file::DelFile( $filePath );
                    header("Content-Type:application/json;charset=utf-8");
        	        ReturnData($result['msg'] , $ajax , 500);
        	        break;
                }
		        $curText = $v;
            }
            else
            {
                $curText = $ttsData['text'];
            }
        }
    }
    //直接读取文件
    else
    {
        header("Content-transfer-encoding: chunked"); 
        header("Accept-Ranges: bytes"); 
        header('Content-length: '.filesize($filePath));
        header('Cache-Control: max-age=600');
        readfile($filePath);
    }
    die();
}