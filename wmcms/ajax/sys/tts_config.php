<?php
/**
* 获得语音合成的配置信息
*
* @version        $Id: tts_config.php 2022年04月21日 15:28  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$data = array();
$voicetMod = NewModel('system.apittsvoicet');
$where['where']['type_id'] = 11;
$where['where']['voicet_status'] = 1;
$voicetList = $voicetMod->GetAll($where);
if( $voicetList )
{
    foreach($voicetList as $k=>$v)
    {
        //不存在就写入分类id
        if( !isset($data[$v['voicet_api_id']]) )
        {
            $data[$v['voicet_api_id']]['api_name'] = $v['api_name'];
            $data[$v['voicet_api_id']]['api_title'] = $v['api_title'];
            $data[$v['voicet_api_id']]['api_ctitle'] = $v['api_ctitle'];
        }
        $data[$v['voicet_api_id']]['list'][] = array('voicet_ids'=>$v['voicet_ids'],'voicet_name'=>$v['voicet_name']);
    }
    $data = array_values($data);
}
ReturnData('success' , $ajax , 200,$data);
?>