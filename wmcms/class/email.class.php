<?php
/**
* 邮件发送类
*
* @version        $Id: email.class.php 2016年3月31日 10:00  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
* @uptime 		  2015年12月22日 10:38  weimeng
*
*/
class email{
	private $sendType = 1;
	private $smtp;
	private $port;
	private $name;
	private $psw;
	private $send;
	
	function __construct($data = array())
	{
		//如果默认的邮件服务器参数不为空就设置为当前传入的邮件服务器
		if( !empty($data) )
		{
			$this->sendType = $data['email_type'];
			$this->smtp = $data['email_smtp'];
			$this->port = $data['email_port'];
			$this->name = $data['email_name'];
			$this->psw = str::Encrypt( $data['email_psw'] , 'D' , C('config.api.system.api_apikey'));
			$this->send = $data['email_send'];
		}
	}
	
	//发送测试邮件
	function Test()
	{
		$nickName = 'admin';
		$title = '邮件发送测试！';
		$content = '这是一封测试邮件，如果您收到此邮件说明你的邮件配置完全正确可以使用了！';
		return $this->SendEmail($this->name,$nickName , $title , $content);
	}
	
	/**
	 * 发送邮件
	 * @param 参数1，必填，收件人地址
	 * @param 参数2，必填，收件人姓名
	 * @param 参数3，必填，邮件标题
	 * @param 参数4，必填，邮件内容
	 */
	function SendEmail( $email , $nickname , $title , $content){
		require_once("Mailer/class.phpmailer.php"); //下载的文件必须放在该文件所在目录
		$mail = new PHPMailer();
		//通过链接 SMTP 服务器发送邮件
		if( $this->sendType == '1' )
		{
			$mail->IsSMTP();
			$mail->Host =$this->smtp;
			$mail->SMTPDebug = 0; 
			$mail->SMTPAuth = true;
			$mail->Username = $this->name; 
			$mail->Password = $this->psw; 
			$mail->Port=$this->port;
			$mail->From =$this->name;
			$mail->FromName =C('config.web.webname');
			//$mail->SMTPSecure="ssl";
			//新增识别465端口，因为部分服务器发送不了
			if($this->port =='465' )
			{
				$mail->SMTPSecure = 'ssl';
				$mail->Port = 465;
			}
		}
		//通过sendmail 发送邮件
		else if($this->sendType=="2")
		{
			$mail->IsSendmail();
		}
		//通过 PHP 函数 SMTP 发送邮件
		else if($this->sendType=="3")
		{
			$mail->IsMail();
		}
		$mail->CharSet = "UTF-8";
		$mail->Encoding = "base64";
		$mail->AddReplyTo($this->send,C('config.web.webname'));
		$mail->AddAddress($email , $nickname);
		$mail->IsHTML(true);
		$mail->Subject = $title;
		$mail->Body =$content;
		$mail->AltBody ="text/html";
		//$mail->AltBody = "这是一封系统自动发送的注册邮件,请勿回复！";
		try
		{
    		if( $mail->Send() === true )
    		{
    			return array('code'=>200,'msg'=>'发送成功');
    		}
    		else
    		{
    			return array('code'=>500,'msg'=>$mail->ErrorInfo);
		    }
		}
		catch (PDOException $e)
		{
		    return array('code'=>500,'msg'=>$e->getMessage());
        }
	}
}
?>