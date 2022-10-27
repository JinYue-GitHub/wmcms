<?php
/**
* 微信公众号平台自动加载接口
*
* @version        $Id: autoload.php 2019年03月09日 19:52  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$id = Get('id/i');
$msgMod = NewModel('operate.weixin_msg');
$replyMod = NewModel('operate.weixin_autoreply');
$accountMod = NewModel('operate.weixin_account');
$fansMod = NewModel('operate.weixin_fans');

$inputData = file_get_contents("php://input");
//检查是否存在公众号
$data = $accountMod->GetById($id);
if( $data )
{
	//获得公众号基本信息
	$wxConfig['appid'] =$data['account_appid'];
	$wxConfig['secret'] =$data['account_secret'];
	$weixinSer = NewClass('weixin_platform',$wxConfig);
	//写入日志
	$weixinSer->SetLog('response','Input:'.$inputData.'，GET:'.json_encode($_GET));
	
	//验证通过
	if( $weixinSer->CheckSignature($data['account_token']) )
	{
		if( isset($_GET["echostr"]) )
		{
			//修改为接入状态
			$accountMod->Update(array('account_access'=>1),$id);
			die($_GET["echostr"]);
		}
		else
		{
			//设置默认回复和关注回复
			$weixinSer->responseMsg['welcome_type'] = 'text';
			$weixinSer->responseMsg['welcome_temp'] = $data['account_welcome_temp'];
			$weixinSer->responseMsg['default_type'] = 'text';
			$weixinSer->responseMsg['default_temp'] = $data['account_default_temp'];
			//查询该公众设置的自动回复
			$replyData = $replyMod->GetDefaultByAid($id);
			if( $replyData )
			{
				foreach ($replyData as $k=>$v)
				{
					if( $v['autoreply_default'] == 1 )
					{
						$weixinSer->responseMsg['welcome_type'] = $v['autoreply_type'];
						$weixinSer->responseMsg['welcome_temp'] = $v['autoreply_temp'];
					}
					else
					{
						$weixinSer->responseMsg['default_type'] = $v['autoreply_type'];
						$weixinSer->responseMsg['default_temp'] = $v['autoreply_temp'];
					}
				}
			}
		
			//处理消息
			$msg = $weixinSer->ResponseMsg();
			$openId = $weixinSer->responseMsg['data']->FromUserName;
			//公众号停止服务返回的消息
			if( $data['account_status'] == '0' )
			{
				$template = str::EnCoding($weixinSer->ResponseGetTemp('text','对不起，该公众号已停止服务，如有疑问请联系管理员QQ：'.C('config.web.qq').'！'));
				$msg = sprintf($template, $openId, $weixinSer->responseMsg['data']->ToUserName, time());
			}
			
			//如果是subscribe 关注事件场景，执行粉丝表关注操作
			if ( isset($weixinSer->responseMsg['data']->Event) && 
				strtolower($weixinSer->responseMsg['data']->Event) == 'subscribe')
			{
				//获得用户信息
				$userInfo = $weixinSer->UserGetInfo($openId);
				//获取成功就记录用户信息
				if( isset($userInfo['openid']) )
				{
					$fansMod->Subscribe($id,$openId,$userInfo);
				}
				else
				{
					//出错就记录
					$weixinSer->SetLog('response','Error:'.$userInfo['errmsg']);
				}
			}
			//取消关注unsubscribe 取消关注事件，执行粉丝表取消关注操作
			else if ( isset($weixinSer->responseMsg['data']->Event) && 
				strtolower($weixinSer->responseMsg['data']->Event) == 'unsubscribe')
			{
				$fansMod->UnSubscribe($id,$openId);
			}

			//记录对话内容
			$msgData['msg_account_id'] = $id;
			$msgData['msg_from'] = $openId;
			$msgData['msg_type'] = trim($weixinSer->responseMsg['data']->MsgType);
			$msgData['msg_content'] = isset($weixinSer->responseMsg['data']->Content)?$weixinSer->responseMsg['data']->Content:'';
			$msgData['msg_picurl'] = isset($weixinSer->responseMsg['data']->PicUrl)?$weixinSer->responseMsg['data']->PicUrl:'';
			$msgData['msg_url'] = isset($weixinSer->responseMsg['data']->Url)?$weixinSer->responseMsg['data']->Url:'';
			$msgData['msg_media_id'] = isset($weixinSer->responseMsg['data']->MediaId)?$weixinSer->responseMsg['data']->MediaId:'';
			$msgData['msg_recognition'] = isset($weixinSer->responseMsg['data']->Recognition)?$weixinSer->responseMsg['data']->Recognition:'';
			$msgData['msg_attr'] = $weixinSer->responseMsg['data']['attr'];
			$msgData['msg_get'] = $inputData;
			$msgData['msg_send'] = $msg;
			$msgData['msg_time'] = time();
			$msgMod->Insert($msgData);
			if( $msg === false )
			{
	    		$weixinSer->SetLog('response','False:'.$inputData);
				die('php://input is empty');
			}
			else
			{
				$weixinSer->SetLog('response','Reply:'.$msg);
				
				//清空缓存区域输出内容
				ob_clean();
				die($msg);
			}
		}
	}
	//签名验证失败
	else
	{
		die(false);
	}
}
?>