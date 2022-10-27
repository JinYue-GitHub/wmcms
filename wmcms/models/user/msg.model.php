<?php
/**
* 用户消息模型
*
* @version        $Id: msg.model.php 2016年5月28日 21:48  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*/
class MsgModel
{
	public $table = '@user_msg';
	public $userTable = '@user_user';
	//用户id
	public $userId;
	//消息id
	public $msgId;
	
	
	
	/**
	 * 构造函数
	 */
	function __construct(){}
	
	
	
	/**
	 * 获得查询的条件
	 */
	function GetWhere()
	{
		$where['msg_id'] = $this->msgId;
		$where['msg_tuid'] = $this->userId;
		return $where;
	}
	
	
	/**
	 * 查询一条数据
	 * @param 参数1，必须，条件
	 */
	function GetOne( $id = '' )
	{
		if( $id != '' )
		{
			$this->msgId = $id;
		}
		$where['table'] = $this->table;
		$where['where'] = $this->GetWhere();
		$data = wmsql::GetOne($where);
		return $data;
	}
	
	
	/**
	 * 删除一条数据
	 */
	function DelOne()
	{
		return wmsql::Delete($this->table , $this->GetWhere());
	}
	
	
	/**
	 * 插入消息记录
	 * @param 参数1，必须，收信用户id
	 * @param 参数2，必须，发送的消息
	 * @param 参数2，必须，发信用户id
	 */
	function Insert($uid , $content , $fuid=0)
	{
		$data['msg_fuid'] = $fuid;
		$data['msg_tuid'] = $uid;
		$data['msg_content'] = $content;
		$data['msg_time'] = time();
		return wmsql::Insert($this->table, $data);
	}
	


	/**
	 * 设置消息已读
	 * @param 参数1，必须，收信用户id
	 * @param 参数2，选填，0为全部消息，大于0为消息id
	 */
	function Read($uid,$mid=0)
	{
		$data['msg_status'] = 1;
		$where['msg_status'] = 0;
		$where['msg_tuid'] = $uid;
		if( $mid > 0 )
		{
			$where['msg_id'] = $mid;
		}
		return wmsql::Update($this->table,$data,$where);
	}
	
	
	/**
	 * 清空消息
	 * @param 参数1，必须，收信用户id
	 * @param 参数2，选填，0为全部消息，大于0为消息id
	 */
	function Clear($uid,$mid=0)
	{
		if( $mid > 0 )
		{
			$this->msgId = $mid;
			$this->userId = $uid;
			return $this->DelOne();
		}
		else
		{
			$where['msg_tuid'] = $uid;
			return wmsql::Delete($this->table , $where);
		}
	}
	
	/**
	 * 检查是否有@消息的出现
	 * @param 参数1，必须，检查的内容
	 * @param 参数2，必须，@的校内内容
	 * @param 参数2，必须，是否返回@的用户数组
	 */
	function CheckAt($content,$remark='',$isRs = false)
	{
		preg_match_all('/@(.*?)\s/', $content, $label);
		if( count($label[1]) > 0 )
		{
			$nicknameArr = array();
			$accountArr = array();
			//获得所有的@用户昵称
			foreach ($label[1] as $k=>$v)
			{
				if( 3 <= strlen($v) && strlen($v) <= 30 )
				{
					$nicknameArr[] = $v;
				}
			}
			//去除重复的昵称
			$nicknameArr = array_unique($nicknameArr);
			//根据昵称查询用户是否存在
			$where['table'] = $this->userTable;
			$where['field'] = 'user_id,user_nickname';
			$where['where']['user_nickname'] = array('or',str::ArrToStr($nicknameArr));
			$accountArr = wmsql::GetALL($where);
			//插入新的消息。
			if( $isRs == false )
			{
				//循环得出查询的数据
				if( $accountArr )
				{
					foreach ($accountArr as $k=>$v)
					{
						$data['msg_uid'] = $v['uid'];
						$data['msg_content'] = $ramark;
						$data['msg_time'] = time();
						$dataAll[] = $data;
					}
					//插入全部消息
					wmsql::InsertAll($dataAll);
				}
				return true;
			}
			//直接返回结果
			else
			{
				return $accountArr;
			}
		}
		else
		{
			return false;
		}
	}
	
