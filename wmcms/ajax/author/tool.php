<?php
/**
* 作者工具箱
*
* @version        $Id: tool.php 2021年09月03日 16:17  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
if(!defined('WMINC')){ exit("dont alone open!"); }

$type = Request('type');
$info = $lang['system']['operate']['success'];
$filePath = WMFILE.'data/author/';

switch ($type)
{
    //姓名生成工具
    case 'name':
        $code = 200;
        //姓名国家
        $country = Request('country');
        $countryArr = array('cn','en','jp');
        $sexArr = array('1'=>'nan','2'=>'nv');
        if( !in_array($country,$countryArr) )
        {
            $info = $lang['system']['par']['err'];
        }
        else
        {
            //性别/数量
            $sex = str::Int(Request('sex'));
            if( $sex==0 || !isset($sexArr[$sex]) )
            {
                $sex = rand(1,2);
            }
            $number = str::Int(Request('number'),null,16);
            //姓氏前缀和指定长度
            $xing = Request('xing');
            $xingLen = str::Int(Request('xing_len'),null,0);
            //名字前缀和指定长度
            $ming = Request('ming');
            $mingLen = str::Int(Request('ming_len'),null,0);
            //姓名数据
            $nameData = json_decode(file::GetFile($filePath.'name_'.$country.'.txt'),true);
            //姓氏数量和长度
            $xingCount = count($nameData['xing']);
            $xingLen = $xingLen>$xingCount?$xingCount:$xingLen;
            //名字数量和长度
            $mingCount = count($nameData['ming']['nan']);
            $mingLen = $mingLen>$mingCount?$mingCount:$mingLen;
            //如果是女性
            if( $sex == 2 )
            {
                $mingCount = count($nameData['ming']['nv']);
                $mingLen = $mingLen>$mingCount?$mingCount:$mingLen;
            }
            for($i=1;$i<=$number;$i++)
            {
                $xingMing = '';
                //姓氏随机
                if( !empty($xing) )
                {
                    $xingMing = $xing;
                }
                else
                {
                    if( $xingLen == 0)
                    {
                        $lenKey = rand(1,$xingCount);
                    }
                    else
                    {
                        $lenKey = $xingLen;
                    }
                    $xingMing = $nameData['xing'][$lenKey][rand(0,count($nameData['xing'][$lenKey])-1)];
                }
                //名字随机
                if( !empty($ming) )
                {
                    $xingMing .= $ming;
                }
                else
                {
                    if( $mingLen == 0)
                    {
                        $lenKey = rand(1,$mingCount);
                    }
                    else
                    {
                        $lenKey = $mingLen;
                    }
                    $xingMing .= $nameData['ming'][$sexArr[$sex]][$lenKey][rand(0,count($nameData['ming'][$sexArr[$sex]][$lenKey])-1)];
                }
                $data[] = $xingMing;
            }
        }
        break;

    //门派生成工具
    case 'menpai':
        //门派数据
        $code = 200;
        //姓名国家
        $country = Request('country','cn');
        $countryArr = array('cn');
        $number = str::Int(Request('number'),null,24);
        $prefix = Request('prefix');
        $suffix = Request('suffix');

        if( !in_array($country,$countryArr) )
        {
            $info = $lang['system']['par']['err'];
        }
        else
        {
            $mingPaiData = json_decode(file::GetFile($filePath.'menpai_'.$country.'.txt'),true);
            for($i=1;$i<=$number;$i++)
            {
                //前缀随机
                if( !empty($prefix) )
                {
                    $xingMing = $prefix;
                }
                else
                {
                    $lenKey = rand(1,count($mingPaiData['prefix']));
                    $xingMing = $mingPaiData['prefix'][$lenKey][rand(0,count($mingPaiData['prefix'][$lenKey])-1)];
                }
                //后缀随机
                if( !empty($suffix) )
                {
                    $xingMing .= $suffix;
                }
                else
                {
                    $lenKey = rand(1,count($mingPaiData['suffix']));
                    $xingMing .= $mingPaiData['suffix'][$lenKey][rand(0,count($mingPaiData['suffix'][$lenKey])-1)];
                }
                $data[] = $xingMing;
            }
        }
        break;

    default:
        $info = $lang['system']['par']['err'];
        break;
}
ReturnData($info , $ajax , $code , $data);
?>