<?php
/***************************************************************************
 *
 * Copyright (c) 2011 Baidu.com, Inc. All Rights Reserved
 *
 **************************************************************************/

require_once( 'BaiduStore.php');
require_once( 'BaiduOAuth2.php');
require_once( 'BaiduApiClient.php');
require_once( 'BaiduUtils.php');

class Baidu
{
    protected $clientId;
    protected $clientSecret;
    protected $redirectUri;
    protected $store = null;
    protected $state = null;
    protected $session = null;
    protected $oauth2 = null;
    
    public function __construct($clientId, $clientSecret, $redirectUri, $store = null)
    {
    	$this->clientId = $clientId;
    	$this->clientSecret = $clientSecret;
    	$this->redirectUri = $redirectUri;
    	$this->setStore($store ? $store : new BaiduCookieStore($clientId));
    }

	public function getBaiduOAuth2Service()
	{
		if (!$this->oauth2) {
			$this->oauth2 = new BaiduOAuth2($this->clientId, $this->clientSecret);
			$this->oauth2->setRedirectUri($this->redirectUri);
		}
		return $this->oauth2;
	}
	
	public function getBaiduApiClientService()
	{
		return new BaiduApiClient($this->clientId, $this->getAccessToken());
	}
	
	public function getAccessToken()
	{
		$session = $this->getSession();
		if (isset($session['access_token'])) {
			return $session['access_token'];
		} else {
			return false;
		}
	}
	public function getRefreshToken()
	{
		$session = $this->getSession();
		if (isset($session['refresh_token'])) {
			return $session['refresh_token'];
		} else {
			return false;
		}
	}
	public function getLoggedInUser()
	{
		$user = $this->getUser();
		
		if (isset($_REQUEST['bd_sig']) && isset($_REQUEST['bd_user'])) {
			$params = array('bd_user' => $_REQUEST['bd_user']);
			$sig = BaiduUtils::generateSign($params, $this->clientSecret, 'bd_sig');
			if ($sig != $_REQUEST['bd_sig'] || $user['uid'] != $_REQUEST['bd_user']) {
				$this->store->remove('session');
				return false;
			}
		}
		
		return $user;
	}
	
	public function getLoginUrl($scope = '', $display = 'page')
	{
		$oauth2 = $this->getBaiduOAuth2Service();
		return $oauth2->getAuthorizeUrl('code', $scope, $this->state, $display);
	}
	
	public function getLogoutUrl($next)
	{
		$oauth2 = $this->getBaiduOAuth2Service();
		return $oauth2->getLogoutUrl($this->getAccessToken(), $next);
	}
	
	public function getSession()
	{
		if ($this->session === null) {
			$this->session = $this->doGetSession();
		}
		
		return $this->session;
	}
	
	public function setSession($session)
	{
		$this->session = $session;
		if ($session) {
			$this->store->set('session', $session);
		} else {
			$this->store->remove('session');
		}
		return $this;
	}
	
	protected function getUser()
	{
		$session = $this->getSession();
		if (is_array($session) && isset($session['uid']) && isset($session['uname'])) {
			return array('uid' => $session['uid'], 'uname' => $session['uname']);
		} else {
			return false;
		}
	}
	
    protected function setStore($store)
    {
    	$this->store = $store;
    	if ($this->store) {
    		$state = $this->store->get('state');
    		if (!empty($state)) {
    			$this->state = $state;
    		}
    		//as the storage engine is changed, we need to get the session again.
    		$this->session = null;
    		$this->getSession();
    		$this->establishCSRFTokenState();
    	}
    	
    	return $this;
    }
	protected function doGetSession()
	{
		$code = $this->getCode();
		if ($code && $code != $this->store->get('code')) {
			$oauth2 = $this->getBaiduOAuth2Service();
			$session = $oauth2->getAccessTokenByAuthorizationCode($code);
			if ($session) {
				$this->store->set('code', $code);
				$this->setSession($session);
				$apiClient = new BaiduApiClient($this->clientId, $session['access_token']);
				$user = $apiClient->api('passport/users/getLoggedInUser');
				if ($user) {
					$session = array_merge($session, $user);
					$this->setSession($session);
				}
				return $session;
			}
			
			$this->store->removeAll();
			return false;
		}
		$session = $this->store->get('session');
		$this->setSession($session);
		if ($session && !isset($session['uid'])) {
			$apiClient = new BaiduApiClient($this->clientId, $session['access_token']);
			$user = $apiClient->api('passport/users/getLoggedInUser');
			if ($user) {
				$session = array_merge($session, $user);
				$this->setSession($session);
			}
		}
		
		return $session;
	}

	protected function getCode()
	{
		if (isset($_GET['code'])) {
			if ($this->state && $this->state === $_GET['state']) {
				// CSRF state has done its job, so clear it
				$this->state = null;
				$this->store->remove('state');
				return $_GET['code'];
			} else {
				BaiduUtils::errorLog('CSRF state token does not match one provided.');
				return false;
			}
		}
		
		return false;
	}

	protected function establishCSRFTokenState()
	{
		if ($this->state === null) {
			$this->state = md5(uniqid(mt_rand(), true));
			$this->store->set('state', $this->state);
		}
	}
}


//wmcms整合登录类
class OtherLogin{
	private $baidu;
	private $apikey;
	private $secret;
	private $backurl;
	function __construct($data=array())
	{
		$this->apikey = $data['apikey'];
		$this->secret = $data['secret'];
		$this->backurl = $data['backurl'];
		$this->baidu = new Baidu($this->apikey, $this->secret, $this->backurl, new BaiduCookieStore($this->apikey));
	}

	//获得跳转登录的url
	function GetJumpUrl()
	{
		//获得登录地址并且跳转
		$loginurl = $this->baidu->getLoginUrl('', 'popup');
		return $loginurl;
	}
	
	//获得用户的信息
	function GetUserInfo($token='',$openid='')
	{
		$user = $this->baidu->getLoggedInUser();
		
		$data['type'] = '百度';
		$data['openid'] = $user['uid'];
		$data['nickname'] = $user['uname'];
		$data['unionid'] = isset($user['unionid'])?$user['unionid']:'';
		return $data;
	}
}