	/**
	 * 发送消息通知
	 * @param 参数1，必须，内容模块
	 * @param 参数2，必须，内容id
	 * @param 参数3，必须，评论用户id
	 * @param 参数4，必须，评论内容
	 * @param 参数5，选填，通知内容
	 */
	function SendMsg($module,$cid,$uid,$content,$remark='')
	{
		global $tableSer;
		$authorMod = NewModel('author.author');
		$authorMsg = array();
		$authorId = 0;

		//查询内容信息
		$contentData = $tableSer->GetData($module,$cid);
		//根据不同的模块获得内容标题和内容url链接
		switch($module)
		{
			//论坛模块
			case 'bbs':
				$contentTitile = $contentData['bbs_title'];
				$url = tpl::url('bbs_bbs',array('bid'=>$cid,'tid'=>$contentData['type_id']));
				break;
			//文章模块
			case 'article':
				$contentTitile = $contentData['article_name'];
				$url = tpl::url('article_article',array('aid'=>$cid,'tid'=>$contentData['type_id']));
				$authorId = $contentData['article_author_id'];
				break;
			//小说模块
			case 'novel':
				$contentTitile = $contentData['novel_name'];
				$url = tpl::url('novel_info',array('nid'=>$cid,'tid'=>$contentData['type_id']));
				$authorId = $contentData['author_id'];
				break;
			//专题模块
			case 'zt':
				$contentTitile = $contentData['zt_name'];
				$url = tpl::url('zt_zt',array('zid'=>$cid,'tid'=>$contentData['type_id']));
				$authorId = 0;
				break;
			//应用模块
			case 'app':
				$contentTitile = $contentData['app_name'];
				$url = tpl::url('app_app',array('aid'=>$cid,'tid'=>$contentData['type_id']));
				$authorId = 0;
				break;
				
			default:
				$contentTitile = $contentData[$module.'_title'];
				$url = tpl::url($module.'_'.$module,array('id'=>$cid,'tid'=>$contentData['type_id']));
				$authorId = $contentData[$module.'_author_id'];
				break;
		}
		
		//给内容作者发送评论通知
		//根据作者id查询用户id
		if( $authorId > 0 )
		{
			$authorData = $authorMod->GetAuthor($authorId,2);
			if( $authorData )
			{
				$contentData['user_id'] = $authorData['user_id'];
			}
			else
			{
				$contentData['user_id'] = 0;
			}
		}
		else
		{
			$contentData['user_id'] = 0;
		}
		
		//如果内容作者和回帖的人不是一个人就发送通知
		if( $contentData['user_id'] > 0 && $contentData['user_id'] != $uid )
		{
			$msgRemark = $this->RepRemark(C('system.msg.at_replay_'.$module,null,'lang'),$contentTitile,$url);
			$this->Insert($contentData['user_id'],$msgRemark);
		}
		
		//给评论@的用户发送通知
		//获得@的所有用户id和昵称
		$uidList = $this->CheckAt($content,$remark,true);
		if( $uidList )
		{
			if( $remark == '' )
			{
				$remark = C('system.msg.at_'.$module,null,'lang');
				if( $remark == '' )
				{
					return false;
				}
			}
			
			//替换掉通知内容的标签
			$remark = $this->RepRemark($remark,$contentTitile,$url);

			//如果@的用户包含了自己就去除掉
			foreach($uidList as $k=>$v)
			{
				if( $v['user_id'] == $uid )
				{
					unset($uidList[$k]);
				}
			}
			
			//循环数组
			if( $uidList )
			{
				foreach ($uidList as $k=>$v)
				{
					$data['msg_tuid'] = $v['user_id'];
					$data['msg_content'] = $remark;
					$data['msg_time'] = time();
					$dataAll[] = $data;
				}
				//插入全部消息
				wmsql::InsertAll($this->table,$dataAll);
			}
		}
		return true;
	}
	
	/**
	 * 替换消息通知的标签
	 * @param 参数1，必须，通知内容
	 * @param 参数2，必须，内容标题
	 * @param 参数3，必须，链接url
	 */
	private function RepRemark($remark,$title,$url)
	{
		//替换掉通知内容的标签
		$nickname = user::GetNickName();
		if( user::GetUid() == 0 )
		{
			$replayConfig = GetModuleConfig('replay');
			$nickname = $replayConfig['nickname'];
		}
		$remark = str_replace('{用户昵称}',$nickname,$remark);
		$remark = str_replace('{内容标题}',$title,$remark);
		$url = str_replace('{page}', 1, $url);
		$remark = str_replace('{url}',$url,$remark);
		return $remark;
	}
}
?>