DROP TABLE IF EXISTS `wm_about_about`;

CREATE TABLE `wm_about_about` (
  `about_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(11) NOT NULL DEFAULT '1' COMMENT '信息分类id',
  `about_name` varchar(50) NOT NULL COMMENT '文章标题',
  `about_pinyin` varchar(50) DEFAULT NULL COMMENT '信息拼音',
  `about_content` text COMMENT '内容',
  `about_order` int(1) DEFAULT '0' COMMENT '信息的排序',
  `about_title` varchar(120) DEFAULT NULL COMMENT '信息内容标题',
  `about_key` varchar(120) DEFAULT NULL COMMENT '信息内容关键字',
  `about_desc` varchar(120) DEFAULT NULL COMMENT '信息内容描述',
  `about_time` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间年月日时分秒',
  `about_ctempid` int(4) DEFAULT '0' COMMENT '使用的模版',
  PRIMARY KEY (`about_id`),
  KEY `tid_index` (`type_id`,`about_time`),
  KEY `ctempid_index` (`about_ctempid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='信息内容表';

/*Data for the table `wm_about_about` */

/*Table structure for table `wm_about_type` */

DROP TABLE IF EXISTS `wm_about_type`;

CREATE TABLE `wm_about_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_topid` int(4) DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(50) DEFAULT NULL COMMENT '父级id',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_cname` varchar(10) DEFAULT NULL COMMENT '类型简称',
  `type_pinyin` varchar(50) DEFAULT NULL COMMENT '类型拼音',
  `type_order` int(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `type_info` varchar(200) DEFAULT NULL COMMENT '分类描述',
  `type_tempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类列表页模版id',
  `type_ctempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类内容页模版id',
  `type_title` varchar(120) DEFAULT NULL COMMENT '分类页标题',
  `type_key` varchar(120) DEFAULT NULL COMMENT '分类页关键字',
  `type_desc` varchar(120) DEFAULT NULL COMMENT '分类页描述',
  PRIMARY KEY (`type_id`),
  KEY `topid_index` (`type_topid`),
  KEY `pid_index` (`type_pid`),
  KEY `order_index` (`type_order`),
  KEY `tempid_index` (`type_tempid`),
  KEY `ctempid_index` (`type_ctempid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='信息分类表';

/*Data for the table `wm_about_type` */

/*Table structure for table `wm_ad_ad` */

DROP TABLE IF EXISTS `wm_ad_ad`;

CREATE TABLE `wm_ad_ad` (
  `ad_id` int(4) NOT NULL AUTO_INCREMENT,
  `ad_type_id` int(4) DEFAULT '0' COMMENT '广告分类id',
  `ad_pt` tinyint(1) DEFAULT '4' COMMENT '广告属于哪个平台，1为简版，2为彩版，3为触屏，4为电脑',
  `ad_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0为不显示，1为正常',
  `ad_type` int(1) NOT NULL DEFAULT '1' COMMENT '1为文字,2为图文,3为js',
  `ad_name` varchar(20) DEFAULT NULL COMMENT '广告位的名字',
  `ad_title` varchar(100) DEFAULT NULL COMMENT '广告的标题',
  `ad_url` varchar(200) DEFAULT NULL COMMENT '广告的连接',
  `ad_img` varchar(250) DEFAULT NULL COMMENT '图片广告的地址',
  `ad_img_width` int(1) DEFAULT '0' COMMENT '图片的宽度',
  `ad_img_height` int(1) DEFAULT '0' COMMENT '图片的高度',
  `ad_price` decimal(4,2) DEFAULT '0.00' COMMENT '广告的单价',
  `ad_js` varchar(2000) DEFAULT NULL COMMENT 'js广告代码',
  `ad_time_type` tinyint(4) DEFAULT NULL COMMENT '0为不限制时间，1为限时投放',
  `ad_start_time` int(11) DEFAULT '0' COMMENT '广告开始时间',
  `ad_end_time` int(11) DEFAULT '0' COMMENT '广告结束时间',
  `ad_time` int(4) NOT NULL DEFAULT '0' COMMENT '广告添加的时间',
  PRIMARY KEY (`ad_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告表';

/*Data for the table `wm_ad_ad` */

/*Table structure for table `wm_ad_type` */

DROP TABLE IF EXISTS `wm_ad_type`;

CREATE TABLE `wm_ad_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_name` varchar(20) NOT NULL COMMENT '广告分类名字',
  `type_info` varchar(1000) DEFAULT NULL COMMENT '广告分类描述',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='广告分类表';

/*Data for the table `wm_ad_type` */

/*Table structure for table `wm_api_api` */

DROP TABLE IF EXISTS `wm_api_api`;

CREATE TABLE `wm_api_api` (
  `api_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_id` int(4) NOT NULL COMMENT '接口类型',
  `api_open` tinyint(1) DEFAULT '1' COMMENT '接口是否开启，1为开启，0为关闭',
  `api_title` varchar(20) NOT NULL COMMENT '接口名称',
  `api_ctitle` varchar(10) DEFAULT NULL COMMENT '接口简称',
  `api_name` varchar(20) NOT NULL COMMENT '接口标识',
  `api_appid` varchar(120) DEFAULT NULL COMMENT '开发者id',
  `api_apikey` varchar(5000) DEFAULT NULL COMMENT 'apikey',
  `api_secretkey` varchar(5000) DEFAULT NULL COMMENT 'skey',
  `api_base` varchar(500) DEFAULT NULL COMMENT '基本接口配置参数',
  `api_info` varchar(200) DEFAULT NULL COMMENT '接口描述',
  `api_order` int(4) DEFAULT NULL COMMENT '接口排序',
  `api_option` varchar(500) DEFAULT NULL COMMENT '接口的其他参数',
  PRIMARY KEY (`api_id`),
  UNIQUE KEY `cname` (`api_name`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='全站API接口系统表';

/*Data for the table `wm_api_api` */

insert  into `wm_api_api`(`api_id`,`type_id`,`api_open`,`api_title`,`api_ctitle`,`api_name`,`api_appid`,`api_apikey`,`api_secretkey`,`api_base`,`api_info`,`api_order`,`api_option`) values (1,1,1,'站内通用',NULL,'system','0000','0000','0000','a:3:{s:9:\"api_appid\";a:2:{s:4:\"mast\";i:1;s:6:\"remark\";s:20:\"请输入您的appid\";}s:10:\"api_apikey\";a:2:{s:4:\"mast\";i:1;s:6:\"remark\";s:21:\"请输入您的apikey\";}s:13:\"api_secretkey\";a:2:{s:4:\"mast\";i:1;s:6:\"remark\";s:24:\"请输入您的secretkey\";}}','<span style=\"color:red\">用于站内互动的接口。如果不填写部分功能将无法使用，请勿进行任何修改！</span>',1,''),(2,2,1,'QQ登录',NULL,'qqlogin','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:6:\"APP ID\";s:6:\"remark\";s:27:\"请输入您的应用APP ID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:7:\"APP Key\";s:6:\"remark\";s:28:\"请输入您的应用APP Key\";}s:13:\"api_secretkey\";a:1:{s:4:\"mast\";i:0;}}','用于QQ登录网站的接口，需要去腾讯开放平台申请。回调地址：域名+/wmcms/notify/apilogin.php',1,''),(3,3,1,'支付宝PC支付','支付宝','alipay','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:8:\"应用id\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:15:\"支付宝私匙\";s:6:\"remark\";s:15:\"支付宝私匙\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:15:\"支付宝公匙\";s:6:\"remark\";s:15:\"支付宝公匙\";}}','支付宝在线支付接口，需要去支付宝开放平台申请。',1,''),(4,2,0,'百度登录',NULL,'bdlogin','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:2:\"ID\";s:6:\"remark\";s:23:\"请输入您的应用ID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:7:\"API Key\";s:6:\"remark\";s:28:\"请输入您的应用API Key\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:10:\"Secret Key\";s:6:\"remark\";s:31:\"请输入您的应用Secret Key\";}}','百度账号登录，需要去百度开放平台申请。回调地址：域名+/wmcms/notify/apilogin.php',3,''),(5,2,0,'新浪登录',NULL,'weibologin','','','','a:3:{s:9:\"api_appid\";a:1:{s:4:\"mast\";i:0;}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:7:\"App Key\";s:6:\"remark\";s:28:\"请输入您的应用App Key\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:10:\"App Secret\";s:6:\"remark\";s:31:\"请输入您的应用App Secret\";}}','新浪微博账号登录，需要去新浪开放平台申请。',2,''),(6,3,1,'微信扫码支付','微信','wxpay','','','123','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:20:\"请输入您的APPID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:9:\"API密钥\";s:6:\"remark\";s:24:\"请输入您的API密钥\";}s:13:\"api_secretkey\";a:1:{s:4:\"mast\";i:0;}}','微信扫码支付接口，需要去微信商户平台申请。',3,'a:1:{#123}s:5:{#34}mchid{#34};a:3:{#123}s:5:{#34}title{#34};s:9:{#34}商户号{#34};s:5:{#34}value{#34};s:1:{#34}0{#34};s:4:{#34}info{#34};s:17:{#34}微信商户号id{#34};{#125}{#125}'),(7,4,0,'阿里云OSS',NULL,'oss','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:11:\"AccessKeyId\";s:6:\"remark\";s:26:\"从OSS获得的AccessKeyId\";}s:10:\"api_apikey\";a:1:{s:4:\"mast\";i:0;}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:15:\"AccessKeySecret\";s:6:\"remark\";s:30:\"从OSS获得的AccessKeySecret\";}}','阿里云的OSS存储',1,'a:2:{#123}s:6:{#34}bucket{#34};a:3:{#123}s:5:{#34}title{#34};s:9:{#34}bucket名{#34};s:5:{#34}value{#34};s:0:{#34}{#34};s:4:{#34}info{#34};s:0:{#34}{#34};{#125}s:5:{#34}point{#34};a:3:{#123}s:5:{#34}title{#34};s:12:{#34}地理位置{#34};s:5:{#34}value{#34};s:0:{#34}{#34};s:4:{#34}info{#34};s:81:{#34}根据自己的服务器位置设置,默认为http://oss-cn-shenzhen.aliyuncs.com{#34};{#125}{#125}'),(8,4,0,'腾讯云COS',NULL,'cos','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:6:\"APP_ID\";s:6:\"remark\";s:21:\"从COS获得的APP_ID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:7:\"API_KEY\";s:6:\"remark\";s:22:\"从COS获得的API_KEY\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:6:\"SC_KEY\";s:6:\"remark\";s:21:\"从COS获得的SC_KEY\";}}','腾讯云的COS存储',2,'a:2:{#123}s:6:{#34}bucket{#34};a:3:{#123}s:5:{#34}title{#34};s:9:{#34}bucket名{#34};s:5:{#34}value{#34};s:0:{#34}{#34};s:4:{#34}info{#34};s:0:{#34}{#34};{#125}s:5:{#34}point{#34};a:3:{#123}s:5:{#34}title{#34};s:12:{#34}地理位置{#34};s:5:{#34}value{#34};s:0:{#34}{#34};s:4:{#34}info{#34};s:35:{#34}华南 -{#gt}gz；华中-{#gt}sh;华北-{#gt}tj{#34};{#125}{#125}'),(9,4,0,'七牛云',NULL,'qiniu','','','','a:3:{s:9:\"api_appid\";a:1:{s:4:\"mast\";i:0;}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:9:\"accessKey\";s:6:\"remark\";s:42:\"从七牛云对象存储获得的accessKey\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:9:\"secretKey\";s:6:\"remark\";s:42:\"从七牛云对象存储获得的secretKey\";}}','七牛云的存储',3,'a:2:{#123}s:6:{#34}bucket{#34};a:3:{#123}s:5:{#34}title{#34};s:9:{#34}bucket名{#34};s:5:{#34}value{#34};s:0:{#34}{#34};s:4:{#34}info{#34};s:0:{#34}{#34};{#125}s:6:{#34}domain{#34};a:3:{#123}s:5:{#34}title{#34};s:12:{#34}访问域名{#34};s:5:{#34}value{#34};s:0:{#34}{#34};s:4:{#34}info{#34};s:70:{#34}登录您的七牛云后台查看访问域名，必须带上http://！{#34};{#125}{#125}'),(10,4,0,'新浪云',NULL,'scs','','','','a:3:{s:9:\"api_appid\";a:1:{s:4:\"mast\";i:0;}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:9:\"AccessKey\";s:6:\"remark\";s:42:\"从新浪云对象存储获得的AccessKey\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:9:\"SecretKey\";s:6:\"remark\";s:42:\"从新浪云对象存储获得的SecretKey\";}}','新浪云的存储',4,'a:1:{#123}s:6:{#34}bucket{#34};a:3:{#123}s:5:{#34}title{#34};s:9:{#34}bucket名{#34};s:5:{#34}value{#34};s:0:{#34}{#34};s:4:{#34}info{#34};s:0:{#34}{#34};{#125}{#125}'),(11,5,0,'百度链接提交',NULL,'bdurl','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:6:\"域名\";s:6:\"remark\";s:40:\"请输入您的域名，无需加http://\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:5:\"token\";s:6:\"remark\";s:20:\"请输入您的token\";}s:13:\"api_secretkey\";a:1:{s:4:\"mast\";i:0;}}','用于网站原创url提交，appid填写域名，域名无需添加http://。apikey填写token',1,NULL),(12,2,0,'支付宝登录',NULL,'alipaylogin','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:3:\"PID\";s:6:\"remark\";s:17:\"合作身份者id\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:3:\"KEY\";s:6:\"remark\";s:15:\"安全检验码\";}s:13:\"api_secretkey\";a:1:{s:4:\"mast\";i:0;}}','支付宝账号登录，需要去支付宝开放平台申请。',4,''),(13,2,0,'微信扫码登录',NULL,'wxlogin','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:23:\"请输入您的应用ID\";}s:10:\"api_apikey\";a:1:{s:4:\"mast\";i:0;}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:9:\"Appsecret\";s:6:\"remark\";s:30:\"请输入您的应用Appsecret\";}}','微信登录，需要支付300元认证申请资格。',5,''),(14,6,1,'支付宝WAP支付','支付宝','alipay_wap','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:8:\"应用id\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:15:\"支付宝私匙\";s:6:\"remark\";s:15:\"支付宝私匙\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:15:\"支付宝公匙\";s:6:\"remark\";s:15:\"支付宝公匙\";}}','支付宝wap端支付，可以使用pc相同配置，也可以单独使用账户',2,NULL),(15,6,1,'微信公众号支付','微信','wxpay_jsapi','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:20:\"请输入您的APPID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:9:\"API密钥\";s:6:\"remark\";s:24:\"请输入您的API密钥\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";i:1;s:4:\"name\";s:9:\"Appsecret\";s:6:\"remark\";s:24:\"请输入您的Appsecret\";}}','微信公众号内支付，只支持微信浏览器里面支付。可以和扫码支付同配置',4,'a:1:{#123}s:5:{#34}mchid{#34};a:3:{#123}s:5:{#34}title{#34};s:9:{#34}商户号{#34};s:5:{#34}value{#34};s:1:{#34}0{#34};s:4:{#34}info{#34};s:17:{#34}微信商户号id{#34};{#125}{#125}'),(16,2,1,'微信小程序登录','微信','wxapplogin','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:5:\"AppID\";s:6:\"remark\";s:11:\"小程序ID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:0:\"\";s:6:\"remark\";s:0:\"\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:9:\"AppSecret\";s:6:\"remark\";s:15:\"小程序密钥\";}}','微信小程序接口设置',6,''),(17,2,0,'微信公众号登录','微信','wxmplogin','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:9:\"api_appid\";s:6:\"remark\";s:5:\"appID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:10:\"api_apikey\";s:6:\"remark\";s:12:\"应用apikey\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:13:\"api_secretkey\";s:6:\"remark\";s:9:\"appsecret\";}}','微信公众号（服务号）登录，需要支付300元认证申请资格。',7,''),(18,3,0,'PayPal国际支付','PayPal','paypal','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:8:\"ClientId\";s:6:\"remark\";s:9:\"Client ID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:10:\"api_apikey\";s:6:\"remark\";s:12:\"应用apikey\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:6:\"secret\";s:6:\"remark\";s:6:\"secret\";}}','PayPal是倍受全球亿万用户追捧的国际贸易支付工具，即时支付，即时到账，全中文操作界面，能通过中国的本地银行轻松提现，解决外贸收款难题。',5,''),(19,7,1,'支付宝APP支付','支付宝','alipay_app','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:8:\"应用id\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:15:\"支付宝私匙\";s:6:\"remark\";s:15:\"支付宝私匙\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:15:\"支付宝公匙\";s:6:\"remark\";s:15:\"支付宝公匙\";}}','支付宝APP支付',1,''),(20,7,1,'微信APP支付','微信','wxpay_app','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:5:\"APPID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:9:\"API密钥\";s:6:\"remark\";s:9:\"API密钥\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:13:\"api_secretkey\";s:6:\"remark\";s:0:\"\";}}','微信APP支付',2,'a:1:{#123}s:5:{#34}mchid{#34};a:3:{#123}s:5:{#34}title{#34};s:8:{#34}商户ID{#34};s:5:{#34}value{#34};s:10:{#34}1504418791{#34};s:4:{#34}info{#34};s:11:{#34}商户号ID{#34};{#125}{#125}'),(21,8,1,'微信APP登录','微信','wxapp_login','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:5:\"APPID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:10:\"api_apikey\";s:6:\"remark\";s:12:\"应用apikey\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:9:\"APPSecret\";s:6:\"remark\";s:9:\"APPSecret\";}}','微信APP登录，需要去微信开放平台申请应用',0,''),(22,8,1,'手机QQ登录','QQ登录','qqapplogin','','','','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:6:\"APP ID\";s:6:\"remark\";s:6:\"APP ID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:7:\"APP Key\";s:6:\"remark\";s:7:\"APP Key\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:13:\"api_secretkey\";s:6:\"remark\";s:15:\"应用secretkey\";}}','需要到QQ开放平台申请',2,''),(23,9,0,'阿里云消息','阿里云','aliyunsms','','123123','1223123','a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:9:\"api_appid\";s:6:\"remark\";s:11:\"应用appid\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:11:\"accessKeyId\";s:6:\"remark\";s:11:\"accessKeyId\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:15:\"accessKeySecret\";s:6:\"remark\";s:15:\"accessKeySecret\";}}','阿里云消息发送接口，请参阅 https://ak-console.aliyun.com/ 取得您的AK信息',1,''),(24,9,1,'腾讯云消息','腾讯云','tencentsms',NULL,NULL,NULL,'a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:14:\"应用的APPID\";s:6:\"remark\";s:14:\"应用的APPID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:14:\"云APISecretId\";s:6:\"remark\";s:14:\"云APISecretId\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:15:\"云APISecretKey\";s:6:\"remark\";s:15:\"云APISecretKey\";}}','腾讯云消息发送接口，云API密匙查询: https://console.cloud.tencent.com/cam/capi',2,''),(25,10,0,'钉钉通知','钉钉','dingding_hook',NULL,NULL,NULL,'a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:9:\"api_appid\";s:6:\"remark\";s:11:\"应用appid\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:10:\"api_apikey\";s:6:\"remark\";s:12:\"应用apikey\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:13:\"api_secretkey\";s:6:\"remark\";s:15:\"应用secretkey\";}}','钉钉的消息通知',1,'a:1:{s:3:\"url\";a:3:{s:5:\"title\";s:9:\"钉钉URL\";s:5:\"value\";s:0:\"\";s:4:\"info\";s:25:\"钉钉的WEBHOOK通知URL\";}}'),(26,10,0,'企业微信通知','企业微信','weixinwork_hook',NULL,NULL,NULL,'a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:9:\"api_appid\";s:6:\"remark\";s:11:\"应用appid\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:10:\"api_apikey\";s:6:\"remark\";s:12:\"应用apikey\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"0\";s:4:\"name\";s:13:\"api_secretkey\";s:6:\"remark\";s:15:\"应用secretkey\";}}','企业微信的消息通知',2,'a:1:{s:3:\"url\";a:3:{s:5:\"title\";s:15:\"企业微信URL\";s:5:\"value\";s:0:\"\";s:4:\"info\";s:31:\"企业微信的WEBHOOK通知URL\";}}'),(27,11,0,'腾讯云语音合成','腾讯云','tencenttts',NULL,NULL,NULL,'a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:5:\"APPID\";s:6:\"remark\";s:11:\"账号APPID\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:8:\"secretId\";s:6:\"remark\";s:14:\"云APISecretId\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:9:\"secretKey\";s:6:\"remark\";s:14:\"云APISecretId\";}}','腾讯云语音合成，云API密匙查询: https://console.cloud.tencent.com/cam/capi',2,''),(28,11,0,'百度云语音合成','百度云','baidutts',NULL,NULL,NULL,'a:3:{s:9:\"api_appid\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:5:\"AppID\";s:6:\"remark\";s:11:\"应用appid\";}s:10:\"api_apikey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:7:\"API Key\";s:6:\"remark\";s:13:\"应用API Key\";}s:13:\"api_secretkey\";a:3:{s:4:\"mast\";s:1:\"1\";s:4:\"name\";s:10:\"Secret Key\";s:6:\"remark\";s:16:\"应用Secret Key\";}}','百度云语音合成接口，需要在百度云创建应用',4,'');

/*Table structure for table `wm_api_tts_voicet` */

DROP TABLE IF EXISTS `wm_api_tts_voicet`;

CREATE TABLE `wm_api_tts_voicet` (
  `voicet_id` int(11) NOT NULL AUTO_INCREMENT,
  `voicet_api_id` int(11) NOT NULL COMMENT '接口id',
  `voicet_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否使用:0=禁用,1=使用',
  `voicet_ids` varchar(10) NOT NULL COMMENT '发音人ID',
  `voicet_name` varchar(20) NOT NULL COMMENT '发音人名字',
  `voicet_order` int(11) NOT NULL DEFAULT '9' COMMENT '排序,越小越靠前',
  PRIMARY KEY (`voicet_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='语音合成发音人配置表';

/*Data for the table `wm_api_tts_voicet` */

insert  into `wm_api_tts_voicet`(`voicet_id`,`voicet_api_id`,`voicet_status`,`voicet_ids`,`voicet_name`,`voicet_order`) values (1,29,1,'100510000','智逍遥，阅读男声',1),(2,29,1,'101001','智瑜，情感女声',2),(3,29,1,'101002','智聆，通用女声',3),(4,29,1,'101004','智云，通用男声',4),(5,29,1,'101023','智萱，聊天女声',5),(6,29,1,'101024','智皓，聊天男声',6),(7,29,1,'1005','智莉，通用女声',7),(8,29,1,'1018','智靖，情感男声',8),(9,30,1,'5003','度逍遥',1),(10,30,1,'5118','度小鹿',2),(11,30,1,'106','度博文',3),(12,30,1,'5','度小娇',4),(13,30,1,'1','度小宇',6),(14,30,1,'0','度小美',5),(15,30,1,'3','度逍遥',7),(16,30,1,'4','度丫丫',8);

/*Table structure for table `wm_api_type` */

DROP TABLE IF EXISTS `wm_api_type`;

CREATE TABLE `wm_api_type` (
  `type_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `type_title` varchar(20) DEFAULT NULL COMMENT '接口类型名字',
  `type_name` varchar(20) DEFAULT NULL COMMENT '接口类型标识',
  `type_order` int(4) DEFAULT NULL COMMENT '排序',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='api接口类型表';

/*Data for the table `wm_api_type` */

insert  into `wm_api_type`(`type_id`,`type_title`,`type_name`,`type_order`) values (1,'站内接口','system',1),(2,'网页登录接口','login',2),(3,'PC支付接口','pay',3),(4,'存储接口','oss',4),(5,'SEO接口','seo',5),(6,'移动支付接口','pay_wap',3),(7,'APP支付接口','pay_app',3),(8,'APP登录接口','login_app',3),(9,'消息接口','sms',7),(10,'WEBHOOK','webhook',8),(11,'语音合成接口','tts',9);

/*Table structure for table `wm_app_app` */

DROP TABLE IF EXISTS `wm_app_app`;

CREATE TABLE `wm_app_app` (
  `app_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(4) NOT NULL DEFAULT '1' COMMENT '应用分类id',
  `app_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核',
  `app_rec` int(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `app_ico` varchar(120) DEFAULT NULL COMMENT '应用图标',
  `app_simg` varchar(120) DEFAULT NULL COMMENT '引用缩略图',
  `app_pinyin` varchar(50) DEFAULT NULL COMMENT '应用拼音',
  `app_name` varchar(50) NOT NULL COMMENT '应用标题',
  `app_cname` varchar(50) DEFAULT NULL COMMENT '应用简称',
  `app_lid` varchar(100) DEFAULT '0' COMMENT '语言id',
  `app_lid_text` varchar(200) DEFAULT NULL COMMENT '语言文字',
  `app_cid` varchar(100) DEFAULT '0' COMMENT '费用id',
  `app_cid_text` varchar(200) DEFAULT NULL COMMENT '费用文字',
  `app_tocn` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为不是，1为是汉化应用',
  `app_ver` varchar(20) NOT NULL DEFAULT '0' COMMENT '版本',
  `app_size` varchar(20) NOT NULL DEFAULT '0' COMMENT '大小',
  `app_tags` varchar(50) DEFAULT NULL COMMENT '应用标签',
  `app_aid` int(4) DEFAULT '0' COMMENT '开发商',
  `app_oid` int(4) DEFAULT '0' COMMENT '运营公司',
  `app_info` varchar(100) DEFAULT NULL COMMENT '点评，预览',
  `app_content` varchar(10000) DEFAULT NULL COMMENT '简介',
  `app_read` int(4) NOT NULL DEFAULT '0' COMMENT '应用浏览量',
  `app_replay` int(4) NOT NULL DEFAULT '0' COMMENT '应用评论量',
  `app_ding` int(4) NOT NULL DEFAULT '0' COMMENT '应用顶',
  `app_cai` int(4) NOT NULL DEFAULT '0' COMMENT '应用踩',
  `app_start` decimal(2,1) NOT NULL DEFAULT '0.0' COMMENT '星级',
  `app_score` decimal(2,1) NOT NULL DEFAULT '0.0' COMMENT '应用评分',
  `app_paid` varchar(100) NOT NULL DEFAULT '0' COMMENT '运行平台',
  `app_paid_text` varchar(200) DEFAULT NULL COMMENT '运行平台文字',
  `app_osver` varchar(20) NOT NULL DEFAULT '0' COMMENT '系统要求',
  `app_downnum` int(4) NOT NULL DEFAULT '0' COMMENT '应用下载量',
  `app_down1` varchar(120) DEFAULT NULL COMMENT '应用下载地址1',
  `app_down2` varchar(120) DEFAULT NULL COMMENT '应用下载地址2',
  `app_down3` varchar(120) DEFAULT NULL COMMENT '应用下载地址3',
  `app_addtime` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间年月日时分秒',
  PRIMARY KEY (`app_id`),
  KEY `tid` (`type_id`,`app_addtime`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用表';

/*Data for the table `wm_app_app` */

/*Table structure for table `wm_app_attr` */

DROP TABLE IF EXISTS `wm_app_attr`;

CREATE TABLE `wm_app_attr` (
  `attr_id` int(4) NOT NULL AUTO_INCREMENT,
  `attr_type` varchar(10) NOT NULL COMMENT 'c费用，p平台，l语言',
  `attr_name` varchar(20) NOT NULL COMMENT '属性名',
  PRIMARY KEY (`attr_id`)
) ENGINE=MyISAM AUTO_INCREMENT=12 DEFAULT CHARSET=utf8 COMMENT='应用资费系统表';

/*Data for the table `wm_app_attr` */

insert  into `wm_app_attr`(`attr_id`,`attr_type`,`attr_name`) values (5,'c','内购'),(4,'p','安卓'),(6,'p','塞班'),(7,'l','英文'),(8,'p','苹果'),(9,'l','中文'),(10,'c','破解'),(11,'c','免费');

/*Table structure for table `wm_app_firms` */

DROP TABLE IF EXISTS `wm_app_firms`;

CREATE TABLE `wm_app_firms` (
  `firms_id` int(4) NOT NULL AUTO_INCREMENT,
  `firms_type` varchar(10) DEFAULT NULL COMMENT 'a是开发商，o是运营商，s自研自营',
  `firms_name` varchar(30) NOT NULL COMMENT '开发商名字',
  `firms_cname` varchar(10) DEFAULT NULL COMMENT '开发商简称',
  `firms_url` varchar(120) DEFAULT NULL COMMENT '官网',
  `firms_adress` varchar(120) DEFAULT NULL COMMENT '联系地址',
  `firms_phone` varchar(15) DEFAULT NULL COMMENT '开发商电话',
  `firms_email` varchar(20) DEFAULT NULL COMMENT '开发商邮件',
  `firms_content` varchar(5000) DEFAULT NULL COMMENT '开发商描述',
  `firms_addtime` int(4) DEFAULT NULL COMMENT '添加时间',
  PRIMARY KEY (`firms_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='应用开发商表';

/*Data for the table `wm_app_firms` */

/*Table structure for table `wm_app_type` */

DROP TABLE IF EXISTS `wm_app_type`;

CREATE TABLE `wm_app_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_topid` int(4) NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '子栏目id',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_cname` varchar(10) DEFAULT NULL COMMENT '类型简称',
  `type_pinyin` varchar(50) DEFAULT NULL COMMENT '类型拼音',
  `type_order` int(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `type_ico` varchar(200) DEFAULT NULL COMMENT '类型图标',
  `type_info` varchar(200) DEFAULT NULL COMMENT '类型简介',
  `type_tempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类模版',
  `type_ctempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类内容模版',
  `type_title` varchar(80) DEFAULT NULL COMMENT '分类标题',
  `type_key` varchar(100) DEFAULT NULL COMMENT '分类关键字',
  `type_desc` varchar(120) DEFAULT NULL COMMENT '分类描述',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='应用分类表';

/*Data for the table `wm_app_type` */

insert  into `wm_app_type`(`type_id`,`type_topid`,`type_pid`,`type_name`,`type_cname`,`type_pinyin`,`type_order`,`type_ico`,`type_info`,`type_tempid`,`type_ctempid`,`type_title`,`type_key`,`type_desc`) values (12,0,'0','体育竞速','体育','tiyu',5,NULL,'',0,0,'','',''),(11,0,'0','策略塔防','策略','celue',4,NULL,'',0,0,'','',''),(8,0,'0','角色扮演','角色','jiaose',1,NULL,'',0,0,'','',''),(9,0,'0','动作冒险','动作','dongzuo',2,'','',0,0,'','',''),(10,0,'0','飞行射击','飞行','feixing',3,NULL,'',0,0,'','',''),(13,0,'0','益智休闲','益智','yizhi',6,NULL,'',0,0,'','',''),(14,0,'0','卡牌桌游','卡牌','kapai',7,NULL,'',0,0,'','',''),(15,0,'0','模拟养成','养成','yangcheng',9,NULL,'',0,0,'','',''),(16,0,'0','汉化精品','汉化','hanhua',8,NULL,'',0,0,'','','');

/*Table structure for table `wm_article_article` */

DROP TABLE IF EXISTS `wm_article_article`;

CREATE TABLE `wm_article_article` (
  `article_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(4) NOT NULL DEFAULT '1' COMMENT '新闻分类id',
  `article_display` tinyint(1) DEFAULT '1' COMMENT '是否显示、生成html',
  `article_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0为审核，1为正常，2为回收站',
  `article_weight` int(4) NOT NULL DEFAULT '0' COMMENT '文章权重，越大越靠前',
  `article_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `article_head` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否头条',
  `article_strong` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否加粗',
  `article_name` varchar(50) NOT NULL COMMENT '文章标题',
  `article_cname` varchar(35) DEFAULT NULL COMMENT '文章简称',
  `article_color` varchar(20) DEFAULT NULL COMMENT '标题颜色',
  `article_simg` varchar(200) DEFAULT NULL COMMENT '缩略图',
  `article_source` varchar(20) DEFAULT NULL COMMENT '文章来源',
  `article_tags` varchar(50) DEFAULT NULL COMMENT '标签',
  `article_url` varchar(120) DEFAULT NULL COMMENT '是否跳转',
  `article_author` varchar(20) DEFAULT 'admin' COMMENT '作者',
  `article_author_id` int(11) DEFAULT '0' COMMENT '文章作者',
  `article_editor` varchar(20) DEFAULT 'admin' COMMENT '编辑',
  `article_editor_id` int(4) DEFAULT '1' COMMENT '编辑的id',
  `article_info` varchar(250) DEFAULT NULL COMMENT '简介',
  `article_save_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '文章内容保存类型，1为入库，2为文件',
  `article_save_path` varchar(250) DEFAULT NULL COMMENT '文件保存的路径',
  `article_content` mediumtext COMMENT '内容',
  `article_read` int(4) NOT NULL DEFAULT '0' COMMENT '阅读量',
  `article_replay` int(4) NOT NULL DEFAULT '0' COMMENT '回复量',
  `article_score` decimal(2,1) DEFAULT '0.0' COMMENT '文章评分',
  `article_ding` int(4) NOT NULL DEFAULT '0' COMMENT '顶',
  `article_cai` int(4) NOT NULL DEFAULT '0' COMMENT '踩',
  `article_addtime` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间年月日时分秒',
  `article_examinetime` int(4) NOT NULL DEFAULT '0' COMMENT '审核时间',
  PRIMARY KEY (`article_id`),
  KEY `tid_index` (`type_id`,`article_addtime`),
  KEY `attr_index` (`article_display`,`article_status`,`article_weight`,`article_rec`,`article_head`,`article_strong`),
  KEY `list_index` (`article_status`,`type_id`),
  KEY `status_index` (`article_status`),
  KEY `type_index` (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='文章内容表';

/*Data for the table `wm_article_article` */

/*Table structure for table `wm_article_author` */

DROP TABLE IF EXISTS `wm_article_author`;

CREATE TABLE `wm_article_author` (
  `author_id` int(4) NOT NULL AUTO_INCREMENT,
  `author_type` varchar(2) NOT NULL DEFAULT 'a' COMMENT '是作者还是文章来源,a是作者，s是文章来源',
  `author_name` varchar(20) NOT NULL COMMENT '作者或者来源名字',
  `author_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否为默认数据',
  `author_data` int(11) DEFAULT '0' COMMENT '当前属性配对的数据条数',
  PRIMARY KEY (`author_id`)
) ENGINE=MyISAM AUTO_INCREMENT=66 DEFAULT CHARSET=utf8 COMMENT='文章作者表';

/*Data for the table `wm_article_author` */

insert  into `wm_article_author`(`author_id`,`author_type`,`author_name`,`author_default`,`author_data`) values (1,'s','原创',1,10),(32,'a','admin',1,10),(65,'e','admin',0,10);

/*Table structure for table `wm_article_type` */

DROP TABLE IF EXISTS `wm_article_type`;

CREATE TABLE `wm_article_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_status` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `type_topid` int(4) NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '子栏目id',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_cname` varchar(10) DEFAULT NULL COMMENT '分类简称',
  `type_pinyin` varchar(50) DEFAULT NULL COMMENT '分类拼音',
  `type_order` int(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `type_ico` varchar(200) DEFAULT NULL COMMENT '图标',
  `type_info` varchar(100) DEFAULT NULL COMMENT '分类简介',
  `type_add` tinyint(1) DEFAULT '1' COMMENT '分类是否允许投稿',
  `type_titempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类首页',
  `type_tempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类列表',
  `type_ctempid` int(4) NOT NULL DEFAULT '0' COMMENT '内容页的模版id',
  `type_title` varchar(80) DEFAULT NULL COMMENT '分类标题',
  `type_key` varchar(100) DEFAULT NULL COMMENT '分类关键字',
  `type_desc` varchar(120) DEFAULT NULL COMMENT '分类描述',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='文章分类表';

/*Data for the table `wm_article_type` */

insert  into `wm_article_type`(`type_id`,`type_status`,`type_topid`,`type_pid`,`type_name`,`type_cname`,`type_pinyin`,`type_order`,`type_ico`,`type_info`,`type_add`,`type_titempid`,`type_tempid`,`type_ctempid`,`type_title`,`type_key`,`type_desc`) values (1,1,0,'0','网站公告','公告','gg',1,'','',0,0,0,0,'','',''),(2,1,0,'0','新闻资讯','新闻','news',2,'','',0,0,0,0,'','',''),(3,1,1,'1','ces','','',1,'','',0,0,0,0,'','',''),(4,1,0,'0','ceshi分类',NULL,NULL,0,NULL,NULL,1,0,0,0,NULL,NULL,NULL);

/*Table structure for table `wm_author_author` */

DROP TABLE IF EXISTS `wm_author_author`;

CREATE TABLE `wm_author_author` (
  `author_id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL DEFAULT '0' COMMENT '用户id',
  `author_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为审核中,1为正常,2为不通过',
  `author_nickname` varchar(20) NOT NULL COMMENT '作者笔名，不允许更改',
  `author_info` varchar(100) NOT NULL COMMENT '作者简介',
  `author_notice` varchar(500) DEFAULT NULL COMMENT '作者公告',
  `author_time` int(4) NOT NULL DEFAULT '0' COMMENT '开通时间',
  `author_toptime` int(4) NOT NULL DEFAULT '0' COMMENT '上次登录的时间',
  PRIMARY KEY (`author_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='作者表';

/*Data for the table `wm_author_author` */

/*Table structure for table `wm_author_draft` */

DROP TABLE IF EXISTS `wm_author_draft`;

CREATE TABLE `wm_author_draft` (
  `draft_id` int(11) NOT NULL AUTO_INCREMENT,
  `draft_author_id` int(4) NOT NULL COMMENT '草稿的作者',
  `draft_module` varchar(20) NOT NULL COMMENT '草稿箱所属模块',
  `draft_cid` int(4) NOT NULL DEFAULT '0' COMMENT '草稿所属的内容id',
  `draft_title` varchar(100) NOT NULL COMMENT '草稿标题',
  `draft_content` text NOT NULL COMMENT '草稿内容',
  `draft_number` int(4) NOT NULL DEFAULT '0' COMMENT '草稿字数',
  `draft_option` varchar(2000) DEFAULT NULL COMMENT '其他数据的选项',
  `draft_createtime` int(4) NOT NULL COMMENT '草稿创建时间',
  PRIMARY KEY (`draft_id`),
  KEY `draft_index` (`draft_author_id`,`draft_module`,`draft_cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='草稿箱';

/*Data for the table `wm_author_draft` */

/*Table structure for table `wm_author_end` */

DROP TABLE IF EXISTS `wm_author_end`;

CREATE TABLE `wm_author_end` (
  `end_id` int(4) NOT NULL AUTO_INCREMENT,
  `end_module` varchar(20) DEFAULT NULL COMMENT '完结奖励模块',
  `end_type` varchar(20) DEFAULT NULL COMMENT '完结奖励验证类型（字数、章节）',
  `end_number` int(4) DEFAULT NULL COMMENT '奖励数字要求',
  `end_gold1` decimal(5,2) DEFAULT NULL COMMENT '奖励金币1',
  `end_gold2` decimal(5,2) DEFAULT NULL COMMENT '奖励金币2',
  PRIMARY KEY (`end_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='完结奖励表';

/*Data for the table `wm_author_end` */

/*Table structure for table `wm_author_exp` */

DROP TABLE IF EXISTS `wm_author_exp`;

CREATE TABLE `wm_author_exp` (
  `exp_id` int(4) NOT NULL AUTO_INCREMENT,
  `exp_module` varchar(20) NOT NULL COMMENT '经验值所属的模块',
  `exp_number` int(4) NOT NULL DEFAULT '0' COMMENT '经验值',
  `exp_author_id` int(4) NOT NULL COMMENT '作者id',
  PRIMARY KEY (`exp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='作者经验等级表';

/*Data for the table `wm_author_exp` */

/*Table structure for table `wm_author_level` */

DROP TABLE IF EXISTS `wm_author_level`;

CREATE TABLE `wm_author_level` (
  `level_id` int(4) NOT NULL AUTO_INCREMENT,
  `level_module` varchar(15) DEFAULT NULL COMMENT '等级模块',
  `level_name` varchar(20) NOT NULL COMMENT '等级名字',
  `level_start` int(4) NOT NULL DEFAULT '0' COMMENT '开始经验',
  `level_end` int(4) NOT NULL DEFAULT '0' COMMENT '结束经验',
  `level_order` int(4) NOT NULL DEFAULT '0' COMMENT '显示位置',
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM AUTO_INCREMENT=26 DEFAULT CHARSET=utf8 COMMENT='经验等级';

/*Data for the table `wm_author_level` */

insert  into `wm_author_level`(`level_id`,`level_module`,`level_name`,`level_start`,`level_end`,`level_order`) values (1,'novel','初入文坛',0,100,1),(2,'novel','初露锋芒',100,300,2),(3,'novel','略有小成',300,600,3),(4,'novel','粉丝众多',600,1000,4),(5,'novel','职业写手',1000,1500,5),(6,'novel','写作大神',1500,2100,6),(22,'article','普通写手',0,100,1),(23,'article','中级写手',100,500,2),(24,'article','高级写手',500,2000,3),(25,'article','专职写手',2000,5000,4);

/*Table structure for table `wm_author_module_income` */

DROP TABLE IF EXISTS `wm_author_module_income`;

CREATE TABLE `wm_author_module_income` (
  `income_id` int(4) NOT NULL AUTO_INCREMENT,
  `income_module` varchar(20) NOT NULL COMMENT '收入的模块',
  `income_author_id` int(4) NOT NULL COMMENT '作者id',
  `income_cid` int(4) NOT NULL COMMENT '模块的内容',
  `income_gold1_now` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前累积的金币1',
  `income_gold2_now` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '当前累积的金币2',
  `income_gold1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总共累积的金币1',
  `income_gold2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '总共累积的金币2',
  PRIMARY KEY (`income_id`),
  KEY `user_index` (`income_module`,`income_author_id`,`income_cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='作者模块内容收入记录表';

/*Data for the table `wm_author_module_income` */

/*Table structure for table `wm_author_sign` */

DROP TABLE IF EXISTS `wm_author_sign`;

CREATE TABLE `wm_author_sign` (
  `sign_id` int(4) NOT NULL AUTO_INCREMENT,
  `sign_module` varchar(20) NOT NULL COMMENT '模块',
  `sign_name` varchar(20) NOT NULL COMMENT '签约等级名字',
  `sign_divide` varchar(5) NOT NULL DEFAULT '7:3' COMMENT '道具打赏收入分成比例',
  `sign_gold1` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '千字金币1',
  `sign_gold2` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '千字金币2',
  `sign_order` tinyint(2) DEFAULT '0' COMMENT '排序',
  PRIMARY KEY (`sign_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='小说作者签约等级';

/*Data for the table `wm_author_sign` */

insert  into `wm_author_sign`(`sign_id`,`sign_module`,`sign_name`,`sign_divide`,`sign_gold1`,`sign_gold2`,`sign_order`) values (1,'novel','D级签约','9:1','1.00','10.00',1),(2,'novel','C级签约','8:2','2.00','20.00',2),(3,'novel','B级签约','7:3','3.00','30.00',3),(4,'novel','A级签约','6:4','4.00','40.00',4),(5,'novel','白银签约','5:5','5.00','50.00',5),(6,'novel','黄金签约','4:6','6.00','60.00',6),(7,'novel','白金签约','3:7','7.00','70.00',7);

/*Table structure for table `wm_author_word` */

DROP TABLE IF EXISTS `wm_author_word`;

CREATE TABLE `wm_author_word` (
  `word_id` int(4) NOT NULL AUTO_INCREMENT,
  `word_module` varchar(20) DEFAULT NULL COMMENT '模块',
  `word_author_id` int(4) DEFAULT NULL COMMENT '作者id',
  `word_cid` int(4) DEFAULT NULL COMMENT '内容id',
  `word_time` int(4) DEFAULT NULL COMMENT '时间',
  PRIMARY KEY (`word_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='字数更新记录表';

/*Data for the table `wm_author_word` */

/*Table structure for table `wm_bbs_bbs` */

DROP TABLE IF EXISTS `wm_bbs_bbs`;

CREATE TABLE `wm_bbs_bbs` (
  `bbs_id` int(4) NOT NULL AUTO_INCREMENT,
  `bbs_isreplay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要回复',
  `bbs_islogin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要登录',
  `bbs_ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否付费',
  `bbs_isself` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否只能自己查看',
  `type_id` int(4) NOT NULL COMMENT '分类id',
  `bbs_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为审核中，1为正常',
  `bbs_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '推荐',
  `bbs_es` tinyint(1) NOT NULL DEFAULT '0' COMMENT '精华',
  `bbs_top` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为全站置顶，2为分类置顶，3为当前置顶',
  `bbs_simg` varchar(120) DEFAULT NULL COMMENT '缩略图',
  `bbs_title` varchar(30) NOT NULL COMMENT '帖子标题',
  `bbs_content` text NOT NULL COMMENT '帖子内容',
  `user_id` int(4) NOT NULL DEFAULT '0' COMMENT '发帖用户',
  `bbs_time` int(4) NOT NULL DEFAULT '0' COMMENT '发帖时间',
  `bbs_replay_time` int(4) NOT NULL DEFAULT '0' COMMENT '最后回帖时间',
  `bbs_replay_nickname` varchar(20) DEFAULT NULL COMMENT '回帖用户昵称',
  `bbs_replay_uid` int(4) NOT NULL DEFAULT '0' COMMENT '回帖用户id',
  `bbs_read` int(4) NOT NULL DEFAULT '0' COMMENT '浏览量',
  `bbs_replay` int(4) NOT NULL DEFAULT '0' COMMENT '回复量',
  `bbs_tags` varchar(50) DEFAULT NULL COMMENT '标签',
  `bbs_ding` int(4) NOT NULL DEFAULT '0' COMMENT '顶',
  `bbs_cai` int(4) NOT NULL DEFAULT '0' COMMENT '踩',
  `bbs_score` decimal(5,2) NOT NULL DEFAULT '0.00' COMMENT '评分',
  PRIMARY KEY (`bbs_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='论坛主题表';

/*Data for the table `wm_bbs_bbs` */

/*Table structure for table `wm_bbs_moderator` */

DROP TABLE IF EXISTS `wm_bbs_moderator`;

CREATE TABLE `wm_bbs_moderator` (
  `moderator_id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL DEFAULT '0' COMMENT '用户id',
  `type_id` int(4) NOT NULL DEFAULT '0' COMMENT '版块id',
  PRIMARY KEY (`moderator_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='论坛版主表';

/*Data for the table `wm_bbs_moderator` */

/*Table structure for table `wm_bbs_type` */

DROP TABLE IF EXISTS `wm_bbs_type`;

CREATE TABLE `wm_bbs_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_post_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为开启',
  `type_replay_open` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为开启',
  `type_topid` int(4) NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '子栏目id',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_cname` varchar(10) NOT NULL COMMENT '类型简称',
  `type_pinyin` varchar(50) NOT NULL COMMENT '类型拼音',
  `type_order` int(2) NOT NULL DEFAULT '50' COMMENT '排序',
  `type_info` varchar(200) DEFAULT NULL COMMENT '简介',
  `type_tempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类页模版',
  `type_ctempid` int(4) NOT NULL DEFAULT '0' COMMENT '内容页模版',
  `type_rtempid` int(4) NOT NULL DEFAULT '0' COMMENT '评论页模版',
  `type_ico` varchar(225) DEFAULT NULL COMMENT '图标地址',
  `type_title` varchar(80) DEFAULT NULL COMMENT '版块标题',
  `type_key` varchar(100) DEFAULT NULL COMMENT '版块关键字',
  `type_desc` varchar(120) DEFAULT NULL COMMENT '版块描述',
  `type_last_post` int(4) NOT NULL DEFAULT '0' COMMENT '最后发帖',
  `type_last_replay` int(4) NOT NULL DEFAULT '0' COMMENT '最后回帖',
  `type_uptime` int(4) NOT NULL DEFAULT '0' COMMENT '数据更新时间',
  `type_sum_post` int(4) NOT NULL DEFAULT '0' COMMENT '总帖子',
  `type_sum_replay` int(4) NOT NULL DEFAULT '0' COMMENT '总回复',
  `type_sum_read` int(4) NOT NULL DEFAULT '0' COMMENT '总浏览',
  `type_today_post` int(4) NOT NULL DEFAULT '0' COMMENT '日帖子',
  `type_today_replay` int(4) NOT NULL DEFAULT '0' COMMENT '日回复',
  `type_today_read` int(4) NOT NULL DEFAULT '0' COMMENT '日浏览',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='论坛版块表';

/*Data for the table `wm_bbs_type` */

/*Table structure for table `wm_config_config` */

DROP TABLE IF EXISTS `wm_config_config`;

CREATE TABLE `wm_config_config` (
  `config_id` int(4) NOT NULL AUTO_INCREMENT,
  `config_status` tinyint(1) DEFAULT '1' COMMENT '是否显示配置',
  `group_id` int(4) DEFAULT '0' COMMENT '配置分组id',
  `config_module` varchar(20) NOT NULL COMMENT '配置所属的模块',
  `config_title` varchar(35) NOT NULL COMMENT '配置的标题',
  `config_name` varchar(35) NOT NULL COMMENT '配置的字段名',
  `config_value` varchar(2000) NOT NULL COMMENT '配置的字段值',
  `config_formtype` varchar(20) DEFAULT NULL COMMENT '配置的表单类型',
  `config_remark` varchar(200) DEFAULT NULL COMMENT '配置的备注信息',
  `config_order` int(4) DEFAULT NULL COMMENT '配置的显示顺序',
  PRIMARY KEY (`config_id`,`config_title`)
) ENGINE=MyISAM AUTO_INCREMENT=442 DEFAULT CHARSET=utf8 COMMENT='系统配置表';

/*Data for the table `wm_config_config` */

insert  into `wm_config_config`(`config_id`,`config_status`,`group_id`,`config_module`,`config_title`,`config_name`,`config_value`,`config_formtype`,`config_remark`,`config_order`) values (1,1,1,'system','网站名字','webname','WMCMS演示网站','input','网站的名字',1),(2,1,1,'system','网站主域','weburl','wmcms.com','input','网站的主域名(禁止/结尾,无需填写http://)',2),(3,1,1,'system','站长邮箱','email','1747699213@qq.com','input','站长的联系邮箱',3),(14,1,1,'system','备案号','beian','渝ICP证030173号','input','网站域名的备案号',4),(7,1,1,'system','网站开启','siteopen','1','radio','网站开启关闭的状态',5),(8,1,1,'system','关闭提示','closeinfo','对不起，网站升级中暂时关闭。','textarea','网站关闭的时候的提示信息',6),(9,1,1,'templates','简版模版','tp1','wmcms-web','input','wap1.0使用的模版',9),(10,1,1,'templates','3G模板','tp2','wmcms-web','input','3g网站使用模版',10),(11,1,1,'templates','触屏模板','tp3','wmcms-m','input','触屏网站使用的模版',11),(12,1,1,'templates','电脑模板','tp4','wmcms-web','input','电脑网站使用模版',12),(13,1,15,'urlmode','开启伪静态','url_type','1','select','开启伪静态将消耗服务器资源',8),(15,1,1,'domain','电脑域名','domain4','','input','直接访问电脑版的域名',8),(16,1,1,'domain','触屏域名','domain3','','input','直接访问触屏版的域名',7),(17,1,1,'domain','3G域名','domain2','','input','直接访问3G版的域名',6),(18,1,1,'domain','简版域名','domain1','','input','直接访问简版的域名',5),(25,1,4,'user','禁用关键字','shieldkey','管理员,站长,系统,admin','input','用户昵称禁用的关键字',50),(28,1,1,'system','邮件发送日志','emaillog_open','0','select','是否记录邮件发送的日志',13),(390,1,1,'cache','队列缓存类型','cache_queuetype','file','select','队列缓存使用的类型',16),(391,1,1,'cache','sql缓存文件夹','sql_folder','sql','input','sql缓存保存的文件夹',5),(392,1,1,'cache','sql缓存文件后缀','sql_ext','.sql','input','sql缓存文件名的后缀名',5),(39,1,1,'system','站长QQ','qq','1747699213','input','站长的QQ号码',9),(40,1,1,'system','站长电话','phone','15123931801','input','站长的电话号码',10),(47,1,1,'domain','显示pt标识','pt_rep','0','input','是否显示pt识别标识符',11),(42,1,1,'tongji','统计代码','tongji','','textarea','统计代码',0),(43,1,1,'domain','简洁版标识','tpmark1','wap','input','简洁版的识别标识符',13),(44,1,1,'domain','3G版标识','tpmark2','3g','input','3G版的识别标识符',12),(45,1,1,'domain','触屏版标识','tpmark3','m','input','触屏版的识别标识符',10),(46,1,1,'domain','电脑版标识','tpmark4','web','input','电脑版的识别标识符',9),(50,1,1,'domain','原始域名','bdomain','','input','将原来的域名',1),(51,1,1,'domain','新的域名','ndomain','','input','跳转到新的域名',2),(53,1,1,'domain','后台绑定域名','admin_domain','','input','网站后台管理绑定的域名',3),(54,1,1,'system','后台目录','admin_path','admin','input','后台文件所在的目录,如：admin',2),(57,1,1,'code','后台登录验证码','code_admin_login','0','radio','后台登录是否需要填写验证码',1),(67,1,1,'upload','自动剪裁','upload_cut','0','radio','是否开启图片自动剪裁',3),(58,1,1,'upload','上传类型','upload_type','jpeg,jpg,gif,png,gif,txt,doc,apk','input','允许上传的文件类型用,隔开',2),(59,1,1,'upload','上传大小','upload_size','1024','input','限制上传文件大小，Kb单位',2),(60,1,1,'upload','开启水印','watermark_open','1','radio','是否开启水印功能',20),(61,1,1,'upload','触发水印的宽度','watermark_width','100','input','当图片的宽度大于此值',21),(62,1,1,'upload','触发水印的高度','watermark_height','200','input','当图片的宽度大于此值',22),(63,1,1,'upload','水印类型','watermark_type','text','radio','水印的类型！图片水印的路径/files/images/watermark.png',23),(64,1,1,'upload','水印位置','watermark_location','9','radio','水印的显示位置',27),(65,1,1,'upload','水印颜色','watermark_color','#e01515','input','文字水印的颜色',25),(66,1,1,'upload','文字大小','watermark_size','5','input','文字水印的大小，单位：像素',26),(68,1,1,'upload','触发剪裁的宽度','upload_imgwidth','1200','input','当图片的宽度大于此值',4),(69,1,1,'upload','触发剪裁的高度','upload_imgheight','800','input','当图片的高度大于此值',5),(70,1,1,'upload','剪裁后的宽度','upload_cutwidth','500','input','图片剪裁后的宽度',6),(71,1,1,'upload','剪裁后的高度','upload_cutheight','500','input','图片剪裁后的高度',7),(72,1,1,'upload','水印内容','watermark_text','wmcms中文标签','input','文字水印的内容',24),(73,1,1,'system','程序主语言','lang','zh_cn','radio','程序使用的语言',15),(389,1,1,'cache','sql缓存类型','cache_sqltype','file','select','sql缓存使用的类型',16),(75,1,1,'system','允许代理访问','proxy_visit','1','radio','是否允许代理访问',16),(77,1,1,'system','管理员请求','request_open','0','radio','是否开启后台管理员请求日志',11),(78,1,1,'system','管理员操作','operation_open','0','radio','是否开启后台管理员修改数据的备份记录',14),(79,1,2,'article','文章页检查参数','par_article','1','radio','文章页的是否检测所有参数正确',1),(80,1,2,'article','评论页检查参数','par_replay','1','radio','评论页的是否检测所有参数正确',2),(81,1,2,'article','后台发布默认状态','admin_add','1','radio','后台管理员发布文章的默认状态',3),(82,1,11,'author','投稿功能是否开启','author_article_open','1','radio','用户投稿功能是否开启',24),(84,1,2,'article','文章搜索开关','search_open','1','radio','文章搜索功能是否开启',5),(85,1,2,'article','搜索间隔','search_time','3','input','每次搜索的间隔时间(单位:秒)',6),(86,1,2,'article','开启文章顶踩','dingcai_open','1','select','是否开启文章顶踩功能',7),(87,1,2,'article','文章顶踩次数','dingcai_count','2','input','每篇文章每日的顶踩次数限制',8),(88,1,1,'system','记录错误日志','err_open','0','select','是否开启网站错误日志记录',12),(89,1,2,'article','直接永久删除数据','admin_del','1','select','删除文章是直接删除还是先到回收站',3),(90,1,2,'article','文章评论开关','replay_open','0','select','文章模块是否开启评论功能',9),(91,1,2,'article','评论是否需要登录','replay_login','1','select','文章模块评论是否需要登录',11),(92,1,3,'novel','小说页参数检查','par_info','1','select','小说详情页分类id和书籍id同时检查',1),(93,1,3,'novel','评论页参数检查','par_replay','0','select','小说评论页分类id和书籍id同时检查',2),(94,1,3,'novel','目录页参数检查','par_menu','1','select','小说目录页分类id和书籍id同时检查',3),(95,1,3,'novel','阅读页参数检查','par_read','1','select','小说阅读页分类id和书籍id同时检查',4),(96,1,3,'novel','小说文件保存路径','novel_save','/files/txt/novel/{#123}tid{#125}/{#123}nid{#125}/index.txt','input','小说生成的txt文件保存路径',5),(97,1,3,'novel','小说章节保存路径','chapter_save','/files/txt/novel/{#123}tid{#125}/{#123}nid{#125}/{#123}cid{#125}.txt','input','小说生成的章节txt文件保存路径',6),(98,1,3,'novel','默认封面','cover','/files/images/nocover.jpg','input','默认小说封面地址',7),(99,1,3,'novel','后台书籍发布的状态','admin_novel_add','1','select','后台书籍发布的默认审核状态',8),(100,1,3,'novel','后台章节发布的状态','admin_chapter_add','1','select','后台章节发布的默认审核状态',9),(101,1,3,'novel','发布默认类型','type','1','select','小说发布默认类型',10),(102,1,3,'novel','数据入库模式','data_type','1','radio','数据入库模式',11),(103,1,3,'novel','开启小说下载','down_open','1','radio','是否开启小说下载',12),(104,1,3,'novel','默认最新章节名','new_cname','暂无章节','input','新建小说默认最新章节名',13),(105,1,3,'novel','生成TXT前置内容模版','novel_head','本小说网站完全无毒','textarea','新建小说生成txt的前面模版',14),(106,1,3,'novel','新建TXT章节开始模版','chapter_start','欢迎收藏','textarea','新建TXT章节开始模版',15),(107,1,3,'novel','新建TXT章节结束模版','chapter_end','请记住本网址','textarea','新建TXT章节结束模版',16),(108,1,3,'novel','章节默认是否需要登录','chapter_isvip','0','radio','发布章节默认是否需要登录阅读',17),(109,1,3,'novel','订阅章节使用金币种类','buy_gold_type','2','radio','订阅章节使用金币种类',13),(110,1,3,'novel','低于字数不收费','buy_wordnumber','500','input','低于多少字的章节不收费',19),(111,1,3,'novel','顶踩开关','dingcai_open','1','select','小说顶踩功能开启',20),(112,1,3,'novel','顶踩次数','dingcai_count','1','input','每本小说每日顶踩次数限制',21),(113,1,3,'novel','评分次数','score_count','1','input','每本小说每日评分次数限制',23),(114,1,3,'novel','评分开启','score_open','1','select','小说评分功能开启',22),(115,1,3,'novel','评分是否登录','score_login','0','select','小说评分功能是否需要登录',24),(116,1,3,'novel','评论开启','replay_open','0','select','小说评论功能是否开启',25),(117,1,3,'novel','评论需要登录','replay_login','1','select','小说评论功能是否需要登录',26),(118,1,3,'novel','收藏功能','coll_open','1','select','小说收藏功能是否开启',27),(119,1,3,'novel','推荐功能','rec_open','1','select','小说推荐功能是否开启',28),(120,1,3,'novel','消费金币1获得粉丝经验值','fans_exp_gold1','200','input','小说订阅功能是否开启',30),(121,1,3,'novel','打赏金币功能','reward_open','1','select','小说打赏金币功能是否开启',31),(122,1,3,'novel','搜索功能','search_open','1','select','小说搜索功能是否开启',32),(123,1,3,'novel','搜索间隔','search_time','3','input','小说搜索间隔时间',33),(124,1,3,'novel','搜索跳转','search_jump','0','radio','小说搜索只有一条结果的时候是否直接跳转到小说页',34),(125,1,3,'novel','书架是否开启','shelf_open','1','select','书架功能是否开启',31),(126,1,4,'user','注册功能','reg_open','1','select','是否开启注册',1),(127,1,4,'user','登陆功能','login_open','1','select','是否开启登陆',2),(128,1,4,'user','登陆日志','login_log','1','select','是否开启登陆日志记录',3),(129,1,4,'user','默认头像类型','user_head','1','select','新用户注册头像默认',4),(130,1,4,'user','默认头像类型','default_head','/files/images/nohead.gif','input','默认头像的地址',5),(131,1,4,'user','新用户注册状态','reg_status','1','select','新用户注册账号的默认状态',6),(132,1,4,'user','头像宽度','head_width','100','input','用户头像宽度',7),(133,1,4,'user','头像高度','head_height','100','input','用户头像高度',8),(134,1,4,'user','系统头像','msg_head','/files/images/system.jpg','input','系统的头像',9),(135,1,4,'user','注册赠送金币1','reg_gold1','2','input','新用户注册赠送金币1的数量',10),(136,1,4,'user','注册赠送金币2','reg_gold2','0','input','新用户注册赠送金币2的数量',11),(137,1,4,'user','注册赠送经验','reg_exp','0','input','新用户注册赠送经验的数量',12),(138,1,4,'user','注册默认签名','reg_sign','wmcms与您共在！','textarea','新用户注册的默认签名',13),(139,1,4,'user','经验名字','exp_name','梦点','input','网站经验的名字',14),(140,1,4,'user','金币1名字','gold1_name','梦元','input','网站金币1的名字',15),(141,1,4,'user','金币2名字','gold2_name','梦宝','input','网站金币2的名字',16),(142,1,4,'user','金币1单位','gold1_unit','元','input','网站金币1的单位',17),(143,1,4,'user','金币2单位','gold2_unit','个','input','网站金币2的单位',18),(144,1,4,'user','登陆赠送经验','login_exp','1','input','每日首次登陆赠送的经验值',19),(145,1,4,'user','登陆赠送金币1','login_gold1','0','input','每日首次登陆赠送的金币1数量',20),(146,1,4,'user','登陆赠送金币2','login_gold2','0','input','每日首次登陆赠送的金币2数量',21),(147,1,1,'system','检查保留字符','check_shield','0','select','检查保留字符',98),(148,1,1,'system','检查禁用字符','check_disable','0','select','检查禁用字符',99),(149,1,4,'user','签到开关','sign_open','1','select','签到功能开关',24),(150,1,4,'user','签到奖励开关','sign_reward','0','select','签到奖励开关',25),(151,1,4,'user','签到奖励经验','sign_exp','1','input','每日签到奖励的经验值',26),(152,1,4,'user','签到奖励金币1','sign_gold1','0','input','每日签到奖励的金币1',27),(153,1,4,'user','签到奖励金币2','sign_gold2','0','input','每日签到奖励的金币2',28),(154,1,5,'replay','开启评论','replay_open','1','select','是否开启评论功能',1),(155,1,5,'replay','统一设置','unify','1','select','其他模块是否由评论模块设置',2),(156,1,5,'replay','是否登录','replay_login','0','select','评论是否需要登录',3),(157,1,5,'replay','默认评论状态','status','1','select','默认评论是审核状态还是待审核',4),(158,1,5,'replay','两次评论间隔','top_time','15','input','两次评论之间的间隔',5),(159,1,5,'replay','评论条数','everyday_count','100','input','每天能评论的次数',6),(160,1,5,'replay','顶踩功能','dingcai_open','1','select','评论顶踩功能是否开启',7),(161,1,5,'replay','顶踩次数','dingcai_count','1','input','同一评论每日顶踩次数',8),(162,1,5,'replay','游客评论昵称','nickname','游客','input','游客评论的时候的昵称',9),(163,1,5,'replay','前几次评论奖励','reward_count','0','input','每天前几次评论有奖励',10),(164,1,5,'replay','奖励金币1','replay_gold1','0','input','评论奖励金币1',11),(165,1,5,'replay','奖励金币2','replay_gold2','0','input','评论奖励金币2',12),(166,1,5,'replay','奖励经验值','replay_exp','0','input','评论奖励经验值',13),(167,1,5,'replay','js评论框名','boxname','江湖茶馆','input','js评论框的名字',14),(168,1,5,'replay','无数据提示','no_data','就等你了','input','js评论无数据的时候提示',15),(169,1,5,'replay','输入框提示','input','少侠,你不来两句？','input','js评论输入框提示',16),(170,1,5,'replay','显示热门评论','hot','1','select','是否显示热门评论',17),(171,1,5,'replay','多少顶成为热门评论','hot_display','2','input','多少顶成为热门评论',18),(172,1,5,'replay','热门评论条数','hot_count','2','input','显示多少条热门评论',19),(173,1,5,'replay','每页条数','list_limit','6','input','每页显示多少条评论',20),(174,1,5,'replay','分页链接数量','page_count','5','input','显示多少条跳页按钮',21),(175,1,6,'link','友链页参数检查','par_link','0','select','友链页参数检查',1),(176,1,6,'link','开启申请','join','1','select','是否开启申请友链',2),(177,1,6,'link','友链点击类型','click_type','0','select','友链的点击类型',3),(178,1,6,'link','防刷新时间','ref_time','3600','input','防止刷新时间',4),(179,1,6,'link','检查点击UA','check_ua','1','select','点击友链的时候是否检查UA',5),(180,1,6,'link','只记录一次IP','check_oneip','1','select','同一天点击只记录一次IP',6),(181,1,6,'link','点击日志','click_log','1','select','开启点击记录统计',7),(182,1,6,'link','IP统计开启','getip_open','1','select','开启IP统计开启',8),(183,1,6,'link','地址统计开启','getadress_open','0','select','开启地址统计开启',9),(184,1,6,'link','前台申请默认状态','join_status','0','select','友链前台申请默认状态',10),(185,1,6,'link','后台添加默认状态','admin_status','1','select','友链后台添加默认状态',11),(186,1,6,'link','友链进入页面','in_jump','','input','友链点入的时候进入的页面，空为首页',12),(187,1,7,'picture','图集页参数检查','par_picture','0','select','图集页参数检查',1),(188,1,7,'picture','评论页参数检查','par_replay','0','select','图集评论页参数检查',2),(189,1,7,'picture','后台更新需要审核','admin_add','1','select','后台更新图集是否需要审核',3),(190,1,7,'picture','用户投稿需要审核','user_add','1','select','用户图集投稿需要审核审核',4),(191,1,7,'picture','顶踩功能','dingcai_open','1','select','顶踩功能',5),(192,1,7,'picture','评分功能','score_open','1','select','评分功能是否开启',6),(193,1,7,'picture','评论功能','replay_open','1','select','评论功能是否开启',7),(194,1,7,'picture','评分是否登录','score_login','1','select','评分是否需要登录',8),(195,1,7,'picture','搜索是否开启','search_open','1','select','搜索是否开启',9),(196,1,7,'picture','顶踩次数','dingcai_count','1','input','同一内容每日顶踩次数',10),(197,1,7,'picture','评分次数','score_count','1','input','同一内容每日评分次数',11),(198,1,7,'picture','搜索间隔','search_time','3','input','每次搜索的间隔时间',12),(199,1,7,'picture','评论是否登录','replay_login','1','select','评论是否需要登录',14),(200,1,8,'app','应用页参数检查','par_app','0','select','应用页参数检查',1),(201,1,8,'app','评论页参数检查','par_replay','0','select','应用评论页参数检查',2),(202,1,8,'app','应用默认图标','default_ico','/files/images/noimage.gif','input','应用的默认图标',3),(203,1,8,'app','应用默认封面','default_simg','/files/images/noimage.gif','input','应用的默认封面',4),(204,1,8,'app','后台更新需要审核','admin_add','1','select','后台更新应用是否需要审核',5),(205,1,8,'app','是否开启应用下载','down_open','1','select','是否开启应用下载功能',6),(206,1,8,'app','顶踩功能','dingcai_open','1','select','顶踩功能',6),(207,1,8,'app','顶踩次数','dingcai_count','1','input','同一内容每日顶踩次数',7),(208,1,8,'app','评分功能','score_open','1','select','评分功能是否开启',8),(209,1,8,'app','评分次数','score_count','1','input','同一内容每日评分次数',9),(210,1,8,'app','评分是否登录','score_login','0','select','评分是否需要登录',10),(211,1,8,'app','评论功能','replay_open','1','select','评论功能是否开启',11),(212,1,8,'app','评论是否登录','replay_login','0','select','评论是否需要登录',12),(213,1,8,'app','搜索是否开启','search_open','1','select','搜索是否开启',13),(214,1,8,'app','搜索间隔','search_time','3','input','两次搜索的时间间隔',14),(215,1,9,'bbs','主题页参数检查','par_bbs','0','select','主题页参数检查',1),(216,1,9,'bbs','回帖页参数检查','par_relist','0','select','回帖页参数检查',2),(217,1,9,'bbs','作者是否可以查看','author_look','1','select','作者是否可以查看审核中的主题',3),(218,1,9,'bbs','作者是否可以修改','author_up','1','select','作者是否可以修改自己的主题',4),(219,1,9,'bbs','作者是否可以删除','author_del','1','select','作者是否可以删除自己的主题',5),(220,1,9,'bbs','发帖默认状态','user_post','1','select','用户发帖默认状态',6),(221,1,9,'bbs','回帖默认状态','user_replay','1','select','用户回帖默认状态',7),(222,1,9,'bbs','修改后是否需要审核','up_status','1','select','用户修改主题后是否需要审核',8),(223,1,9,'bbs','发帖功能','post_open','0','select','是否开启发帖功能',9),(224,1,9,'bbs','回帖功能','replay_open','0','select','是否开启回帖功能',10),(225,1,9,'bbs','发帖限制','post_num','0','input','是否限制每日发帖数量',11),(226,1,9,'bbs','前几次发帖奖励','post_top','5','input','每天前几次发帖有奖励',12),(227,1,9,'bbs','发帖奖励金币1','post_gold1','1','input','发帖奖励金币1',13),(228,1,9,'bbs','发帖奖励金币2','post_gold2','0','input','发帖奖励金币2',14),(229,1,9,'bbs','发帖奖励经验','post_exp','1','input','发帖奖励经验',15),(230,1,9,'bbs','每日回帖限制','everyday_count','0','input','每日回帖限制数量',16),(231,1,9,'bbs','前几次回帖有奖励','reward_count','5','input','每日前几次回帖有奖励',17),(232,1,9,'bbs','回帖奖励金币1','replay_gold1','1','input','回帖奖励金币1',18),(233,1,9,'bbs','回帖奖励金币2','replay_gold2','0','input','回帖奖励金币2',18),(234,1,9,'bbs','回帖奖励经验','replay_exp','1','input','回帖奖励经验',19),(235,1,9,'bbs','默认版块图标','default_ico','/files/images/forum.png','input','默认版块图标',20),(236,1,9,'bbs','搜索间隔','search_time','3','input','两次搜索的时间间隔',22),(237,1,9,'bbs','开启搜索','search_open','1','select','是否开启主题搜索',21),(238,1,2,'article','前台调用缺省缩略图','default_simg','/files/images/noimage.gif','input','前台调用缩略图没有的时候缺省封面',0),(239,1,1,'logo','简版logo','logo_1','','input','简版使用的logo',1),(240,1,1,'logo','彩版logo','logo_2','','input','彩版使用的logo',2),(241,1,1,'logo','触屏logo','logo_3','/files/images/logo.png','input','触屏使用的logo',3),(242,1,1,'logo','电脑logo','logo_4','/files/images/logo.png','input','电脑使用的logo',4),(243,1,1,'system','数据库类型','db','mysql','select','使用的数据库类型',17),(244,1,10,'message','留言功能开关','message_open','1','select','是否开启留言功能',1),(245,1,10,'message','每天留言条数','message_count','3','input','每天留言的限制条数',2),(246,1,1,'logo','微信二维码','ewm_wx','','input','微信二维码',5),(247,1,1,'logo','支付宝二维码','ewm_alipay','/files/images/ewm_alipay.png','input','支付宝二维码',6),(248,1,1,'logo','微博二维码','ewm_weibo','','input','微博二维码',7),(249,1,1,'logo','QQ群二维码','ewm_qun','/files/images/ewm_qun.png','input','QQ群二维码',8),(250,1,1,'cache','缓存开关','cache_open','0','select','是否开启缓存',1),(251,1,1,'cache','缓存类型','cache_type','file','select','缓存的类型',2),(252,1,1,'cache','缓存保存路径','cache_path','cache','input','缓存保存的路径',3),(253,1,1,'cache','文件缓存路径','file_folder','site','input','文件缓存保存路径',4),(254,1,1,'cache','文件缓存后缀','file_ext','.cache','input','文件缓存后缀',5),(255,1,1,'cache','队列保存文件夹','queue_folder','queue','input','队列保存文件夹',6),(256,1,1,'cache','队列缓存后缀','queue_ext','.queue','input','队列缓存后缀',7),(257,1,1,'cache','redis服务器','redis_host','127.0.0.1','input','redis服务器',8),(258,1,1,'cache','redis端口','redis_port','6379','input','redis端口',9),(259,1,1,'cache','memcached服务器','memcached_host','127.0.0.1','input','memcached服务器',10),(260,1,1,'cache','memcached端口','memcached_port','11211','input','memcached端口',11),(261,1,1,'cache','缓存机制','cache_mechanism','page','select','缓存机制',1),(262,1,1,'cache','SQL缓存','cache_sql','0','radio','SQL缓存',1),(263,1,1,'cache','缓存保存时间','cache_time','300','input','缓存保存时间(单位:秒)',12),(268,1,4,'user','ajax登陆后操作','ajax_login','ref','select','ajax登陆后执行的操作',29),(265,1,1,'cache','队列缓存开启','cache_queue','0','radio','队列缓存是否开启',14),(266,1,1,'cache','队列缓存时间','cache_queuetime','300','input','队列缓存时间',15),(267,1,1,'cache','SQL缓存时间','cache_sqltime','300','input','SQL缓存时间',16),(269,1,11,'author','作者申请默认状态','apply_author_status','0','select','作者申请默认的审核状态',2),(270,1,11,'author','开启自助作者申请','apply_author_open','1','select','是否开启前台自助作者申请',1),(271,1,11,'author','默认简介','author_default_info','这个人很懒呢！','input','申请作者默认的个人简介',3),(272,1,11,'author','默认公告','author_default_notice','此作者暂时没有公告！','input','申请作者默认的公告',4),(273,1,4,'user','余额名字','money_name','软妹币','input','账户余额的名字',30),(274,1,11,'author','小说审核方式','author_novel_createnovel','0','select','新建的小说审核方式',5),(275,1,11,'author','章节审核方式','author_novel_createchapter','0','select','新建的小说章节审核方式',6),(276,1,11,'author','上传封面宽度','author_novel_coverwidth','400','input','上传封面的宽度限制',7),(277,1,11,'author','上传封面高度','author_novel_coverheight','500','input','上传封面的高度限制',8),(278,1,11,'author','封面审核方式','author_novel_uploadcover','0','radio','封面审核方式',9),(279,1,11,'author','未审核消息','author_apply_0','您好，您的作者申请已经提交，请等待管理员审核！。','textarea','作者状态变更为未审核的原因',10),(280,1,11,'author','审核通过原因','author_apply_1','您好，您申请的作者已经通过审核，现在您可以去创建小说了！','textarea','作者审核通过的时候发送消息的原因',11),(281,1,11,'author','拒绝通过原因','author_apply_2','对不起，您申请的作者没有通过审核！\r\n原因如下：\r\n1.可能资料不完善\r\n2.你的笔名涉嫌违法','textarea','作者拒绝通过审核的原因',12),(282,1,11,'author','未审核原因','author_novel_cover_0','您好，您上传的封面请等待管理员审核通过后才能显示！','textarea','封面变成未审核的原因',13),(283,1,11,'author','审核通过原因','author_novel_cover_1','恭喜您，您提交的小说《{#123}小说名字{#125}》封面审核通过！','textarea','封面审核通过的原因',14),(284,1,11,'author','拒绝审核的原因','author_novel_cover_2','对不起，您申请的小说《{#123}小说名字{#125}》封面没有通过审核！\r\n原因如下：\r\n1.可能封面涉黄、暴力！\r\n2.违法国家相关法律！','textarea','封面拒绝审核的原因',15),(285,1,11,'author','编辑小说审核方式','author_novel_editnovel','0','select','编辑小说是否需要审核',16),(286,1,11,'author','编辑章节审核方式','author_novel_editchapter','0','select','编辑章节是否需要审核',17),(287,1,11,'author','未审核原因','author_novel_editnovel_0','您好，您编辑的小说请等待管理员审核！','textarea','编辑小说状态变更为未审核的原因',18),(288,1,11,'author','审核通过原因','author_novel_editnovel_1','恭喜您，您提交的小说《{#123}小说名字{#125}》信息审核通过！','textarea','编辑小说审核通过的原因',19),(289,1,11,'author','拒绝审核的原因','author_novel_editnovel_2','对不起，您编辑的小说《{#123}小说名字{#125}》没有通过审核！\r\n原因如下：\r\n1.可能内容涉黄、暴力！\r\n2.违法国家相关法律！','textarea','编辑小说拒绝审核的原因',20),(290,1,11,'author','未审核原因','author_novel_editchapter_0','您好，您编辑的章节请等待管理员审核！','textarea','编辑章节状态变更为未审核的原因',21),(291,1,11,'author','审核通过原因','author_novel_editchapter_1','恭喜您，您的小说《{#123}小说名字{#125}》章节《{#123}小说章节名字{#125}》审核通过！','textarea','编辑章节审核通过的原因',22),(292,1,11,'author','拒绝审核的原因','author_novel_editchapter_2','对不起，您编辑的小说《{#123}小说名字{#125}》章节《{#123}小说章节名字{#125}》没有通过审核！\r\n原因如下：\r\n1.可能内容涉黄、暴力！\r\n2.违法国家相关法律！','textarea','编辑章节拒绝审核的原因',23),(293,1,11,'author','文章审核方式','author_article_createarticle','0','select','文章投稿的审核方式',24),(294,1,11,'author','编辑文章审核方式','author_article_editarticle','0','select','编辑文章投稿的审核方式',25),(295,1,11,'author','未审核原因','author_article_editarticle_0','您好，请等待管理员审核您的稿件！','textarea','编辑文章变更为未审核的原因',26),(296,1,11,'author','审核通过原因','author_article_editarticle_1','恭喜您，您的投稿已经审核通过！','textarea','编辑文章审核通过的原因',27),(297,1,11,'author','拒绝审核的原因','author_article_editarticle_2','对不起，您的投稿没有通过审核！\r\n原因如下：\r\n1.可能内容涉黄、暴力！\r\n2.违法国家相关法律！','textarea','编辑文章变更为未审核的原因',28),(302,1,1,'system','自动上传错误','err_auto_upload','1','select','发现错误是否自动上报到云服务器进行处理',13),(301,1,15,'urlmode','是否开启HTML','ishtml','0','radio','是否开启HTML访问',6),(303,1,3,'novel','消费金币1数量','cons_gold1','200','input','消费金币1的数量',30),(304,1,3,'novel','消费金币1赠送月票','cons_gold1_month','0','input','消费金币1达到一定数量赠送月票',31),(322,1,3,'novel','消费金币1赠送推荐票','cons_gold1_rec','1','input','消费金币1达到一定数量赠送推荐票',32),(305,1,4,'user','登录赠送推荐票','login_rec','0','input','每日登录赠送的推荐票数量',31),(306,1,4,'user','登录赠送月票','login_month','0','input','每日登录赠送的月票数量',32),(307,1,4,'user','签到赠送月票','sign_month','0','input','每日签到赠送月票数量',33),(308,1,4,'user','签到赠送推荐票','sign_rec','0','input','每日签到赠送推荐数量',34),(309,1,4,'user','推荐票名字','ticket_rec','推荐票','input','推荐票的自定义名字',35),(310,1,4,'user','月票名字','ticket_month','月票','input','月票的自定义名字',36),(311,1,12,'finance','开启充值','recharge_open','0','select','是否开启充值',1),(312,1,12,'finance','余额兑换金币2','money_to_gold2_open','0','select','是否开启余额兑换金币2',2),(313,1,12,'finance','金币2兑换金币1','gold2_to_gold1_open','0','select','是否开启金币2兑换金币1',3),(314,1,12,'finance','金币1兑换金币2','gold1_to_gold2_open','0','select','是否开启金币1兑换金币2',4),(315,1,12,'finance','金币2兑换余额','gold2_to_money_open','0','select','是否开启金币2兑换余额',5),(316,1,12,'finance','是否开启提现','cash_open','1','select','是否开启提现',6),(317,1,12,'finance','充值比例','rmb_to_gold2','10','input','充值比例',7),(318,1,12,'finance','余额兑换金币2比例','money_to_gold2','1','input','余额兑换金币2比例',8),(319,1,12,'finance','金币2兑换金币1比例','gold2_to_gold1','10','input','金币2兑换金币1比例',9),(320,1,12,'finance','金币1兑换金币2比例','gold1_to_gold2','0','input','金币1兑换金币2比例',10),(321,1,12,'finance','金币2兑换余额比例','gold2_to_money','0.1','input','金币2兑换余额比例',11),(323,1,3,'novel','消费金币2数量','cons_gold2','100','input','消费金币2的数量',33),(324,1,3,'novel','消费金币2赠送月票','cons_gold2_month','2','input','消费金币2达到一定数量赠送月票',34),(325,1,3,'novel','消费金币2赠送推荐票','cons_gold2_rec','3','input','消费金币2达到一定数量赠送推荐票',35),(326,1,4,'user','注册赠送推荐票','reg_rec','0','input','注册赠送的推荐票',48),(327,1,4,'user','注册赠送月票','reg_month','0','input','注册赠送的月票',49),(328,1,3,'novel','消费金币2获得粉丝经验值','fans_exp_gold2','15','input','消费金币2到一定数量获得粉丝经验值',36),(329,1,11,'author','登录赠送','login_exp','10','input','每日登录赠送的作者经验值',29),(330,1,11,'author','收入推荐票经验值','income_rec','5','input','收入一张推荐票获得多少经验值',30),(331,1,11,'author','收入月票经验值','income_month','10','input','收入一张月票获得多少经验值',31),(332,1,11,'author','收入金币1数量','income_gold1','100','input','收入金币1多少数量可以获得1点作者经验值',32),(333,1,11,'author','收入金币2数量','income_gold2','50','input','收入金币2多少数量可以获得1点作者经验值',33),(334,1,3,'novel','赠送礼物功能','give_open','1','select','赠送礼物功能是否开启',37),(335,1,3,'novel','顶踩是否登录','dingcai_login','1','select','顶踩是否需要登录',38),(336,1,3,'novel','阅读页未订阅提示方式','read_sub_prompt','1','select','阅读页未订阅提示方式',39),(337,1,12,'finance','卡密购买地址','card_buy_url','http://www.weimengcms.com','input','卡密购买地址',12),(338,1,12,'finance','首充奖励','recharge_reward_gold2','10','input','首次充值奖励多少金币2',13),(339,1,12,'finance','充值活动','activity_open','1','radio','充值活动是否开启',14),(340,1,12,'finance','活动开始时间','activity_starttime','1524055804','input','活动开始时间',15),(341,1,12,'finance','活动结束时间','activity_endtime','1524919806','input','活动结束时间',16),(342,1,12,'finance','提现手续费','cash_cost','8','input','提现手续费',17),(343,1,12,'finance','最低提现金额','cash_lowest','100','input','最低提现金额',18),(344,1,1,'upload','上传保存方式','upload_savetype','0','select','上传文件保存的位置',1),(345,1,1,'upload','远程保存路径/前缀','upload_savepath','','input','远程上传文件保存的位置或者前缀',1),(346,1,2,'article','文章评分功能','score_open','1','select','文章评分功能是否开启',12),(347,1,2,'article','文章顶踩登录','dingcai_login','1','select','文章顶踩是否需要登录',13),(348,1,2,'article','文章评分登录','score_login','1','select','文章评分是否需要登录',14),(349,1,2,'article','每篇文章评分次数','score_count','1','input','每日每篇文章评分次数',15),(350,1,5,'replay','评论顶踩登录','dingcai_login','1','select','顶踩评论是否需要登陆了',22),(351,1,1,'system','自动识别UA跳转','auto_jump','1','select','是否开启自动检测并且跳转到相应的手机版或者电脑版',4),(352,1,1,'code','用户注册验证码','code_user_reg','1','radio','用户注册是否开启验证码',2),(353,1,1,'code','用户登录验证码','code_user_login','1','radio','用户登录是否开启验证码',3),(354,1,1,'code','修改密码验证码','code_user_uppsw','1','radio','修改密码是否开启验证码',4),(355,1,1,'code','找回密码验证码','code_user_getpsw','1','radio','找回密码是否开启验证码',5),(356,1,1,'code','发表评论验证码','code_replay','0','radio','发表评论是否开启验证码',6),(357,1,1,'code','发表主题验证码','code_bbs_post','1','radio','发表主题是否开启验证码',7),(358,1,1,'code','回复主题验证码','code_bbs_replay','1','radio','回复主题是否开启验证码',8),(359,1,1,'code','问答验证码题库','code_question','WMCMS的官网是什么？|www.weimengcms.com\r\nWMCMS创始人是谁？|未梦','textarea','问答验证码的题库',9),(360,1,1,'code','后台登录验证码类型','code_admin_login_type','2','select','后台登录验证码的类型',10),(361,1,1,'code','用户注册验证码类型','code_user_reg_type','2','select','用户注册验证码的类型',11),(362,1,1,'code','用户登录验证码类型','code_user_login_type','2','select','用户登录验证码的类型',12),(363,1,1,'code','找回密码验证码类型','code_user_uppsw_type','2','select','找回密码验证码的类型',13),(364,1,1,'code','发表评论验证码类型','code_user_getpsw_type','2','select','发表评论验证码的类型',14),(365,1,1,'code','发表主题验证码类型','code_replay_type','1','select','发表主题验证码的类型',15),(366,1,1,'code','回复主题验证码类型','code_bbs_post_type','1','select','回复主题验证码的类型',16),(367,1,1,'code','问答验证码题库类型','code_bbs_replay_type','1','select','问答验证码题库的类型',17),(368,1,1,'cache','全站首页缓存','cache_index','300','input','全站首页缓存时间',17),(369,1,1,'cache','模块首页缓存','cache_module_index','300','input','模块首页缓存时间',18),(370,1,1,'cache','模块分类首页缓存','cache_module_tindex','300','input','模块分类首页缓存时间',19),(371,1,1,'cache','模块排行首页缓存','cache_module_topindex','36000','input','模块排行首页缓存时间',20),(372,1,1,'cache','模块列表缓存','cache_module_type','600','input','模块列表缓存时间',21),(373,1,1,'cache','模块评论缓存','cache_module_replay','600','input','模块评论列表缓存时间',22),(374,1,1,'cache','模块搜索缓存','cache_module_search','600','input','模块搜索列表缓存时间',23),(375,1,1,'cache','模块排行列表缓存','cache_module_toplist','36000','input','模块排行列表缓存时间',24),(376,1,1,'cache','模块内容缓存','cache_module_content','1800','input','模块内容缓存时间',25),(377,1,1,'cache','模块目录缓存','cache_module_menu','1800','input','模块目录缓存时间',26),(378,1,1,'cache','模块详情缓存','cache_module_info','360000','input','模块详情缓存时间',27),(379,1,2,'article','更新文章自动创建HTML','auto_create_html','0','select','更新文章自动创建HTML',16),(380,1,3,'novel','更新章节自动创建HTML','auto_create_html','0','select','更新章节自动创建HTML',40),(381,1,8,'app','更新应用自动创建HTML','auto_create_html','0','select','更新应用自动创建HTML',14),(382,1,1,'system','COOKIE共享方式','cookie_type','0','select','网站的cookeie共享方式',15),(383,1,1,'site','站群开关','site_open','0','select','站群的是否开启或者关闭',1),(384,1,5,'replay','楼层备注','replay_floor_nickname','沙发\r\n板凳\r\n地板','textarea','从上倒下沙发开始，不限制楼层数量。',23),(385,1,5,'replay','楼层名','replay_floor_name','楼','input','楼层的名字是什么：比如“楼”',24),(386,1,3,'novel','章节比较','chapter_compare','2','select','章节入库同章判断方式',41),(387,1,3,'novel','对比相似度','chapter_compare_number','70','input','相似度为多少则表示为一样的内容',42),(393,1,3,'novel','阅读记录','read_open','1','select','是否开启小说阅读记录',43),(394,1,4,'user','接口登录绑定账号','api_login_bind','0','select','第三方登陆是否强制绑定本站的账号',50),(395,1,1,'domain','后台域名认证','admin_domain_access','0','select','后台只能通过域名访问',14),(397,1,3,'novel','小说自定义加密字符串','novel_en_str','','input','小说文件名字加密时候的自定义字符串',5),(396,1,1,'system','网络协议','tcp_type','http','select','网络访问的TCP协议类型',2),(398,1,1,'upload','上传开关','upload_open','1','select','是否允许前台上传文件',0),(399,1,1,'code','后台登录错误次数','admin_login_error_number','5','input','后台允许登录错误次数',1),(400,1,1,'code','后台错误登录限制时间','admin_login_error_time','1440','input','后台达到错误次数后封锁登录时间',1),(401,1,1,'code','用户登录错误次数','user_login_error_number','5','input','用户允许登录错误次数',3),(402,1,1,'code','用户错误登录限制时间','user_login_error_time','1440','input','用户达到错误次数后封锁登录时间',3),(403,1,6,'link','公共链接出站模式','link_url_outtype','1','select','公共链接出站模式',1),(404,1,14,'zt','专题页检查参数','par_zt','1','radio','专题页的是否检测所有参数正确',1),(405,1,14,'zt','评论页检查参数','par_replay','1','radio','评论页的是否检测所有参数正确',2),(406,1,14,'zt','开启专题顶踩','dingcai_open','0','select','是否开启专题顶踩功能',11),(407,1,14,'zt','专题顶踩登录','dingcai_login','1','select','专题顶踩是否需要登录',12),(408,1,14,'zt','专题顶踩次数','dingcai_count','2','input','每篇专题每日的顶踩次数限制',13),(409,1,14,'zt','开启专题评分','score_open','0','select','是否开启专题评分功能',14),(410,1,14,'zt','专题评分登录','score_login','1','select','专题评分是否需要登录',15),(411,1,14,'zt','专题评分次数','score_count','1','input','每日每篇专题评分次数',16),(412,1,14,'zt','专题评论开关','replay_open','0','select','专题模块是否开启评论功能',17),(413,1,14,'zt','专题评论登录','replay_login','1','select','专题模块评论是否需要登录',18),(414,1,15,'urlmode','URL参数分割字符','urlmode_exp','/','input','URL参数的分割字符',3),(415,1,15,'urlmode','URL文件后缀','urlmode_suffix','.html','input','URL文件后缀，可以为空',4),(416,1,15,'urlmode','普通模式模块参数名','urlmode_par_odnr1','module','input','普通模式的模块参数名',5),(417,1,15,'urlmode','普通模式文件参数名','urlmode_par_odnr2','file','input','普通模式的文件参数名',6),(418,1,15,'urlmode','兼容模式参数名','urlmode_par_cptb','path','input','兼容模式的参数名',7),(419,1,15,'urlmode','URL路由','urlmode_route','','textarea','url参数的路由匹配',8),(420,1,2,'article','数据保存方式','data_save_type','2','radio','文章数据保存的方式',17),(421,1,2,'article','文件保存路径','data_save_path','/files/txt/article/{#123}tid{#125}/{#123}aid{#125}.txt','input','文章数据保存的路径',18),(422,1,1,'upload','自动生成缩略图','upload_simg','1','select','是否开启自动生成缩略图',8),(423,1,1,'upload','触发缩略图宽度','upload_simg_width','900','input','触发生成缩略图的原图宽度',9),(424,1,1,'upload','触发缩略图高度','upload_simg_height','600','input','触发生成缩略图的原图高度',10),(425,1,1,'upload','缩略图宽度','upload_simgwidth','540','input','生成的缩略图宽度',11),(426,1,1,'upload','缩略图高度','upload_simgheight','370','input','生成的缩略图高度',12),(427,1,5,'replay','评论显示模式','replay_type','2','select','评论列表显示模式',25),(428,1,5,'replay','默认显示回复数','replay_replay_number','5','input','每条评论的回复显示几条',26),(429,1,5,'replay','每页显示回复数','replay_replay_page','10','input','每页显示多少条回复',27),(430,1,5,'replay','回复排序方式','replay_replay_order','replay_time','select','评论回复额排序方式',28),(431,1,4,'user','登录清空推荐票','login_clear_ticket','0','select','登录是否清空推荐票',55),(432,1,11,'author','作者财务收入模式','author_income_type','2','select','作者的财务收入结算模式',34),(433,1,11,'author','销售统计财务申请模式','author_finance_apply_type','2','select','销售统计财务申请模式',35),(434,1,1,'system','短信发送日志','tellog_open','1','select','是否记录短信发送的日志',13),(435,1,16,'dev','预警通知','warning_open','0','select','是否开启预警通知',1),(436,1,16,'dev','预警通道','warning_channel','','select','预警通知渠道',2),(437,1,16,'dev','WebHook通道','warning_hook_channel','','select','通知webhook渠道',3),(438,1,16,'dev','预警通道收件人','warning_receive','','input','预警收件人',4),(439,1,16,'dev','预警类型','warning_type','','check','预警通知类型',5),(440,1,3,'novel','小说文件保存路径','novel_mp3_save','/files/audio/novel/{#123}tid{#125}/{#123}nid{#125}/index.mp3','input','小说生成的听书mp3主文件保存路径',6),(441,1,3,'novel','小说章节保存路径','chapter_mp3_save','/files/audio/novel/{#123}tid{#125}/{#123}nid{#125}/{#123}cid{#125}.mp3','input','小说生成的听书mp3章节文件保存路径',6);

/*Table structure for table `wm_config_field` */

DROP TABLE IF EXISTS `wm_config_field`;

CREATE TABLE `wm_config_field` (
  `field_id` int(4) NOT NULL AUTO_INCREMENT,
  `field_name` varchar(20) DEFAULT NULL COMMENT '字段名字',
  `field_module` varchar(20) NOT NULL COMMENT '所属模块',
  `field_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为分类字段，2为内容字段',
  `field_type_id` int(4) NOT NULL COMMENT '所属分类的id',
  `field_type_child` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否对子级分类有效',
  `field_type_pids` varchar(50) NOT NULL DEFAULT '0' COMMENT '分类的级别关系',
  `field_option` varchar(2000) NOT NULL COMMENT '选项',
  PRIMARY KEY (`field_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自建字段表';

/*Data for the table `wm_config_field` */

/*Table structure for table `wm_config_field_value` */

DROP TABLE IF EXISTS `wm_config_field_value`;

CREATE TABLE `wm_config_field_value` (
  `value_id` int(4) NOT NULL AUTO_INCREMENT,
  `value_field_id` int(4) NOT NULL COMMENT '选项的id',
  `value_field_module` varchar(20) DEFAULT NULL COMMENT '值的模块',
  `value_field_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '值的类型',
  `value_content_id` int(4) NOT NULL DEFAULT '0' COMMENT '内容的id，默认为0',
  `field_option` varchar(2000) DEFAULT NULL COMMENT '选项的标题',
  `value_option` varchar(2000) NOT NULL COMMENT '选项的值',
  PRIMARY KEY (`value_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自建字段的值';

/*Data for the table `wm_config_field_value` */

/*Table structure for table `wm_config_group` */

DROP TABLE IF EXISTS `wm_config_group`;

CREATE TABLE `wm_config_group` (
  `group_id` int(4) NOT NULL AUTO_INCREMENT,
  `group_name` varchar(10) DEFAULT NULL COMMENT '分组的名',
  `group_remark` varchar(50) DEFAULT NULL COMMENT '分组备注信息',
  PRIMARY KEY (`group_id`)
) ENGINE=MyISAM AUTO_INCREMENT=17 DEFAULT CHARSET=utf8 COMMENT='配置分组';

/*Data for the table `wm_config_group` */

insert  into `wm_config_group`(`group_id`,`group_name`,`group_remark`) values (1,'web','系统配置组'),(2,'article','文章配置组'),(3,'novel','小说配置组'),(4,'user','用户配置组'),(5,'replay','评论配置组'),(6,'link','友链配置组'),(7,'picture','图集配置组'),(8,'app','应用配置组'),(9,'bbs','论坛配置组'),(10,'message','留言配置组'),(11,'author','原创配置组'),(12,'finance','财务配置组'),(13,'site','站群配置组'),(14,'zt','专题配置组'),(15,'route','路由配置组'),(16,'dev','开发配置组');

/*Table structure for table `wm_config_label` */

DROP TABLE IF EXISTS `wm_config_label`;

CREATE TABLE `wm_config_label` (
  `label_id` int(4) NOT NULL AUTO_INCREMENT,
  `label_title` varchar(20) DEFAULT NULL COMMENT '标签标题',
  `label_name` varchar(20) DEFAULT NULL COMMENT '标签名字',
  `label_value` varchar(500) DEFAULT NULL COMMENT '标签的值',
  PRIMARY KEY (`label_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义标签表';

/*Data for the table `wm_config_label` */

/*Table structure for table `wm_config_option` */

DROP TABLE IF EXISTS `wm_config_option`;

CREATE TABLE `wm_config_option` (
  `option_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `config_id` int(4) DEFAULT '0' COMMENT '配置的id',
  `option_title` varchar(50) DEFAULT NULL COMMENT '选项标题',
  `option_value` varchar(50) DEFAULT NULL COMMENT '选项的值',
  `option_order` int(4) DEFAULT '9' COMMENT '选项的排序',
  PRIMARY KEY (`option_id`)
) ENGINE=MyISAM AUTO_INCREMENT=412 DEFAULT CHARSET=utf8 COMMENT='系统配置选项';

/*Data for the table `wm_config_option` */

insert  into `wm_config_option`(`option_id`,`config_id`,`option_title`,`option_value`,`option_order`) values (1,7,'开启网站','1',1),(2,7,'关闭网站','0',2),(3,13,'传统伪静态模式','2',2),(4,13,'传统动态模式','1',1),(5,57,'开启验证码','1',1),(6,57,'关闭验证码','0',2),(7,73,'中文-简体','zh_cn',1),(8,67,'关闭自动剪裁','0',1),(9,67,'开启自动剪裁','1',2),(10,60,'关闭水印','0',1),(11,60,'开启水印','1',2),(12,63,'图片水印','img',1),(13,63,'文字水印','text',2),(14,75,'禁止代理访问','0',1),(15,75,'允许代理访问','1',2),(16,77,'开启统计','1',1),(17,77,'关闭统计','0',2),(18,78,'开启记录','1',1),(19,78,'关闭记录','0',2),(35,80,'关闭检查','0',1),(34,80,'开启检查','1',0),(25,79,'开启检查','1',1),(26,79,'关闭检查','0',2),(36,81,'默认未审','0',2),(37,81,'默认审核','1',1),(38,82,'关闭投稿','0',1),(39,82,'开启投稿','1',2),(40,84,'开启搜索','1',0),(41,84,'关闭搜索','0',1),(42,86,'开启顶踩','1',1),(43,86,'关闭顶踩','0',2),(44,88,'关闭错误日志记录','0',2),(45,88,'开启错误日志记录','1',1),(46,89,'先删除到回收站','0',1),(47,89,'直接永久删除','1',2),(48,90,'关闭评论','0',2),(49,90,'开启评论','1',1),(50,91,'需要登录','1',2),(51,91,'无需登录','0',1),(52,92,'开启检查','1',2),(53,92,'关闭检查','0',1),(54,93,'开启检查','1',2),(55,93,'关闭检查','0',1),(56,94,'开启检查','1',2),(57,94,'关闭检查','0',1),(58,95,'开启检查','1',2),(59,95,'关闭检查','0',1),(60,101,'他站首发','2',2),(61,101,'原创首发','1',1),(62,102,'生成TXT','1',1),(63,102,'直接入库','2',2),(64,103,'关闭小说下载','0',2),(65,103,'开启小说下载','1',1),(66,99,'待审状态','0',2),(67,99,'通过状态','1',1),(68,100,'待审状态','0',2),(69,100,'通过状态','1',1),(70,108,'登录查看','1',2),(71,108,'无需登录','0',1),(73,109,'金币2','2',2),(72,109,'金币1','1',1),(74,111,'关闭顶踩','0',1),(75,111,'开启顶踩','1',2),(76,114,'关闭评分','0',1),(77,114,'开启评分','1',2),(78,115,'需要登录','1',1),(79,115,'无需登录','0',2),(80,116,'开启评论','1',2),(81,116,'关闭评论','0',1),(82,117,'无需登录','0',1),(83,117,'需要登录','1',2),(84,118,'关闭收藏','0',1),(85,118,'开启收藏','1',2),(86,119,'开启推荐','1',2),(87,119,'关闭推荐','0',1),(88,120,'开启订阅','1',2),(89,120,'关闭订阅','0',1),(251,311,'开启充值','1',2),(250,311,'关闭充值','0',1),(92,122,'关闭搜索','0',1),(93,122,'开启搜索','1',2),(94,124,'显示列表','0',1),(95,124,'跳转书页','1',2),(96,125,'关闭书架','0',1),(97,125,'开启书架','1',2),(98,126,'开启注册','1',1),(99,126,'关闭注册','0',2),(100,127,'关闭登陆','0',2),(101,127,'开启登陆','1',1),(102,128,'开启记录日志','1',2),(103,128,'关闭记录日志','0',1),(104,129,'默认头像','0',2),(105,129,'随机头像','1',1),(106,131,'正常状态','1',2),(107,131,'等待审核','0',1),(108,147,'开启检查','1',2),(109,147,'无需检查','0',1),(110,148,'开启检查','1',2),(111,148,'无需检查','0',1),(112,149,'开启签到','1',2),(113,149,'关闭签到','0',1),(114,150,'关闭签到奖励','0',2),(115,150,'开启签到奖励','1',1),(116,154,'开启评论','1',2),(117,154,'关闭评论','0',1),(118,155,'开启统一设置','1',2),(119,155,'关闭统一设置','0',1),(120,156,'评论无需登录','0',2),(121,156,'评论需要登录','1',1),(122,157,'审核状态','0',1),(123,157,'通过状态','1',2),(124,160,'关闭顶踩','0',2),(125,160,'开启顶踩','1',1),(126,170,'关闭热门评论','0',2),(127,170,'显示热门评论','1',1),(128,175,'开启检查','1',2),(129,175,'关闭检查','0',1),(130,176,'关闭申请','0',2),(131,176,'开启申请','1',1),(132,177,'直接跳出','0',1),(133,177,'进入详情页','1',2),(134,179,'关闭检查','0',2),(135,179,'开启检查','1',1),(136,180,'不限制记录次数','0',2),(137,180,'一天只记录同IP一次','1',1),(138,181,'开启点击日志记录','1',1),(139,181,'关闭点击日志记录','0',2),(140,182,'关闭IP统计','0',1),(141,182,'开启IP统计','1',2),(142,183,'关闭地址统计','0',1),(143,183,'开启地址统计','1',2),(144,184,'无需审核','1',1),(145,184,'需要审核','0',2),(146,185,'正常状态','1',1),(147,185,'审核状态','0',2),(148,187,'关闭检查','0',1),(149,187,'开启检查','1',2),(150,188,'开启检查','1',2),(151,188,'关闭检查','0',1),(152,189,'无需审核','1',1),(153,189,'需要审核','0',2),(154,190,'无需审核','1',2),(155,190,'需要审核','0',1),(156,191,'开启顶踩功能','1',1),(157,191,'关闭顶踩功能','0',2),(158,192,'关闭评分功能','0',2),(159,192,'开启评分功能','1',1),(160,193,'关闭评论功能','0',2),(161,193,'开启评论功能','1',1),(162,194,'需要登录','1',1),(163,194,'无需登录','0',2),(164,195,'开启搜索','1',1),(165,195,'关闭搜索','0',2),(166,199,'游客也可评论','0',2),(167,199,'登录才能评论','1',1),(168,200,'关闭检查','0',1),(169,200,'开启检查','1',2),(170,201,'开启检查','1',2),(171,201,'关闭检查','0',1),(172,204,'无需审核','1',1),(173,204,'需要审核','0',2),(174,205,'开启下载','1',2),(175,205,'关闭下载','0',1),(176,206,'开启顶踩功能','1',1),(177,206,'关闭顶踩功能','0',2),(178,208,'关闭评分功能','0',2),(179,208,'开启评分功能','1',1),(180,211,'关闭评论功能','0',2),(181,211,'开启评论功能','1',1),(182,212,'需要登录','1',1),(183,212,'无需登录','0',2),(184,213,'开启搜索','1',1),(185,213,'关闭搜索','0',2),(186,215,'关闭检查','0',1),(187,215,'开启检查','1',2),(188,216,'关闭检查','0',1),(189,216,'开启检查','1',2),(190,217,'作者可以查看','1',1),(191,217,'作者不能查看','0',2),(192,218,'作者可以修改','1',1),(193,218,'作者不能修改','0',2),(194,219,'作者可以删除','1',1),(195,219,'作者不能删除','0',2),(196,220,'无需审核','1',1),(197,220,'需要审核','0',2),(198,221,'无需审核','1',1),(199,221,'需要审核','0',2),(200,222,'无需审核','1',1),(201,222,'需要审核','0',2),(202,223,'开启发帖','1',1),(203,223,'关闭发帖','0',2),(227,268,'跳转用户中心','jump',2),(206,237,'开启搜索','1',1),(207,237,'关闭搜索','0',2),(208,243,'MYSQL','mysql',1),(209,244,'开启留言','1',1),(210,244,'关闭留言','0',2),(211,210,'无需登录','0',1),(212,210,'需要登录','1',2),(213,250,'关闭缓存','0',1),(214,250,'开启缓存','1',2),(215,251,'文件缓存','file',1),(216,251,'redis缓存','redis',2),(217,251,'memcached缓存','memcached',3),(218,261,'页面缓存','page',1),(219,261,'区块缓存','block',2),(220,262,'关闭sql缓存','0',1),(221,262,'开启sql缓存','1',2),(222,265,'关闭队列缓存','0',1),(223,265,'开启队列缓存','1',2),(226,268,'刷新当前页','ref',1),(225,389,'文件缓存','file',1),(204,224,'开启回帖','1',1),(205,224,'关闭回帖','0',2),(228,269,'手动审核','0',1),(229,269,'自动审核','1',2),(230,270,'关闭自助申请','0',1),(231,270,'开启自助申请','1',2),(232,274,'自动审核','1',2),(233,274,'人工审核','0',1),(234,275,'人工审核','0',1),(235,275,'自动审核','1',2),(236,278,'人工审核','0',1),(237,278,'自动审核','1',2),(238,285,'人工审核','0',1),(239,285,'自动审核','1',2),(240,286,'人工审核','0',1),(241,286,'自动审核','1',2),(242,293,'人工审核','0',1),(243,293,'自动审核','1',2),(244,294,'人工审核','0',1),(245,294,'自动审核','1',2),(246,301,'不使用HTML','0',1),(247,301,'使用HTML','1',2),(248,302,'开启自动上传','1',1),(249,302,'关闭自动上传','0',2),(252,312,'关闭兑换','0',1),(253,312,'开启兑换','1',2),(254,313,'关闭兑换','0',1),(255,313,'开启兑换','1',2),(256,314,'关闭兑换','0',1),(257,314,'开启兑换','1',2),(258,315,'关闭兑换','0',1),(259,315,'开启兑换','1',2),(260,316,'关闭提现','0',1),(261,316,'开启提现','1',2),(262,121,'关闭打赏金币','0',1),(263,121,'开启打赏金币','1',2),(264,334,'关闭赠送礼物','0',1),(265,334,'开启赠送礼物','1',2),(266,335,'无需登陆','0',1),(267,335,'需要登录','1',2),(268,336,'页面标签提示','1',1),(269,336,'通用错误页面提示','2',2),(270,339,'关闭活动','0',1),(271,339,'开启活动','1',2),(272,344,'本地保存','0',1),(273,344,'阿里云OSS','oss',2),(274,344,'腾讯云COS','cos',3),(275,344,'七牛云','qiniu',4),(276,344,'新浪云','scs',5),(277,346,'关闭评分','0',1),(278,346,'开启评分','1',2),(279,347,'无需登录','0',1),(280,347,'需要登录','1',2),(281,348,'无需登录','0',1),(282,348,'需要登录','1',2),(283,350,'无需登陆','0',1),(284,350,'需要登录','1',2),(285,351,'自动识别跳转','1',1),(286,351,'无需识别跳转','0',2),(287,352,'开启验证码','1',1),(288,352,'关闭验证码','0',2),(289,353,'开启验证码','1',1),(290,353,'关闭验证码','0',2),(291,354,'开启验证码','1',1),(292,354,'关闭验证码','0',2),(293,355,'开启验证码','1',1),(294,355,'关闭验证码','0',2),(295,356,'开启验证码','1',1),(296,356,'关闭验证码','0',2),(297,357,'开启验证码','1',1),(298,357,'关闭验证码','0',2),(299,358,'开启验证码','1',1),(300,358,'关闭验证码','0',2),(301,360,'普通验证码','1',1),(302,360,'计算验证码','2',2),(303,360,'问答验证码','3',3),(304,361,'普通验证码','1',1),(305,361,'计算验证码','2',2),(306,361,'问答验证码','3',3),(307,362,'普通验证码','1',1),(308,362,'计算验证码','2',2),(309,362,'问答验证码','3',3),(310,363,'普通验证码','1',1),(311,363,'计算验证码','2',2),(312,363,'问答验证码','3',3),(313,364,'普通验证码','1',1),(314,364,'计算验证码','2',2),(315,364,'问答验证码','3',3),(316,365,'普通验证码','1',1),(317,365,'计算验证码','2',2),(318,365,'问答验证码','3',3),(319,366,'普通验证码','1',1),(320,366,'计算验证码','2',2),(321,366,'问答验证码','3',3),(322,367,'普通验证码','1',1),(323,367,'计算验证码','2',2),(324,367,'问答验证码','3',3),(325,379,'不创建HTML','0',1),(326,379,'创建HTML','1',2),(327,380,'不创建HTML','0',1),(328,380,'创建HTML','1',2),(329,381,'不创建HTML','0',1),(330,381,'创建HTML','1',2),(331,382,'主/子域名单享','0',1),(332,382,'主/子域名共享','1',2),(333,383,'关闭站群模式','0',1),(334,383,'开启站群模式','1',2),(335,28,'关闭发送日志','0',1),(336,386,'只对比章节名','1',1),(337,386,'对比内容和名字','2',2),(340,28,'开启发送日志','1',2),(341,389,'redis缓存','redis',2),(342,389,'memcached缓存','memcached',3),(343,390,'文件缓存','file',1),(344,390,'redis缓存','redis',2),(345,390,'memcached缓存','memcached',3),(346,393,'关闭阅读记录','0',1),(347,393,'开启阅读记录','1',2),(348,394,'强制绑定账号','1',1),(349,394,'自动创建账号','0',2),(350,395,'关闭域名认证','0',1),(351,395,'开启域名认证','1',2),(352,396,'HTTP','http',1),(353,396,'HTTPS','https',2),(354,398,'关闭上传','0',1),(355,398,'开启上传','1',2),(356,403,'直接跳出','1',1),(357,403,'进入详情页','2',2),(358,404,'开启检查','1',1),(359,404,'关闭检查','0',2),(360,405,'开启检查','1',1),(361,405,'关闭检查','0',2),(362,406,'开启顶踩','1',1),(363,406,'关闭顶踩','0',2),(364,407,'需要登录','1',1),(365,407,'无需登录','0',2),(366,409,'开启评分','1',1),(367,409,'关闭评分','0',2),(368,410,'需要登录','1',1),(369,410,'无需登录','0',2),(370,412,'开启评论','1',1),(371,412,'关闭评论','0',2),(372,413,'需要登录','1',1),(373,413,'无需登录','0',2),(374,13,'普通模式','3',3),(375,13,'兼容模式','4',4),(376,13,'PATHINFO模式','5',5),(377,13,'REWRITE模式','6',6),(378,420,'保存到数据库','1',1),(379,420,'保存到文件','2',2),(380,422,'关闭自动生成','0',1),(381,422,'开启自动生成','1',2),(382,427,'列表模式','1',1),(383,427,'题主模式','2',2),(384,430,'最新','replay_time',1),(385,430,'热门','replay_ding',2),(386,430,'顺序','replay_id',3),(387,431,'不清空','0',1),(388,431,'只清空推荐票','1',2),(389,431,'只清空月票','2',3),(390,431,'清空推荐票和月票','3',4),(391,432,'收入直接转入作者账户','1',1),(392,432,'人工结算模式','2',2),(393,433,'财务申请处理后资金转入作者账户','1',1),(394,433,'无资金流转只发送消息提示','2',2),(395,361,'邮件验证码','4',4),(396,361,'短信验证码','5',5),(399,434,'关闭发送日志','0',1),(400,434,'开启发送日志','1',2),(401,435,'关闭预警通知','0',1),(402,435,'开启预警通知','1',2),(403,436,'请选择','',1),(404,436,'WebHook','webhook',2),(405,436,'邮件地址','email',3),(406,436,'手机短信','tel',4),(407,437,'请选择','',1),(408,437,'钉钉','dingding_hook',2),(409,437,'企业微信','weixinwork_hook',3),(410,439,'后台登录','warning_admin_login',1),(411,439,'代码报错','warning_code_eroor',2);

/*Table structure for table `wm_diy_diy` */

DROP TABLE IF EXISTS `wm_diy_diy`;

CREATE TABLE `wm_diy_diy` (
  `diy_id` int(4) NOT NULL AUTO_INCREMENT,
  `diy_status` tinyint(1) DEFAULT '1' COMMENT '自定义页面的状态',
  `diy_read` int(4) DEFAULT '0' COMMENT '阅读量',
  `diy_name` varchar(20) NOT NULL COMMENT '自定义页面名字',
  `diy_pinyin` varchar(20) NOT NULL COMMENT '拼音',
  `diy_title` varchar(100) DEFAULT NULL COMMENT '标题',
  `diy_key` varchar(120) DEFAULT NULL COMMENT '关键字',
  `diy_desc` varchar(150) DEFAULT NULL COMMENT '描述',
  `diy_content` varchar(2000) NOT NULL COMMENT '内容',
  `diy_time` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `diy_ctempid` int(4) DEFAULT '0' COMMENT '专题的模版id',
  PRIMARY KEY (`diy_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='自定义页面表';

/*Data for the table `wm_diy_diy` */

/*Table structure for table `wm_editor` */

DROP TABLE IF EXISTS `wm_editor`;

CREATE TABLE `wm_editor` (
  `editor_id` int(11) NOT NULL AUTO_INCREMENT,
  `editor_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否启用:1=是,0=否',
  `editor_uid` int(11) NOT NULL COMMENT '用户id',
  `editor_name` varchar(20) NOT NULL COMMENT '编辑名字',
  `editor_realname` varchar(20) NOT NULL COMMENT '编辑真实姓名',
  `editor_desc` varchar(200) NOT NULL COMMENT '编辑简介',
  `editor_qq` varchar(20) DEFAULT NULL COMMENT '编辑qq',
  `editor_weixin` varchar(50) DEFAULT NULL COMMENT '编辑微信号',
  `editor_tel` varchar(11) DEFAULT NULL COMMENT '编辑手机号',
  `editor_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`editor_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='编辑表';

/*Data for the table `wm_editor` */

/*Table structure for table `wm_editor_bind` */

DROP TABLE IF EXISTS `wm_editor_bind`;

CREATE TABLE `wm_editor_bind` (
  `bind_id` int(11) NOT NULL AUTO_INCREMENT,
  `bind_module` varchar(20) NOT NULL DEFAULT 'novel' COMMENT '模块',
  `bind_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '编辑类型:1=主编,2=责编',
  `bind_group_id` int(11) NOT NULL COMMENT '分组id',
  `bind_editor_id` int(11) NOT NULL DEFAULT '0' COMMENT '编辑id',
  `bind_time` int(11) NOT NULL COMMENT '绑定时间',
  PRIMARY KEY (`bind_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='编辑分组绑定';

/*Data for the table `wm_editor_bind` */

/*Table structure for table `wm_editor_group` */

DROP TABLE IF EXISTS `wm_editor_group`;

CREATE TABLE `wm_editor_group` (
  `group_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '分组id',
  `group_module` varchar(20) NOT NULL DEFAULT 'novel' COMMENT '分组模块',
  `group_name` varchar(20) NOT NULL COMMENT '分组名字',
  `group_desc` varchar(100) NOT NULL COMMENT '分组描述',
  `group_order` tinyint(4) NOT NULL DEFAULT '9' COMMENT '分组排序',
  `group_time` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='编辑分组';

/*Data for the table `wm_editor_group` */

/*Table structure for table `wm_editor_works` */

DROP TABLE IF EXISTS `wm_editor_works`;

CREATE TABLE `wm_editor_works` (
  `works_id` int(11) NOT NULL AUTO_INCREMENT,
  `works_module` varchar(20) NOT NULL DEFAULT 'novel' COMMENT '模块',
  `works_bind_id` int(11) NOT NULL COMMENT '分组绑定id',
  `works_cid` int(11) NOT NULL COMMENT '作品id',
  `works_editor_id` int(11) NOT NULL DEFAULT '0' COMMENT '编辑id',
  `works_time` int(11) NOT NULL COMMENT '申请时间',
  PRIMARY KEY (`works_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='编辑作品关系表';

/*Data for the table `wm_editor_works` */

/*Table structure for table `wm_fans_module` */

DROP TABLE IF EXISTS `wm_fans_module`;

CREATE TABLE `wm_fans_module` (
  `fans_id` int(4) NOT NULL AUTO_INCREMENT,
  `fans_module` varchar(20) DEFAULT NULL COMMENT '所属模块',
  `fans_user_id` int(4) NOT NULL COMMENT '粉丝id',
  `fans_cid` int(4) DEFAULT NULL COMMENT '内容id',
  `fans_exp` int(4) DEFAULT '0' COMMENT '粉丝经验值',
  `fans_addtime` int(4) DEFAULT NULL COMMENT '关注时间',
  PRIMARY KEY (`fans_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块内容粉丝表。';

/*Data for the table `wm_fans_module` */

/*Table structure for table `wm_fans_module_consume` */

DROP TABLE IF EXISTS `wm_fans_module_consume`;

CREATE TABLE `wm_fans_module_consume` (
  `consume_id` int(4) NOT NULL AUTO_INCREMENT,
  `consume_module` varchar(20) DEFAULT NULL COMMENT '消费的模块',
  `consume_user_id` int(4) DEFAULT NULL COMMENT '用户id',
  `consume_cid` int(4) DEFAULT NULL COMMENT '内容id',
  `consume_gold1_exp` decimal(10,2) DEFAULT '0.00' COMMENT '粉丝经验值当前累积消费金币1',
  `consume_gold2_exp` decimal(10,2) DEFAULT '0.00' COMMENT '粉丝经验值当前累积消费金币2',
  `consume_gold1_ticket` decimal(10,2) DEFAULT '0.00' COMMENT '票类当前阶段累积的消费金币1',
  `consume_gold2_ticket` decimal(10,2) DEFAULT '0.00' COMMENT '票类当前阶段累积的消费金币2',
  `consume_gold1` decimal(15,2) DEFAULT '0.00' COMMENT '总共消费的金币1',
  `consume_gold2` decimal(15,2) DEFAULT '0.00' COMMENT '总共消费的金币2',
  PRIMARY KEY (`consume_id`),
  KEY `user_index` (`consume_module`,`consume_user_id`,`consume_cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='模块内容粉丝消费记录表';

/*Data for the table `wm_fans_module_consume` */

/*Table structure for table `wm_fans_module_level` */

DROP TABLE IF EXISTS `wm_fans_module_level`;

CREATE TABLE `wm_fans_module_level` (
  `level_id` int(4) NOT NULL AUTO_INCREMENT,
  `level_module` varchar(20) NOT NULL COMMENT '等级所属模块',
  `level_cid` int(4) NOT NULL DEFAULT '0' COMMENT '0为所有内容，否则为单独定制的粉丝等级。',
  `level_name` varchar(20) NOT NULL COMMENT '等级名字',
  `level_start` int(4) NOT NULL DEFAULT '0' COMMENT '等级开始经验',
  `level_end` int(4) NOT NULL DEFAULT '0' COMMENT '等级结束经验',
  `level_order` int(41) NOT NULL DEFAULT '0' COMMENT '等级排序',
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='模块内容粉丝等级表';

/*Data for the table `wm_fans_module_level` */

insert  into `wm_fans_module_level`(`level_id`,`level_module`,`level_cid`,`level_name`,`level_start`,`level_end`,`level_order`) values (1,'novel',0,'练气弟子',0,500,1),(2,'novel',0,'筑基弟子',500,1000,2),(3,'novel',0,'外门弟子',1000,2000,3),(4,'novel',0,'内门弟子',2000,4000,4),(5,'novel',0,'真传弟子',4000,6000,5),(6,'novel',0,'虚丹外门执事',6000,10000,6),(7,'novel',0,'金丹内门执事',10000,20000,7),(8,'novel',0,'元婴护法',20000,30000,8),(9,'novel',0,'出窍长老',30000,50000,9),(10,'novel',0,'化神副门主',50000,60000,10),(11,'novel',0,'合体门主',60000,70000,11),(12,'novel',0,'洞虚掌门',70000,80000,12),(13,'novel',0,'大乘盟主',80000,100000,13);

/*Table structure for table `wm_finance_apply` */

DROP TABLE IF EXISTS `wm_finance_apply`;

CREATE TABLE `wm_finance_apply` (
  `apply_id` int(4) NOT NULL AUTO_INCREMENT,
  `apply_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为待审核，1为已处理，2为未通过',
  `apply_module` varchar(20) DEFAULT NULL COMMENT '结算来源模块，可以为空',
  `apply_cid` int(4) NOT NULL DEFAULT '0' COMMENT '模块内容id',
  `apply_month` int(4) NOT NULL COMMENT '结算的月份',
  `apply_time` int(4) NOT NULL COMMENT '结算的时间',
  `apply_manager_id` int(4) NOT NULL COMMENT '结算申请人',
  `apply_total` decimal(10,2) NOT NULL COMMENT '结算申请总金额',
  `apply_bonus` decimal(10,2) DEFAULT NULL COMMENT '奖励金额',
  `apply_bonus_remark` varchar(50) DEFAULT NULL COMMENT '奖励备注',
  `apply_deduct` decimal(10,2) DEFAULT NULL COMMENT '惩罚备注',
  `apply_deduct_remark` varchar(50) DEFAULT NULL COMMENT '惩罚备注',
  `apply_real` decimal(10,2) NOT NULL COMMENT '实际到账金额',
  `apply_real_money` decimal(10,2) DEFAULT '0.00' COMMENT '实际到账人民币,为0表示模式1',
  `apply_remark` varchar(50) DEFAULT NULL COMMENT '结算申请备注',
  `apply_to_user_id` int(4) NOT NULL DEFAULT '0' COMMENT '结算申请给哪个用户',
  `apply_handle_manager_id` int(4) NOT NULL DEFAULT '0' COMMENT '处理申请的管理员id',
  `apply_handle_remark` varchar(50) DEFAULT NULL COMMENT '处理申请的备注',
  `apply_handle_time` int(4) NOT NULL DEFAULT '0' COMMENT '处理申请的时间',
  PRIMARY KEY (`apply_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='财务申请记录表';

/*Data for the table `wm_finance_apply` */

insert  into `wm_finance_apply`(`apply_id`,`apply_status`,`apply_module`,`apply_cid`,`apply_month`,`apply_time`,`apply_manager_id`,`apply_total`,`apply_bonus`,`apply_bonus_remark`,`apply_deduct`,`apply_deduct_remark`,`apply_real`,`apply_real_money`,`apply_remark`,`apply_to_user_id`,`apply_handle_manager_id`,`apply_handle_remark`,`apply_handle_time`) values (1,1,'novel',1,202107,1627716407,1,'0.00','200.00','','0.00','','200.00','20.00','小说《测试》 2021-07 的财务结算申请！',1,1,'',1627718702);

/*Table structure for table `wm_finance_level` */

DROP TABLE IF EXISTS `wm_finance_level`;

CREATE TABLE `wm_finance_level` (
  `level_id` int(4) NOT NULL AUTO_INCREMENT,
  `level_money` int(1) NOT NULL DEFAULT '0' COMMENT '充值的金额',
  `level_real` int(1) NOT NULL DEFAULT '0' COMMENT '折扣后应付的价格',
  `level_reward_gold2` int(1) NOT NULL DEFAULT '0' COMMENT '赠送的金币2',
  PRIMARY KEY (`level_id`),
  KEY `money_index` (`level_money`)
) ENGINE=MyISAM AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 COMMENT='充值等级表';

/*Data for the table `wm_finance_level` */

insert  into `wm_finance_level`(`level_id`,`level_money`,`level_real`,`level_reward_gold2`) values (1,10,10,0),(2,20,20,0),(3,50,50,0),(4,100,100,0),(5,200,200,0),(7,500,500,100),(8,80,80,0),(9,150,150,0);

/*Table structure for table `wm_finance_order_cash` */

DROP TABLE IF EXISTS `wm_finance_order_cash`;

CREATE TABLE `wm_finance_order_cash` (
  `cash_id` int(4) NOT NULL AUTO_INCREMENT,
  `cash_status` tinyint(1) DEFAULT '0' COMMENT '0为申请中，1为已经处理，2为已拒绝',
  `cash_user_id` int(4) NOT NULL COMMENT '申请用户',
  `cash_money` decimal(10,2) NOT NULL COMMENT '提现金额',
  `cash_real` decimal(10,2) NOT NULL COMMENT '实际到账',
  `cash_cost` tinyint(3) NOT NULL DEFAULT '0' COMMENT '提现手续费',
  `cash_remark` varchar(200) DEFAULT NULL COMMENT '备注信息',
  `cash_time` int(4) DEFAULT '0' COMMENT '申请时间',
  `cash_handletime` int(4) DEFAULT '0' COMMENT '处理时间',
  PRIMARY KEY (`cash_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='提现申请订单表';

/*Data for the table `wm_finance_order_cash` */

/*Table structure for table `wm_finance_order_charge` */

DROP TABLE IF EXISTS `wm_finance_order_charge`;

CREATE TABLE `wm_finance_order_charge` (
  `charge_id` int(4) NOT NULL AUTO_INCREMENT,
  `charge_sn` varchar(60) NOT NULL COMMENT '本站充值订单号',
  `charge_paysn` varchar(60) DEFAULT NULL COMMENT '第三方充值订单号',
  `charge_status` tinyint(1) DEFAULT '0' COMMENT '订单状态，0为待付款，1为已付款',
  `charge_type` varchar(20) NOT NULL COMMENT '充值方式。card卡密充值',
  `charge_user_id` int(4) NOT NULL DEFAULT '0' COMMENT '充值用户',
  `charge_tuser_id` int(4) NOT NULL DEFAULT '0' COMMENT '好友的id',
  `charge_money` decimal(10,2) DEFAULT NULL COMMENT '充值金额',
  `charge_gold2` decimal(10,2) DEFAULT NULL COMMENT '获得金币2',
  `charge_first` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '是否有首冲奖励',
  `charge_reward` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '是否有充值奖励',
  `charge_addtime` int(4) DEFAULT '0' COMMENT '下单时间',
  `charge_paytime` int(4) DEFAULT '0' COMMENT '支付时间',
  `charge_remark` varchar(200) DEFAULT NULL COMMENT '备注',
  PRIMARY KEY (`charge_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='充值记录';

/*Data for the table `wm_finance_order_charge` */

/*Table structure for table `wm_finance_report` */

DROP TABLE IF EXISTS `wm_finance_report`;

CREATE TABLE `wm_finance_report` (
  `report_id` int(4) NOT NULL AUTO_INCREMENT,
  `report_settlement` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否计算:0=未结算,1=已结算',
  `report_module` varchar(20) DEFAULT NULL COMMENT '模块',
  `report_type` varchar(20) DEFAULT NULL COMMENT '类型',
  `report_user_id` int(4) NOT NULL DEFAULT '0' COMMENT '消费或者收入的用户id',
  `report_cid` varchar(35) NOT NULL DEFAULT '0' COMMENT '购买的内容id或者来源id',
  `report_gold1` decimal(10,3) DEFAULT '0.000' COMMENT '需要结算金币1的数量',
  `report_gold2` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '需要结算金币2的数量',
  `report_remark` varchar(100) DEFAULT NULL COMMENT '备注信息',
  `report_time` int(4) NOT NULL DEFAULT '0' COMMENT '入账时间',
  `report_settlement_id` int(4) NOT NULL DEFAULT '0' COMMENT '结算员ID',
  `report_settlement_time` int(4) NOT NULL DEFAULT '0' COMMENT '结算时间',
  PRIMARY KEY (`report_id`),
  KEY `module_index` (`report_module`),
  KEY `type_index` (`report_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='销售报表';

/*Data for the table `wm_finance_report` */

/*Table structure for table `wm_finance_report_list` */

DROP TABLE IF EXISTS `wm_finance_report_list`;

CREATE TABLE `wm_finance_report_list` (
  `list_id` int(4) NOT NULL AUTO_INCREMENT,
  `list_report_id` int(4) NOT NULL DEFAULT '0' COMMENT '结算报表id',
  `list_order_id` int(4) NOT NULL DEFAULT '0' COMMENT '结算订单id',
  PRIMARY KEY (`list_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='结算订单关联记录表';

/*Data for the table `wm_finance_report_list` */

/*Table structure for table `wm_finance_report_order` */

DROP TABLE IF EXISTS `wm_finance_report_order`;

CREATE TABLE `wm_finance_report_order` (
  `order_id` int(4) NOT NULL AUTO_INCREMENT,
  `order_gold1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金币1',
  `order_gold2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金币2',
  `order_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '订单金额',
  `order_admin_id` int(4) NOT NULL DEFAULT '0' COMMENT '结算员',
  `order_time` int(4) NOT NULL DEFAULT '0' COMMENT '结算结算',
  PRIMARY KEY (`order_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='结算订单记录';

/*Data for the table `wm_finance_report_order` */

/*Table structure for table `wm_flash_flash` */

DROP TABLE IF EXISTS `wm_flash_flash`;

CREATE TABLE `wm_flash_flash` (
  `flash_id` int(4) NOT NULL AUTO_INCREMENT,
  `flash_status` tinyint(1) DEFAULT '1' COMMENT '状态',
  `type_id` int(4) NOT NULL DEFAULT '0' COMMENT '幻灯片分组',
  `flash_order` int(4) NOT NULL COMMENT '排序',
  `flash_module` varchar(20) NOT NULL COMMENT '属于哪个模块',
  `flash_pid` int(11) NOT NULL COMMENT '页面id',
  `flash_title` varchar(20) NOT NULL COMMENT '标题',
  `flash_info` varchar(100) DEFAULT NULL COMMENT '简介',
  `flash_desc` varchar(200) NOT NULL COMMENT '描述',
  `flash_url` varchar(200) NOT NULL COMMENT '跳转地址',
  `flash_img` varchar(500) DEFAULT NULL COMMENT '幻灯片图标',
  `flash_time` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`flash_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

/*Data for the table `wm_flash_flash` */

/*Table structure for table `wm_flash_type` */

DROP TABLE IF EXISTS `wm_flash_type`;

CREATE TABLE `wm_flash_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_topid` int(4) NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '子栏目id',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_pinyin` varchar(50) DEFAULT NULL COMMENT '类型拼音',
  `type_order` int(4) NOT NULL DEFAULT '0' COMMENT '分类排序',
  `type_info` varchar(100) DEFAULT NULL COMMENT '分类备注',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='幻灯片表';

/*Data for the table `wm_flash_type` */

/*Table structure for table `wm_link_click` */

DROP TABLE IF EXISTS `wm_link_click`;

CREATE TABLE `wm_link_click` (
  `click_id` int(4) NOT NULL AUTO_INCREMENT,
  `click_lid` int(4) NOT NULL,
  `click_type` varchar(10) NOT NULL COMMENT '进还是出',
  `click_ua` varchar(250) DEFAULT NULL COMMENT 'UA信息',
  `click_ip` varchar(15) DEFAULT NULL COMMENT '点击IP',
  `click_adress` varchar(30) DEFAULT NULL COMMENT '地理位置',
  `click_browser` varchar(30) DEFAULT NULL COMMENT '浏览器',
  `click_browser_ver` varchar(30) DEFAULT NULL COMMENT '浏览器版本',
  `click_system` varchar(250) DEFAULT NULL COMMENT '系统类型',
  `click_system_ver` varchar(30) DEFAULT NULL COMMENT '系统版本',
  `click_time` int(4) NOT NULL COMMENT '点击时间',
  PRIMARY KEY (`click_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='友链点击记录表';

/*Data for the table `wm_link_click` */

/*Table structure for table `wm_link_link` */

DROP TABLE IF EXISTS `wm_link_link`;

CREATE TABLE `wm_link_link` (
  `link_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_id` int(4) NOT NULL COMMENT '分类id',
  `link_name` varchar(20) NOT NULL COMMENT '友链名字',
  `link_cname` varchar(5) DEFAULT NULL COMMENT '友链简称',
  `link_pinyin` varchar(20) DEFAULT NULL COMMENT '拼音',
  `link_ico` varchar(120) DEFAULT NULL COMMENT '图标',
  `link_order` int(4) NOT NULL DEFAULT '99' COMMENT '排序',
  `link_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '状态',
  `link_show` tinyint(1) DEFAULT '1' COMMENT '1为显示跳转链接，0为直链',
  `link_url` varchar(120) NOT NULL COMMENT '友链地址',
  `link_fixed` tinyint(1) NOT NULL DEFAULT '0' COMMENT '固链',
  `link_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `link_jointime` int(4) NOT NULL DEFAULT '0' COMMENT '加入时间',
  `link_in_jump` varchar(200) DEFAULT NULL COMMENT '友链进入跳转连接',
  `link_info` varchar(50) DEFAULT NULL COMMENT '友链简介',
  `link_qq` varchar(20) DEFAULT NULL COMMENT '联系QQ',
  `link_read` int(4) NOT NULL DEFAULT '0' COMMENT '阅读量',
  `link_ding` int(4) NOT NULL DEFAULT '0' COMMENT '顶',
  `link_cai` int(4) NOT NULL DEFAULT '0' COMMENT '踩',
  `link_lastintime` int(4) NOT NULL DEFAULT '0' COMMENT '最后点入',
  `link_lastouttime` int(4) NOT NULL DEFAULT '0' COMMENT '最后点出',
  `link_outday` int(4) NOT NULL DEFAULT '0' COMMENT '日点出',
  `link_outweek` int(4) NOT NULL DEFAULT '0' COMMENT '周点出',
  `link_outmonth` int(4) NOT NULL DEFAULT '0' COMMENT '月点出',
  `link_outyear` int(4) DEFAULT '0' COMMENT '年点出',
  `link_outsum` int(4) NOT NULL DEFAULT '0' COMMENT '总点处',
  `link_inday` int(4) NOT NULL DEFAULT '0' COMMENT '日点入',
  `link_inweek` int(4) NOT NULL DEFAULT '0' COMMENT '周点入',
  `link_inmonth` int(4) NOT NULL DEFAULT '0' COMMENT '月点入',
  `link_inyear` int(4) DEFAULT '0' COMMENT '年点入',
  `link_insum` int(4) NOT NULL DEFAULT '0' COMMENT '总点入',
  PRIMARY KEY (`link_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='友链表';

/*Data for the table `wm_link_link` */

insert  into `wm_link_link`(`link_id`,`type_id`,`link_name`,`link_cname`,`link_pinyin`,`link_ico`,`link_order`,`link_status`,`link_show`,`link_url`,`link_fixed`,`link_rec`,`link_jointime`,`link_in_jump`,`link_info`,`link_qq`,`link_read`,`link_ding`,`link_cai`,`link_lastintime`,`link_lastouttime`,`link_outday`,`link_outweek`,`link_outmonth`,`link_outyear`,`link_outsum`,`link_inday`,`link_inweek`,`link_inmonth`,`link_inyear`,`link_insum`) values (1,2,'WMCMS','','','',99,1,0,'http://www.weimengcms.com',1,1,1494685926,'','','',3,0,0,0,0,0,0,0,0,0,0,0,0,0,0),(2,2,'使用帮助','','','',99,1,1,'http://help.weimengcms.com',0,0,1552653211,'','','',0,0,0,0,0,0,0,0,0,0,0,0,0,0,0);

/*Table structure for table `wm_link_type` */

DROP TABLE IF EXISTS `wm_link_type`;

CREATE TABLE `wm_link_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_topid` int(4) NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '子栏目id',
  `type_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐分类',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_cname` varchar(10) DEFAULT NULL COMMENT '类型简称',
  `type_pinyin` varchar(50) DEFAULT NULL COMMENT '类型拼音',
  `type_order` int(2) NOT NULL COMMENT '排序',
  `type_ico` varchar(200) DEFAULT NULL COMMENT '分类图标',
  `type_info` varchar(100) DEFAULT NULL COMMENT '分类信息',
  `type_in_jump` varchar(200) DEFAULT NULL COMMENT '该分类友链点入跳转连接',
  `type_tempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类页模版',
  `type_ctempid` int(4) NOT NULL DEFAULT '0' COMMENT '展示页模版',
  `type_title` varchar(80) DEFAULT NULL COMMENT '页面标题',
  `type_key` varchar(100) DEFAULT NULL COMMENT '页面关键字',
  `type_desc` varchar(120) DEFAULT NULL COMMENT '页面描述',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 COMMENT='友链分类表';

/*Data for the table `wm_link_type` */

insert  into `wm_link_type`(`type_id`,`type_topid`,`type_pid`,`type_rec`,`type_name`,`type_cname`,`type_pinyin`,`type_order`,`type_ico`,`type_info`,`type_in_jump`,`type_tempid`,`type_ctempid`,`type_title`,`type_key`,`type_desc`) values (2,0,'0',0,'电脑友链','','',0,'','','',0,0,'','','');

/*Table structure for table `wm_manager_login` */

DROP TABLE IF EXISTS `wm_manager_login`;

CREATE TABLE `wm_manager_login` (
  `login_id` int(4) NOT NULL AUTO_INCREMENT,
  `manager_id` int(4) NOT NULL COMMENT '管理员id',
  `login_status` tinyint(1) DEFAULT NULL COMMENT '0为登录失败，1为登录成功，2为密码错误',
  `login_remark` varchar(100) DEFAULT NULL COMMENT '备注详情',
  `login_ip` varchar(150) NOT NULL COMMENT '登录ip',
  `login_time` int(4) NOT NULL COMMENT '登录时间',
  `login_ua` varchar(1000) NOT NULL COMMENT '登录UA',
  `login_browser` varchar(250) DEFAULT NULL COMMENT '登录浏览器',
  PRIMARY KEY (`login_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员登录记录';

/*Data for the table `wm_manager_login` */

/*Table structure for table `wm_manager_manager` */

DROP TABLE IF EXISTS `wm_manager_manager`;

CREATE TABLE `wm_manager_manager` (
  `manager_id` int(11) NOT NULL AUTO_INCREMENT,
  `manager_status` int(1) NOT NULL DEFAULT '1' COMMENT '0为禁用,1为正常',
  `manager_cid` int(4) NOT NULL DEFAULT '0' COMMENT '管理员权限ID',
  `manager_name` varchar(20) NOT NULL COMMENT '管理员账号',
  `manager_psw` varchar(50) NOT NULL COMMENT '管理员密码',
  `manager_salt` varchar(50) DEFAULT NULL COMMENT '管理员密码盐',
  `manager_lastip` varchar(150) DEFAULT NULL COMMENT '最后登录ip',
  `manager_lasttime` int(4) NOT NULL DEFAULT '0' COMMENT '最后登陆',
  PRIMARY KEY (`manager_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='管理员账号表';

/*Data for the table `wm_manager_manager` */

insert  into `wm_manager_manager`(`manager_id`,`manager_status`,`manager_cid`,`manager_name`,`manager_psw`,`manager_salt`,`manager_lastip`,`manager_lasttime`) values (1,1,0,'admin','d4b6232e4aa20cd032009cbb79b3452507a24830','a8e52ffc28e3300ee098aec5b1415b6cea5befcb','127.0.0.1',1661663548),(4,1,1,'sdasd','b8dbfcaea467bc655229df4a370cabe063f7daa4',NULL,'127.0.0.1',1499091969);

/*Table structure for table `wm_manager_operation` */

DROP TABLE IF EXISTS `wm_manager_operation`;

CREATE TABLE `wm_manager_operation` (
  `operation_id` int(4) NOT NULL AUTO_INCREMENT,
  `operation_manager_id` int(4) DEFAULT '0' COMMENT '操作的管理员',
  `operation_module` varchar(20) DEFAULT NULL COMMENT '操作的模块',
  `operation_table` varchar(50) DEFAULT NULL COMMENT '操作的表',
  `operation_type` varchar(20) DEFAULT NULL COMMENT '操作的类型，insert，updata,delete',
  `operation_data` text COMMENT '操作的新数据',
  `operation_where` text COMMENT '操作数据的条件',
  `operation_backdata` text COMMENT '操作的镜像原始数据',
  `operation_remark` varchar(500) DEFAULT NULL COMMENT '操作的备注信息',
  `operation_time` int(4) DEFAULT '0' COMMENT '操作的时间',
  PRIMARY KEY (`operation_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员操作记录表';

/*Data for the table `wm_manager_operation` */

/*Table structure for table `wm_manager_recycle` */

DROP TABLE IF EXISTS `wm_manager_recycle`;

CREATE TABLE `wm_manager_recycle` (
  `recycle_id` int(4) NOT NULL AUTO_INCREMENT,
  `recycle_manager_id` int(4) NOT NULL DEFAULT '0' COMMENT '管理员的id',
  `recycle_module` varchar(20) NOT NULL COMMENT '操作的是哪个模块',
  `recycle_data_id` int(4) NOT NULL DEFAULT '0' COMMENT '删除的数据id',
  `recycle_title` varchar(100) DEFAULT NULL COMMENT '删除数据的标题',
  `recycle_time` int(4) DEFAULT '0' COMMENT '删除的时间',
  PRIMARY KEY (`recycle_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员删除数据回收站';

/*Data for the table `wm_manager_recycle` */

/*Table structure for table `wm_manager_request` */

DROP TABLE IF EXISTS `wm_manager_request`;

CREATE TABLE `wm_manager_request` (
  `request_id` int(4) NOT NULL AUTO_INCREMENT,
  `request_manager_id` int(4) NOT NULL COMMENT '管理员的id',
  `request_file` varchar(30) NOT NULL COMMENT '访问的控制器文件',
  `request_type` varchar(20) NOT NULL DEFAULT 'GET' COMMENT '访问的类型',
  `request_ip` varchar(20) DEFAULT NULL COMMENT '访问的ip',
  `request_time` int(4) DEFAULT '0' COMMENT '访问的时间',
  `request_get` text COMMENT 'GET请求的参数',
  `request_post` text COMMENT 'POST请求的参数',
  PRIMARY KEY (`request_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='管理员请求记录';

/*Data for the table `wm_manager_request` */

/*Table structure for table `wm_message_message` */

DROP TABLE IF EXISTS `wm_message_message`;

CREATE TABLE `wm_message_message` (
  `message_id` int(4) NOT NULL AUTO_INCREMENT,
  `message_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未读',
  `message_content` varchar(100) NOT NULL COMMENT '留言内容',
  `message_time` int(4) NOT NULL COMMENT '留言时间',
  `message_ip` varchar(15) DEFAULT NULL COMMENT '留言IP',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='留言表';

/*Data for the table `wm_message_message` */

/*Table structure for table `wm_novel_author_said` */

DROP TABLE IF EXISTS `wm_novel_author_said`;

CREATE TABLE `wm_novel_author_said` (
  `said_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `novel_id` int(11) NOT NULL COMMENT '小说ID',
  `chapter_id` int(11) NOT NULL COMMENT '章节ID',
  `said_content` text COMMENT '内容',
  `said_uptime` int(4) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`said_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='作者的话';

/*Data for the table `wm_novel_author_said` */

/*Table structure for table `wm_novel_chapter` */

DROP TABLE IF EXISTS `wm_novel_chapter`;

CREATE TABLE `wm_novel_chapter` (
  `chapter_id` int(11) NOT NULL AUTO_INCREMENT,
  `chapter_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0为审核中,1为正常,2为不通过',
  `chapter_islogin` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要登录才能查看',
  `chapter_isvip` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要会员,0为不需要',
  `chapter_ispay` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要付费',
  `chapter_istxt` tinyint(1) NOT NULL DEFAULT '2' COMMENT '2为入库，1为是txt文件',
  `chapter_number` int(1) DEFAULT '0' COMMENT '章节的字数',
  `chapter_name` varchar(100) DEFAULT NULL COMMENT '章节名',
  `chapter_nid` int(4) NOT NULL DEFAULT '0' COMMENT '书籍id',
  `chapter_vid` int(4) NOT NULL DEFAULT '1' COMMENT '分卷id默认为1',
  `chapter_cid` int(4) NOT NULL DEFAULT '0' COMMENT '内容id',
  `chapter_order` int(4) NOT NULL DEFAULT '999' COMMENT '排序',
  `chapter_path` varchar(250) DEFAULT NULL COMMENT '章节txt完整保存路径',
  `chapter_time` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间年月日时分秒',
  PRIMARY KEY (`chapter_id`),
  KEY `nid_vid_time_Index` (`chapter_nid`,`chapter_vid`,`chapter_time`),
  KEY `nid_time_Index` (`chapter_nid`,`chapter_time`),
  KEY `nid_index` (`chapter_nid`),
  KEY `time_index` (`chapter_time`),
  KEY `cid_index` (`chapter_cid`),
  KEY `status_index` (`chapter_status`),
  KEY `order_index` (`chapter_order`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说章节索引表';

/*Data for the table `wm_novel_chapter` */

/*Table structure for table `wm_novel_content` */

DROP TABLE IF EXISTS `wm_novel_content`;

CREATE TABLE `wm_novel_content` (
  `content_id` int(11) NOT NULL AUTO_INCREMENT,
  `content` mediumtext NOT NULL COMMENT '内容',
  PRIMARY KEY (`content_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='章节内容表';

/*Data for the table `wm_novel_content` */

/*Table structure for table `wm_novel_novel` */

DROP TABLE IF EXISTS `wm_novel_novel`;

CREATE TABLE `wm_novel_novel` (
  `novel_id` int(11) NOT NULL AUTO_INCREMENT,
  `author_id` int(4) NOT NULL DEFAULT '0' COMMENT '作者id',
  `novel_name` varchar(100) NOT NULL COMMENT '小说名',
  `novel_wordname` varchar(50) DEFAULT NULL COMMENT '全文字书名',
  `novel_pinyin` varchar(50) NOT NULL COMMENT '拼音',
  `novel_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0为审核,1为正常',
  `novel_copyright` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为无版权，1为签约销售，2为买断版权',
  `novel_sign_id` int(4) NOT NULL DEFAULT '0' COMMENT '签约等级的id',
  `novel_sell` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否允许上架出售',
  `novel_process` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为连载，2完成，3断更',
  `novel_type` int(1) NOT NULL DEFAULT '1' COMMENT '1为原创首发，2为他站首发',
  `type_id` int(4) NOT NULL COMMENT '类型id',
  `novel_author` varchar(50) NOT NULL COMMENT '作者',
  `novel_cover` varchar(250) NOT NULL COMMENT '封面',
  `novel_comment` varchar(500) DEFAULT NULL COMMENT '编辑点评',
  `novel_info` varchar(2000) NOT NULL COMMENT '小说简介',
  `novel_tags` varchar(80) DEFAULT NULL COMMENT '小说标签',
  `novel_chapter` int(4) NOT NULL DEFAULT '0' COMMENT '总章节数',
  `novel_wordnumber` int(4) NOT NULL DEFAULT '0' COMMENT '总字数',
  `novel_uptime` int(4) NOT NULL DEFAULT '0' COMMENT '最后更新时间年月日时分秒',
  `novel_clicktime` int(4) NOT NULL DEFAULT '0' COMMENT '点击更新日期年月日',
  `novel_score` decimal(2,1) NOT NULL DEFAULT '0.0' COMMENT '评分',
  `novel_ding` int(4) NOT NULL DEFAULT '0' COMMENT '顶',
  `novel_cai` int(4) NOT NULL DEFAULT '0' COMMENT '踩',
  `novel_replay` int(4) NOT NULL DEFAULT '0' COMMENT '评论条数',
  `novel_startcid` int(4) NOT NULL DEFAULT '0' COMMENT '第一章节的id',
  `novel_startcname` varchar(50) NOT NULL DEFAULT '0' COMMENT '第一章节的名字',
  `novel_newcid` int(4) NOT NULL DEFAULT '0' COMMENT '最新章节的id',
  `novel_newcname` varchar(50) NOT NULL DEFAULT '0' COMMENT '最新章节的名字',
  `novel_createtime` int(4) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `novel_todayclick` int(4) NOT NULL DEFAULT '0' COMMENT '今日点击',
  `novel_weekclick` int(4) NOT NULL DEFAULT '0' COMMENT '周点击',
  `novel_monthclick` int(4) NOT NULL DEFAULT '0' COMMENT '本月点击',
  `novel_yearclick` int(4) DEFAULT '0' COMMENT '年点击',
  `novel_allclick` int(4) NOT NULL DEFAULT '0' COMMENT '总点击数',
  `novel_todaycoll` int(4) NOT NULL DEFAULT '0' COMMENT '日收藏',
  `novel_weekcoll` int(4) NOT NULL DEFAULT '0' COMMENT '周收藏',
  `novel_monthcoll` int(4) NOT NULL DEFAULT '0' COMMENT '月收藏',
  `novel_yearcoll` int(4) NOT NULL DEFAULT '0' COMMENT '年收藏',
  `novel_allcoll` int(4) NOT NULL DEFAULT '0' COMMENT '总收藏',
  `novel_colltime` int(4) NOT NULL DEFAULT '0' COMMENT '收藏更新时间',
  `novel_todayrec` int(4) NOT NULL DEFAULT '0' COMMENT '日用户推荐',
  `novel_weekrec` int(4) NOT NULL DEFAULT '0' COMMENT '周用户推荐',
  `novel_monthrec` int(4) NOT NULL DEFAULT '0' COMMENT '月用户推荐',
  `novel_yearrec` int(4) NOT NULL DEFAULT '0' COMMENT '年用户推荐',
  `novel_allrec` int(4) NOT NULL DEFAULT '0' COMMENT '总用户推荐',
  `novel_path` varchar(250) DEFAULT NULL COMMENT '小说完本txt保存地址',
  `novel_rectime` int(4) NOT NULL DEFAULT '0' COMMENT '推荐更新时间',
  PRIMARY KEY (`novel_id`),
  KEY `UPTIME_INDEX` (`novel_uptime`),
  KEY `LIST_INDEX` (`novel_status`,`type_id`),
  KEY `PINYIN_INDEX` (`novel_pinyin`),
  KEY `STATUS_INDEX` (`novel_status`),
  KEY `REPLAY_INDEX` (`novel_replay`),
  KEY `TYPE_INDEX` (`type_id`),
  KEY `SOCRE_INDEX` (`novel_score`),
  KEY `CREATETIME_INDEX` (`novel_createtime`),
  KEY `WORDNUMBER_INDEX` (`novel_wordnumber`),
  KEY `DING_INDEX` (`novel_ding`),
  KEY `CAI_INDEX` (`novel_cai`),
  KEY `ALLCLICK_INDEX` (`novel_allclick`),
  KEY `MONTHCLICK_INDEX` (`novel_monthclick`),
  KEY `WEEKCLICK_INDEX` (`novel_weekclick`),
  KEY `TODAYCLICK_INDEX` (`novel_todayclick`),
  KEY `ALLREC_INDEX` (`novel_allrec`),
  KEY `YEARREC_INDEX` (`novel_yearrec`),
  KEY `MONTHREC_INDEX` (`novel_monthrec`),
  KEY `WEEKREC_INDEX` (`novel_weekrec`),
  KEY `YEARCOLL_INDEX` (`novel_yearcoll`),
  KEY `MONTHCOLL_INDEX` (`novel_monthcoll`),
  KEY `WEEKCOLL_INDEX` (`novel_weekcoll`),
  KEY `TODAYCOLL_INDEX` (`novel_todaycoll`),
  KEY `TODAYREC_INDEX` (`novel_todayrec`),
  KEY `YEARCLICK_INDEX` (`novel_yearclick`),
  KEY `ALLCOLL_INDEX` (`novel_allcoll`),
  KEY `PRE_NEXT_INDEX` (`type_id`,`novel_id`),
  KEY `AUTHOR_INDEX` (`novel_author`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说书籍表';

/*Data for the table `wm_novel_novel` */

/*Table structure for table `wm_novel_outline` */

DROP TABLE IF EXISTS `wm_novel_outline`;

CREATE TABLE `wm_novel_outline` (
  `outline_id` int(11) NOT NULL AUTO_INCREMENT COMMENT '自增ID',
  `novel_id` int(11) NOT NULL COMMENT '小说ID',
  `outline_content` text COMMENT '大纲内容',
  `outline_uptime` int(11) NOT NULL COMMENT '更新时间',
  PRIMARY KEY (`outline_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说大纲';

/*Data for the table `wm_novel_outline` */

/*Table structure for table `wm_novel_rec` */

DROP TABLE IF EXISTS `wm_novel_rec`;

CREATE TABLE `wm_novel_rec` (
  `rec_id` int(4) NOT NULL AUTO_INCREMENT,
  `rec_nid` int(4) NOT NULL COMMENT '小说id',
  `rec_img3` varchar(250) DEFAULT NULL COMMENT '触屏推荐图片地址',
  `rec_img4` varchar(250) DEFAULT NULL COMMENT '电脑推荐图片地址',
  `rec_rt` varchar(20) DEFAULT NULL COMMENT '推荐显示的标题',
  `rec_icr` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页封面',
  `rec_ibr` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页精品',
  `rec_ir` tinyint(1) NOT NULL DEFAULT '0' COMMENT '首页推荐',
  `rec_ccr` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分类封面',
  `rec_cbr` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分类精品',
  `rec_cr` tinyint(1) NOT NULL DEFAULT '0' COMMENT '分类推荐',
  `rec_order` int(1) NOT NULL DEFAULT '99' COMMENT '排序',
  `rec_time` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`rec_id`),
  KEY `nid_index` (`rec_nid`),
  KEY `rec_index` (`rec_rt`,`rec_icr`,`rec_ibr`,`rec_ir`,`rec_ccr`,`rec_cbr`,`rec_cr`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说推荐表';

/*Data for the table `wm_novel_rec` */

/*Table structure for table `wm_novel_rewardlog` */

DROP TABLE IF EXISTS `wm_novel_rewardlog`;

CREATE TABLE `wm_novel_rewardlog` (
  `log_id` int(4) NOT NULL AUTO_INCREMENT,
  `log_nid` int(4) NOT NULL DEFAULT '0' COMMENT '小说id',
  `log_cid` int(4) DEFAULT '0' COMMENT '章节id，可以为0',
  `log_uid` int(4) NOT NULL DEFAULT '0' COMMENT '打赏的用户id',
  `log_gold1` decimal(10,2) DEFAULT '0.00' COMMENT '打赏消耗的金币1',
  `log_gold2` decimal(10,2) DEFAULT '0.00' COMMENT '打赏消耗的金币2',
  `log_time` int(4) NOT NULL DEFAULT '0' COMMENT '订打赏的时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说打赏记录日志表';

/*Data for the table `wm_novel_rewardlog` */

/*Table structure for table `wm_novel_sell` */

DROP TABLE IF EXISTS `wm_novel_sell`;

CREATE TABLE `wm_novel_sell` (
  `sell_id` int(4) NOT NULL AUTO_INCREMENT,
  `sell_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为上架，0为下架',
  `sell_novel_id` int(4) NOT NULL DEFAULT '0' COMMENT '小说id',
  `sell_type` varchar(10) CHARACTER SET latin1 DEFAULT '1' COMMENT '销售类型,1为单章，2为全本，3为包月',
  `sell_number` decimal(8,2) DEFAULT '0.00' COMMENT '单章千字价格【金币2】',
  `sell_all` decimal(8,2) DEFAULT '0.00' COMMENT '全本销售价格【金币2】',
  `sell_month` decimal(8,2) DEFAULT '0.00' COMMENT '包月价格【金币2】',
  `sell_time` int(4) NOT NULL DEFAULT '0' COMMENT '上架时间',
  PRIMARY KEY (`sell_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说上架销售记录表';

/*Data for the table `wm_novel_sell` */

/*Table structure for table `wm_novel_sign` */

DROP TABLE IF EXISTS `wm_novel_sign`;

CREATE TABLE `wm_novel_sign` (
  `sign_id` int(4) NOT NULL AUTO_INCREMENT,
  `sign_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为签约，0为解除签约',
  `sign_novel_id` int(4) NOT NULL COMMENT '小说id',
  `sign_manager_id` int(4) NOT NULL DEFAULT '1' COMMENT '签约的管理员',
  `sign_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '签约类型，1为分成销售，2为买断版权',
  `sign_sign_id` int(4) DEFAULT '0' COMMENT '签约等级',
  `sign_time` int(4) NOT NULL COMMENT '签约的时间',
  PRIMARY KEY (`sign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说签约买断记录表';

/*Data for the table `wm_novel_sign` */

/*Table structure for table `wm_novel_sublog` */

DROP TABLE IF EXISTS `wm_novel_sublog`;

CREATE TABLE `wm_novel_sublog` (
  `log_id` int(4) NOT NULL AUTO_INCREMENT,
  `log_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '订阅类型，1是单章，2是全本，3是包月',
  `log_nid` int(4) NOT NULL DEFAULT '0' COMMENT '小说id',
  `log_cid` int(4) DEFAULT '0' COMMENT '章节id，可以为0',
  `log_uid` int(4) NOT NULL DEFAULT '0' COMMENT '订阅的用户id',
  `log_gold1` decimal(10,2) DEFAULT '0.00' COMMENT '订阅消耗的金币1',
  `log_gold2` decimal(10,2) DEFAULT '0.00' COMMENT '订阅消耗的金币2',
  `log_time` int(4) NOT NULL DEFAULT '0' COMMENT '订阅的时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说订阅记录表';

/*Data for the table `wm_novel_sublog` */

/*Table structure for table `wm_novel_timelimit` */

DROP TABLE IF EXISTS `wm_novel_timelimit`;

CREATE TABLE `wm_novel_timelimit` (
  `timelimit_id` int(4) NOT NULL AUTO_INCREMENT,
  `timelimit_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '布尔值，是否可用',
  `timelimit_nid` int(4) NOT NULL COMMENT '小说id',
  `timelimit_starttime` int(4) NOT NULL COMMENT '限时免费开始时间',
  `timelimit_endtime` int(4) NOT NULL COMMENT '限时免费结束时间',
  `timelimit_order` int(4) NOT NULL COMMENT '显示顺序',
  `timelimit_time` int(4) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`timelimit_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说限时免费表';

/*Data for the table `wm_novel_timelimit` */

/*Table structure for table `wm_novel_type` */

DROP TABLE IF EXISTS `wm_novel_type`;

CREATE TABLE `wm_novel_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_topid` int(4) NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '子栏目id',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_cname` varchar(10) DEFAULT NULL COMMENT '类型简称',
  `type_pinyin` varchar(50) DEFAULT NULL COMMENT '类型拼音',
  `type_order` int(4) NOT NULL COMMENT '排序',
  `type_ico` varchar(200) DEFAULT NULL COMMENT '分类图标',
  `type_info` varchar(100) DEFAULT NULL COMMENT '分类简介',
  `type_tempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类页模版',
  `type_titempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类首页的模版',
  `type_ctempid` int(4) NOT NULL DEFAULT '0' COMMENT 'info模版',
  `type_mtempid` int(4) NOT NULL DEFAULT '0' COMMENT 'menu模版',
  `type_rtempid` int(4) NOT NULL DEFAULT '0' COMMENT 'read模版',
  `type_title` varchar(80) DEFAULT NULL COMMENT '分类标题',
  `type_key` varchar(100) DEFAULT NULL COMMENT '分类关键字',
  `type_desc` varchar(120) DEFAULT NULL COMMENT '分类描述',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='小说分类表';

/*Data for the table `wm_novel_type` */

insert  into `wm_novel_type`(`type_id`,`type_topid`,`type_pid`,`type_name`,`type_cname`,`type_pinyin`,`type_order`,`type_ico`,`type_info`,`type_tempid`,`type_titempid`,`type_ctempid`,`type_mtempid`,`type_rtempid`,`type_title`,`type_key`,`type_desc`) values (1,0,'0','玄幻魔法','玄幻','xuanhuanmofa',1,'','玄幻魔法给你最真实的魔法小说洗',0,0,0,0,0,'','',''),(2,0,'0','武侠修真','武侠','wuxiaxiuzhen',3,'','',0,0,0,0,0,'','',''),(3,0,'0','都市言情','都市','dushiyanqing',5,'','',0,0,0,0,0,'','',''),(4,0,'0','历史军事','历史','lishijunshi',7,'','',0,0,0,0,0,'','',''),(5,0,'0','悬疑激情','悬疑','xuanyijiqing',9,'','',0,0,0,0,0,'','',''),(6,0,'0','网游竞技','网游','wangyoujingji',11,'','',0,0,0,0,0,'','',''),(7,0,'0','科幻奇幻','科幻','kehuanqihuan',13,'','',0,0,0,0,0,'','',''),(8,0,'0','恐怖灵异','恐怖','kongbulingyi',15,'','',0,0,0,0,0,'','','');

/*Table structure for table `wm_novel_volume` */

DROP TABLE IF EXISTS `wm_novel_volume`;

CREATE TABLE `wm_novel_volume` (
  `volume_id` int(11) NOT NULL AUTO_INCREMENT,
  `volume_name` varchar(20) NOT NULL COMMENT '分卷名',
  `volume_nid` int(4) NOT NULL DEFAULT '0' COMMENT '书籍id0为默认分卷',
  `volume_desc` varchar(100) DEFAULT NULL COMMENT '分卷描述',
  `volume_order` int(4) NOT NULL DEFAULT '0' COMMENT '排序',
  `volume_time` int(4) NOT NULL DEFAULT '0' COMMENT '创建时间年月日时分秒',
  PRIMARY KEY (`volume_id`,`volume_time`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='小说分卷表';

/*Data for the table `wm_novel_volume` */

insert  into `wm_novel_volume`(`volume_id`,`volume_name`,`volume_nid`,`volume_desc`,`volume_order`,`volume_time`) values (1,'正文',0,'正文内容',0,22);

/*Table structure for table `wm_novel_welfare` */

DROP TABLE IF EXISTS `wm_novel_welfare`;

CREATE TABLE `wm_novel_welfare` (
  `welfare_id` int(4) NOT NULL AUTO_INCREMENT,
  `welfare_nid` int(4) NOT NULL COMMENT '小说id',
  `welfare_type` varchar(250) DEFAULT NULL COMMENT '允许的小说分成方式',
  `welfare_number` decimal(5,2) DEFAULT NULL COMMENT '小说字数奖励',
  `welfare_finish` varchar(500) DEFAULT NULL COMMENT '小说完本奖励',
  `welfare_update` varchar(500) DEFAULT NULL COMMENT '小说更新奖励，每月结算',
  `welfare_full` varchar(500) DEFAULT NULL COMMENT '每月出勤奖励，每月结算',
  PRIMARY KEY (`welfare_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='小说福利设置表';

/*Data for the table `wm_novel_welfare` */

/*Table structure for table `wm_operate_operate` */

DROP TABLE IF EXISTS `wm_operate_operate`;

CREATE TABLE `wm_operate_operate` (
  `operate_id` int(4) NOT NULL AUTO_INCREMENT,
  `operate_module` varchar(20) NOT NULL COMMENT '操作模块',
  `operate_type` varchar(30) NOT NULL COMMENT '操作类型',
  `operate_cid` int(4) NOT NULL DEFAULT '0' COMMENT '操作内容的id',
  `operate_ip` varchar(15) NOT NULL DEFAULT '127.0.0.1' COMMENT 'ip',
  `operate_time` int(4) NOT NULL DEFAULT '0' COMMENT '时间',
  PRIMARY KEY (`operate_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户顶踩，评分等操作记录';

/*Data for the table `wm_operate_operate` */

/*Table structure for table `wm_operate_score` */

DROP TABLE IF EXISTS `wm_operate_score`;

CREATE TABLE `wm_operate_score` (
  `score_id` int(4) NOT NULL AUTO_INCREMENT,
  `score_module` varchar(20) NOT NULL COMMENT '评分的模块',
  `score_cid` int(4) NOT NULL COMMENT '内容id',
  `score_one` int(4) NOT NULL DEFAULT '0' COMMENT '一分的人数',
  `score_two` int(4) NOT NULL DEFAULT '0' COMMENT '两分的人数',
  `score_three` int(4) NOT NULL DEFAULT '0' COMMENT '三分的人数',
  `score_four` int(4) NOT NULL DEFAULT '0' COMMENT '四分的人数',
  `score_five` int(4) NOT NULL DEFAULT '0' COMMENT '五分的人数',
  PRIMARY KEY (`score_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='互动评分操作';

/*Data for the table `wm_operate_score` */

/*Table structure for table `wm_picture_picture` */

DROP TABLE IF EXISTS `wm_picture_picture`;

CREATE TABLE `wm_picture_picture` (
  `picture_id` int(11) NOT NULL AUTO_INCREMENT,
  `type_id` int(4) NOT NULL DEFAULT '1' COMMENT '新闻分类id',
  `picture_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否审核',
  `picture_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐',
  `picture_simg` varchar(120) DEFAULT NULL COMMENT '缩略图',
  `picture_name` varchar(50) NOT NULL COMMENT '图集标题',
  `picture_cname` varchar(50) DEFAULT NULL COMMENT '图集段标题',
  `picture_tags` varchar(50) DEFAULT NULL COMMENT '图集标签',
  `picture_info` varchar(100) DEFAULT NULL COMMENT '点评，预览',
  `picture_imgs` text COMMENT '图片序列化数组',
  `picture_count` int(4) NOT NULL DEFAULT '0' COMMENT '图片数量',
  `picture_content` text COMMENT '简介',
  `picture_read` int(4) NOT NULL DEFAULT '0' COMMENT '图集阅读量',
  `picture_replay` int(4) NOT NULL DEFAULT '0' COMMENT '评论量',
  `picture_ding` int(4) NOT NULL DEFAULT '0' COMMENT '顶',
  `picture_cai` int(4) NOT NULL DEFAULT '0' COMMENT '踩',
  `picture_start` decimal(2,1) NOT NULL DEFAULT '0.0' COMMENT '星级',
  `picture_score` decimal(2,0) DEFAULT '0' COMMENT '评分',
  `picture_time` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间年月日时分秒',
  PRIMARY KEY (`picture_id`),
  KEY `tid` (`type_id`,`picture_time`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图集表';

/*Data for the table `wm_picture_picture` */

/*Table structure for table `wm_picture_type` */

DROP TABLE IF EXISTS `wm_picture_type`;

CREATE TABLE `wm_picture_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_topid` int(4) NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '子栏目id',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_cname` varchar(10) DEFAULT NULL COMMENT '类型简称',
  `type_pinyin` varchar(50) DEFAULT NULL COMMENT '类型拼音',
  `type_order` int(2) NOT NULL DEFAULT '0' COMMENT '排序',
  `type_simg` varchar(200) DEFAULT NULL COMMENT '分类缩略图',
  `type_ico` varchar(200) DEFAULT NULL COMMENT '分类图标',
  `type_info` varchar(100) DEFAULT NULL COMMENT '分类信息',
  `type_titempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类首页模版',
  `type_tempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类页模板',
  `type_ctempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类内容页模版',
  `type_title` varchar(80) DEFAULT NULL COMMENT '标题',
  `type_key` varchar(100) DEFAULT NULL COMMENT '关键字',
  `type_desc` varchar(120) DEFAULT NULL COMMENT '描述',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='图集分类表';

/*Data for the table `wm_picture_type` */

/*Table structure for table `wm_plugin` */

DROP TABLE IF EXISTS `wm_plugin`;

CREATE TABLE `wm_plugin` (
  `plugin_id` int(4) NOT NULL AUTO_INCREMENT,
  `plugin_name` varchar(50) NOT NULL COMMENT '插件名字',
  `plugin_floder` varchar(50) NOT NULL COMMENT '插件文件夹',
  `plugin_author` varchar(20) NOT NULL COMMENT '插件作者',
  `plugin_version` varchar(10) NOT NULL COMMENT '插件版本',
  `plugin_time` int(4) NOT NULL COMMENT '插件安装时间',
  PRIMARY KEY (`plugin_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='插件安装表';

/*Data for the table `wm_plugin` */

insert  into `wm_plugin`(`plugin_id`,`plugin_name`,`plugin_floder`,`plugin_author`,`plugin_version`,`plugin_time`) values (6,'官方-报名插件DEMO','demo','WMCMS官方','V1.0',1528631159);

/*Table structure for table `wm_plugin_config` */

DROP TABLE IF EXISTS `wm_plugin_config`;

CREATE TABLE `wm_plugin_config` (
  `config_id` int(4) NOT NULL AUTO_INCREMENT,
  `config_plugin_id` int(4) NOT NULL COMMENT '插件id',
  `config_key` varchar(100) NOT NULL COMMENT '插件键',
  `config_val` text COMMENT '插件值',
  PRIMARY KEY (`config_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='插件参数配置表';

/*Data for the table `wm_plugin_config` */

insert  into `wm_plugin_config`(`config_id`,`config_plugin_id`,`config_key`,`config_val`) values (4,6,'plugin_demo_site_open','0');

/*Table structure for table `wm_plugin_demo_apply` */

DROP TABLE IF EXISTS `wm_plugin_demo_apply`;

CREATE TABLE `wm_plugin_demo_apply` (
  `message_id` int(4) NOT NULL AUTO_INCREMENT,
  `message_name` varchar(20) NOT NULL COMMENT '报名用户',
  `message_phone` varchar(11) NOT NULL COMMENT '报名电话',
  `message_time` int(4) NOT NULL COMMENT '报名时间',
  PRIMARY KEY (`message_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='demo插件报名表';

/*Data for the table `wm_plugin_demo_apply` */

insert  into `wm_plugin_demo_apply`(`message_id`,`message_name`,`message_phone`,`message_time`) values (1,'未梦','15123232323',1528631188);

/*Table structure for table `wm_props_props` */

DROP TABLE IF EXISTS `wm_props_props`;

CREATE TABLE `wm_props_props` (
  `props_id` int(4) NOT NULL AUTO_INCREMENT,
  `props_type_id` int(4) NOT NULL COMMENT '类型',
  `props_status` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `props_name` varchar(20) NOT NULL COMMENT '道具名字',
  `props_cover` varchar(250) DEFAULT NULL COMMENT '道具图标',
  `props_stock` int(4) NOT NULL DEFAULT '0' COMMENT '剩余库存',
  `props_desc` text COMMENT '道具介绍',
  `props_cost` tinyint(1) DEFAULT '1' COMMENT '1为网站消费类型，金币购买，2为现金购买',
  `props_gold1` decimal(8,2) DEFAULT '0.00' COMMENT '金币1价格',
  `props_gold2` decimal(8,2) DEFAULT '0.00' COMMENT '金币2价格',
  `props_money` decimal(10,2) DEFAULT '0.00' COMMENT '现金价格',
  `props_time` int(4) NOT NULL DEFAULT '0' COMMENT '上架时间',
  `props_option` varchar(500) DEFAULT NULL COMMENT '附加数据，序列化字符串',
  `props_order` int(1) DEFAULT '999' COMMENT '排序，越小越靠前',
  PRIMARY KEY (`props_id`),
  KEY `index_type` (`props_type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8 COMMENT='礼物道具表';

/*Data for the table `wm_props_props` */

insert  into `wm_props_props`(`props_id`,`props_type_id`,`props_status`,`props_name`,`props_cover`,`props_stock`,`props_desc`,`props_cost`,`props_gold1`,`props_gold2`,`props_money`,`props_time`,`props_option`,`props_order`) values (3,4,1,'美女鼓励师','/upload/images/20170317/20170317180045804022978302.jpg',99954,'',2,'0.00','200.00','0.00',1488720588,'a:5:{s:3:\"rec\";s:1:\"0\";s:5:\"month\";s:1:\"0\";s:10:\"author_exp\";s:1:\"0\";s:8:\"fans_exp\";s:1:\"0\";s:8:\"user_exp\";s:1:\"0\";}',4),(4,4,1,'纯金奖杯','/upload/images/20170317/20170317180040436654527725.jpg',99976,'',1,'0.00','100.00','0.00',1488720588,'a:5:{s:3:\"rec\";s:1:\"0\";s:5:\"month\";s:1:\"0\";s:10:\"author_exp\";s:1:\"0\";s:8:\"fans_exp\";s:1:\"0\";s:8:\"user_exp\";s:1:\"0\";}',3),(7,4,1,'苹果电脑','/upload/images/20170317/20170317180034787516420251.jpg',99948,'',1,'0.00','50.00','0.00',1488720803,'a:5:{s:3:\"rec\";s:1:\"0\";s:5:\"month\";s:1:\"0\";s:10:\"author_exp\";s:1:\"0\";s:8:\"fans_exp\";s:1:\"0\";s:8:\"user_exp\";s:1:\"0\";}',2),(8,4,1,'二手电脑','/upload/images/20170317/20170317180028754600344308.jpg',99932,'',1,'0.00','10.00','0.00',1488721070,'a:5:{s:3:\"rec\";s:1:\"1\";s:5:\"month\";s:1:\"1\";s:10:\"author_exp\";s:1:\"1\";s:8:\"fans_exp\";s:1:\"1\";s:8:\"user_exp\";s:1:\"1\";}',1);

/*Table structure for table `wm_props_sell` */

DROP TABLE IF EXISTS `wm_props_sell`;

CREATE TABLE `wm_props_sell` (
  `sell_id` int(4) NOT NULL AUTO_INCREMENT,
  `sell_module` varchar(20) NOT NULL COMMENT '销售的模块',
  `sell_cid` int(4) NOT NULL DEFAULT '0' COMMENT '购买的内容id',
  `sell_props_id` int(4) NOT NULL COMMENT '销售产品',
  `sell_user_id` int(4) NOT NULL COMMENT '购买用户',
  `sell_number` int(1) NOT NULL DEFAULT '1' COMMENT '购买数量',
  `sell_gold1` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买金币1',
  `sell_gold2` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买金币2',
  `sell_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '购买现金',
  `sell_remark` varchar(100) DEFAULT NULL COMMENT '留言备注',
  `sell_time` int(4) NOT NULL DEFAULT '0' COMMENT '购买时间',
  PRIMARY KEY (`sell_id`),
  KEY `index_props` (`sell_props_id`),
  KEY `index_user` (`sell_user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='道具出售记录表';

/*Data for the table `wm_props_sell` */

/*Table structure for table `wm_props_type` */

DROP TABLE IF EXISTS `wm_props_type`;

CREATE TABLE `wm_props_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_status` tinyint(1) DEFAULT '1' COMMENT '是否显示',
  `type_module` varchar(20) DEFAULT NULL COMMENT '分类所属模块',
  `type_topid` int(4) DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) DEFAULT '0' COMMENT '子栏目id',
  `type_name` varchar(20) DEFAULT NULL COMMENT '分类名字',
  `type_cname` varchar(20) DEFAULT NULL COMMENT '分类简称',
  `type_order` int(1) DEFAULT NULL COMMENT '分类排序',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='道具分类表';

/*Data for the table `wm_props_type` */

insert  into `wm_props_type`(`type_id`,`type_status`,`type_module`,`type_topid`,`type_pid`,`type_name`,`type_cname`,`type_order`) values (4,1,'novel',0,'0','打赏','',0);

/*Table structure for table `wm_replay_replay` */

DROP TABLE IF EXISTS `wm_replay_replay`;

CREATE TABLE `wm_replay_replay` (
  `replay_id` int(4) NOT NULL AUTO_INCREMENT,
  `replay_floor` int(4) NOT NULL DEFAULT '1' COMMENT '评论的楼层',
  `replay_pid` varchar(200) NOT NULL DEFAULT '0' COMMENT '祖父楼层id树',
  `replay_rid` int(4) NOT NULL DEFAULT '0' COMMENT '回复的id',
  `replay_status` int(1) NOT NULL DEFAULT '1' COMMENT '1为正常,2为审核中',
  `replay_module` varchar(20) DEFAULT NULL COMMENT '模块',
  `replay_cid` int(4) NOT NULL COMMENT '内容id',
  `replay_subset_id` int(4) DEFAULT '0' COMMENT '内容子集id',
  `replay_segment_id` int(4) DEFAULT '0' COMMENT '内容子集分段id',
  `replay_uid` int(4) DEFAULT '0' COMMENT '用户id',
  `replay_nickname` varchar(50) NOT NULL COMMENT '姓名',
  `replay_ruid` int(4) DEFAULT '0' COMMENT '回复用户的id',
  `replay_rnickname` varchar(50) DEFAULT NULL COMMENT '回复用户的昵称',
  `replay_content` text NOT NULL COMMENT '内容',
  `replay_count` int(4) NOT NULL DEFAULT '0' COMMENT '回复数量',
  `replay_ding` int(4) NOT NULL DEFAULT '0' COMMENT '顶',
  `replay_cai` int(4) NOT NULL DEFAULT '0' COMMENT '踩',
  `replay_time` int(4) NOT NULL COMMENT '时间',
  `replay_ip` varchar(150) DEFAULT NULL COMMENT 'ip',
  PRIMARY KEY (`replay_id`),
  KEY `ding` (`replay_ding`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='评论表';

/*Data for the table `wm_replay_replay` */

/*Table structure for table `wm_search_search` */

DROP TABLE IF EXISTS `wm_search_search`;

CREATE TABLE `wm_search_search` (
  `search_id` int(4) NOT NULL AUTO_INCREMENT,
  `search_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐显示',
  `search_count` int(4) NOT NULL DEFAULT '1' COMMENT '搜索次数',
  `search_module` varchar(20) NOT NULL COMMENT '模块',
  `search_type` int(4) NOT NULL COMMENT '1为标题,2为作者,3为标签',
  `search_key` varchar(20) NOT NULL COMMENT '关键词',
  `search_data` int(4) NOT NULL DEFAULT '0' COMMENT '数据',
  `search_time` int(4) NOT NULL DEFAULT '0' COMMENT '搜索时间',
  PRIMARY KEY (`search_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='搜索记录表';

/*Data for the table `wm_search_search` */

/*Table structure for table `wm_seo_errpage` */

DROP TABLE IF EXISTS `wm_seo_errpage`;

CREATE TABLE `wm_seo_errpage` (
  `errpage_id` int(4) NOT NULL AUTO_INCREMENT,
  `errpage_code` int(1) DEFAULT '500' COMMENT '错误页面代码类型，404或者500',
  `errpage_url` varchar(500) DEFAULT NULL COMMENT '错误的页面地址',
  `errpage_ua` varchar(500) DEFAULT NULL COMMENT '浏览器ua',
  `errpage_time` int(4) DEFAULT '0' COMMENT '错误记录的时间',
  PRIMARY KEY (`errpage_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='错误页面统计';

/*Data for the table `wm_seo_errpage` */

/*Table structure for table `wm_seo_html` */

DROP TABLE IF EXISTS `wm_seo_html`;

CREATE TABLE `wm_seo_html` (
  `html_id` int(4) NOT NULL AUTO_INCREMENT,
  `html_module` varchar(20) DEFAULT NULL COMMENT '模块',
  `html_type` varchar(30) DEFAULT NULL COMMENT '类型',
  `html_type_id` int(4) NOT NULL DEFAULT '0' COMMENT '分类id',
  `html_path4` varchar(100) DEFAULT NULL COMMENT 'web静态路径',
  PRIMARY KEY (`html_id`)
) ENGINE=MyISAM AUTO_INCREMENT=159 DEFAULT CHARSET=utf8 COMMENT='分类和内容静态地址表';

/*Data for the table `wm_seo_html` */

insert  into `wm_seo_html`(`html_id`,`html_module`,`html_type`,`html_type_id`,`html_path4`) values (118,'novel','tindex',4,'/html/novel/{tid}.html'),(119,'novel','list',4,'/html/novel/{tid}_{page}.html'),(120,'novel','content',4,'/html/novel/{nid}.html'),(121,'novel','menu',4,'/html/novel/menu/{nid}/{page}.html'),(122,'novel','read',4,'/html/novel/read/{nid}/{cid}.html'),(123,'novel','tindex',5,'/html/novel/{tid}.html'),(124,'novel','list',5,'/html/novel/{tid}_{page}.html'),(125,'novel','content',5,'/html/novel/{nid}.html'),(126,'novel','menu',5,'/html/novel/menu/{nid}/{page}.html'),(127,'novel','read',5,'/html/novel/read/{nid}/{cid}.html'),(128,'novel','tindex',6,'/html/novel/{tid}.html'),(129,'novel','list',6,'/html/novel/{tid}_{page}.html'),(130,'novel','content',6,'/html/novel/{nid}.html'),(131,'novel','menu',6,'/html/novel/menu/{nid}/{page}.html'),(132,'novel','read',6,'/html/novel/read/{nid}/{cid}.html'),(133,'novel','tindex',7,'/html/novel/{tid}.html'),(134,'novel','list',7,'/html/novel/{tid}_{page}.html'),(135,'novel','content',7,'/html/novel/{nid}.html'),(136,'novel','menu',7,'/html/novel/menu/{nid}/{page}.html'),(137,'novel','read',7,'/html/novel/read/{nid}/{cid}.html'),(138,'novel','tindex',8,'/html/novel/{tid}.html'),(139,'novel','list',8,'/html/novel/{tid}_{page}.html'),(140,'novel','content',8,'/html/novel/{nid}.html'),(141,'novel','menu',8,'/html/novel/menu/{nid}/{page}.html'),(142,'novel','read',8,'/html/novel/read/{nid}/{cid}.html'),(82,'novel','tindex',9,'/html/novel/{tid}.html'),(83,'novel','list',9,'/html/novel/{tid}_{page}.html'),(84,'novel','content',9,'/html/novel/{nid}.html'),(85,'novel','menu',9,'/html/novel/menu/{nid}/{page}.html'),(86,'novel','read',9,'/html/novel/read/{nid}/{cid}.html'),(108,'novel','tindex',1,'/html/novel/{tid}.html'),(109,'novel','list',1,'/html/novel/{tid}_{page}.html'),(110,'novel','content',1,'/html/novel/{nid}.html'),(111,'novel','menu',1,'/html/novel/menu/{nid}/{page}.html'),(112,'novel','read',1,'/html/novel/read/{nid}/{cid}.html'),(113,'novel','tindex',2,'/html/novel/{tid}.html'),(114,'novel','list',2,'/html/novel/{tid}_{page}.html'),(115,'novel','content',2,'/html/novel/{nid}.html'),(116,'novel','menu',2,'/html/novel/menu/{nid}/{page}.html'),(117,'novel','read',2,'/html/novel/read/{nid}/{cid}.html'),(100,'novel','tindex',3,'/html/novel/{tid}.html'),(101,'novel','list',3,'/html/novel/{tid}_{page}.html'),(102,'novel','content',3,'/html/novel/{nid}.html'),(103,'novel','menu',3,'/html/novel/menu/{nid}/{page}.html'),(104,'novel','read',3,'/html/novel/read/{nid}/{cid}.html'),(143,'article','tindex',1,'/html/article/{tid}.html'),(144,'article','list',1,'/html/article/list/{tid}_{page}.html'),(145,'article','content',1,'/html/article/content/{aid}.html'),(146,'article','tindex',2,'/html/article/{tid}.html'),(147,'article','list',2,'/html/article/list/{tid}_{page}.html'),(148,'article','content',2,'/html/article/content/{aid}.html'),(149,'article','tindex',3,'/html/article/{tid}.html'),(150,'article','list',3,'/html/article/list/{tid}_{page}.html'),(151,'article','content',3,'/html/article/content/{aid}.html'),(152,'picture','list',1,'/html/picture/{tid}_{page}.html'),(153,'picture','content',1,'/html/picture/{nid}.html'),(154,'picture','list',2,'/html/picture/{tid}_{page}.html'),(155,'picture','content',2,'/html/picture/{nid}.html'),(156,'bbs','list',1,'/html/bbs/{tid}_{page}.html'),(157,'bbs','content',1,'/html/bbs/{cid}.html'),(158,'bbs','replay',1,'/html/bbs/{cid}_{page}.html');

/*Table structure for table `wm_seo_html_plan` */

DROP TABLE IF EXISTS `wm_seo_html_plan`;

CREATE TABLE `wm_seo_html_plan` (
  `plan_id` int(4) NOT NULL AUTO_INCREMENT,
  `plan_name` varchar(30) NOT NULL COMMENT '任务名字',
  `plan_url` varchar(250) NOT NULL COMMENT 'url连接',
  `plan_data` varchar(500) DEFAULT NULL COMMENT 'post参数',
  `plan_path` varchar(100) NOT NULL COMMENT '保存路径',
  `plan_lasttime` int(4) NOT NULL DEFAULT '0' COMMENT '最后执行时间',
  `plan_addtime` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`plan_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='html任务表';

/*Data for the table `wm_seo_html_plan` */

/*Table structure for table `wm_seo_keys` */

DROP TABLE IF EXISTS `wm_seo_keys`;

CREATE TABLE `wm_seo_keys` (
  `keys_id` int(11) NOT NULL AUTO_INCREMENT,
  `keys_module` varchar(20) DEFAULT NULL COMMENT '所属模块',
  `keys_page` varchar(50) NOT NULL COMMENT '页面标识',
  `keys_pagename` varchar(20) DEFAULT NULL COMMENT '页面名字',
  `keys_title` varchar(80) NOT NULL COMMENT '页面标题',
  `keys_key` varchar(150) NOT NULL COMMENT '页面关键字',
  `keys_desc` varchar(250) NOT NULL COMMENT '页面描述',
  PRIMARY KEY (`keys_id`),
  UNIQUE KEY `page` (`keys_page`)
) ENGINE=MyISAM AUTO_INCREMENT=108 DEFAULT CHARSET=utf8 COMMENT='seo关键词表';

/*Data for the table `wm_seo_keys` */

insert  into `wm_seo_keys`(`keys_id`,`keys_module`,`keys_page`,`keys_pagename`,`keys_title`,`keys_key`,`keys_desc`) values (1,'all','index','首页','网站首页-{网站名}','{网站名}','{网站名}'),(2,'novel','novel_type','小说列表','最新{分类名字}小说列表，{网站名}','最新{分类名字}小说列表，{网站名}','最新{分类名字}小说列表，{网站名}'),(3,'novel','novel_info','小说介绍','{名字}最新章节-{作者}-{类型}','{名字}最新章节-{作者}-{类型}','{简介:100}'),(4,'novel','novel_menu','小说目录','{名字}','{名字}','{简介:100}'),(5,'novel','novel_read','小说阅读','{名字}-{章节名字}','{名字}-{章节名字}','{名字}-{章节名字}'),(6,'novel','novel_topindex','小说排行首页','小说排行榜首页','小说排行榜首页','小说排行榜首页'),(7,'novel','novel_search','小说搜索','{搜索词}的搜索结果页','{搜索词}的搜索结果页','{搜索词}的搜索结果页'),(8,'novel','novel_toplist','小说排行列表','{类型排行}小说排行榜','{类型排行},小说排行榜','{类型排行}小说排行榜'),(9,'user','user_reg','用户注册','用户注册-{网站名}','用户注册,{网站名}','{网站名}新用户注册中心'),(10,'user','user_login','用户登录','用户登录-{网站名}','用户登录,{网站名}','{网站名}老用户登录'),(11,'user','user_getpsw','找回密码','找回密码-{网站名}','找回密码','找回密码'),(12,'user','user_home','个人中心','个人中心-{网站名}','个人中心,{网站名}','{用户名}的个人中心'),(13,'user','user_repsw','重置密码','重置密码-{网站名}','重置密码,{网站名}','重置用户密码'),(14,'user','user_basic','用户资料','用户资料-{网站名}','用户资料,{网站名}','{用户名}的详细资料'),(15,'user','user_attribute','用户属性','用户属性-{网站名}','用户属性,{网站名}','{用户名}的用户属性'),(17,'user','user_head','修改头像','修改头像-{网站名}','修改头像,{网站名}','修改头像'),(18,'user','user_uppsw','修改密码','修改密码-{网站名}','修改密码,{网站名}','修改密码'),(19,'user','user_bind','账号绑定','账号绑定-{网站名}','账号绑定,{网站名}','账号绑定'),(20,'user','user_shelf','我的书架','我的书架-{网站名}','我的书架','我的书架'),(21,'user','user_coll','我的收藏','我的收藏-{网站名}','我的收藏,{网站名}','我的收藏'),(22,'user','user_fhome','查看好友资料','查看好友资料-{网站名}','查看好友资料,{网站名}','{用户名}的个人资料'),(23,'novel','novel_replay','小说评论列表','{名字}的所有评论列表','{名字}评论','{名字}的所有评论列表'),(24,'user','user_msglist','消息列表','消息列表-{网站名}','消息列表,网站名}','消息列表-{网站名}'),(25,'user','user_msg','消息内容','消息内容-{网站名}','消息内容,{网站名}','消息内容-{网站名}'),(26,'author','author_index','作家中心','作家中心-{网站名}','作家中心,{网站名}','作家中心-{网站名}'),(27,'author','author_novel_novellist','小说管理','作品管理-{网站名}','作品管理,{网站名}','作品管理-{网站名}'),(28,'author','author_novel_noveledit','编辑小说','编辑作品-{网站名}','编辑作品,{网站名}','编辑作品-{网站名}'),(29,'author','author_novel_volumelist','小说分卷列表','小说分卷列表-{网站名}','小说分卷列表,{网站名}','小说分卷列表-{网站名}'),(30,'author','author_createchapter','新建章节','新建章节-{网站名}','新建章节,{网站名}','新建章节-{网站名}'),(31,'author','author_novel_draftlist','小说草稿箱','草稿箱-{网站名}','草稿箱,{网站名}','草稿箱-{网站名}'),(32,'author','author_novel_chapterlist','小说章节列表','章节列表-{网站名}','章节列表,{网站名}','章节列表-{网站名}'),(33,'author','author_novel_draftedit','小说草稿编辑','编辑草稿-{网站名}','编辑草稿,{网站名}','编辑草稿-{网站名}'),(34,'zt','zt_type','专题列表','专题列表-{网站名}','专题列表,{网站名}','专题列表-{网站名}'),(37,'user','user_vistlist','最新访客列表','最新访客列表-{网站名}','最新访客列表,{网站名}','最新访客列表-{网站名}'),(38,'author','author_basic','作者基本资料','作者基本资料-{网站名}','作者基本资料,{网站名}','作者基本资料-{网站名}'),(39,'author','author_incomechapter','章节收入','章节收入-{网站名}','章节收入,{网站名}','章节收入-{网站名}'),(40,'author','author_incomedashang','打赏收入','打赏收入-{网站名}','打赏收入,{网站名}','打赏收入-{网站名}'),(41,'author','author_mentionapply','提现申请','提现申请-{网站名}','提现申请,{网站名}','提现申请-{网站名}'),(42,'author','author_mentionrecord','提现记录','提现记录-{网站名}','提现记录,{网站名}','提现记录-{网站名}'),(43,'article','article_article','文章内容','{标题}-{网站名}','{标题}-{网站名}','{标题}-{网站名}'),(44,'article','article_type','文章分类列表','{分类名}-{网站名}','{分类名}-{网站名}','{分类名}-{网站名}'),(45,'article','article_search','文章搜素','{搜索词}的搜索结果页','{搜索词}的搜索结果页','{搜索词}的搜索结果页'),(46,'article','article_replay','文章评论','{标题}的所有评论列表','{标题}的所有评论列表','{标题}的所有评论列表'),(47,'message','message_add','新增反馈','新增反馈-{网站名}','新增反馈,{网站名}','新增反馈'),(48,'user','user_sign','用户签到','用户签到','用户签到','用户签到'),(49,'bbs','bbs_index','论坛首页','论坛首页,{网站名}','论坛首页,{网站名}','论坛首页,{网站名}'),(50,'bbs','bbs_type','论坛板块列表','论坛板块列表,{网站名}','论坛板块列表,{网站名}','论坛板块列表,{网站名}'),(51,'bbs','bbs_list','主题列表','&#123;版块名字&#125;主题列表','&#123;版块名字&#125;主题列表','&#123;版块名字&#125;主题列表'),(52,'bbs','bbs_bbs','主题内容','{标题},{网站名}','{标题},{网站名}','{标题},{网站名}'),(75,'article','article_tindex','文章分类首页','{分类名}-{网站名}','{分类名}-{网站名}','{分类名}-{网站名}'),(54,'bbs','bbs_post','发表新话题','发表新话题,{网站名}','发表新话题,{网站名}','发表新话题,{网站名}'),(55,'link','link_index','友链首页','友链首页,{网站名}','友链首页,{网站名}','友链首页,{网站名}'),(56,'link','link_type','友链分类','{分类名字},{网站名}','{分类名字},{网站名}','{分类名字},{网站名}'),(57,'link','link_show','友链展示','{友链名字},{网站名}','{友链名字},{网站名}','{友链名字},{网站名}'),(58,'link','link_join','申请友链','申请友链,{网站名}','申请友链,{网站名}','申请友链,{网站名}'),(59,'app','app_type','应用分类列表','{类型名}-{网站名}','{类型名},{网站名}','{类型名},{网站名}'),(60,'app','app_app','应用介绍','{名字}-{网站名}','{名字},{网站名}','{名字}-{网站名}'),(61,'app','app_index','应用首页','应用下载中心-{网站名}','应用下载中心,{网站名}','应用下载中心,{网站名}'),(62,'app','app_search','应用搜索','{搜索词}的搜索结果页','{搜索词}的搜索结果页','{搜索词}的搜索结果页'),(63,'article','article_index','文章首页','文章首页-{网站名}	','文章首页,{网站名}	','文章首页,{网站名}	'),(64,'bbs','bbs_search','论坛搜索','论坛搜索,{网站名}1','论坛搜索,{网站名}','论坛搜索,{网站名}'),(65,'about','about_type','关于信息列表','{分类名字}_{网站名}','{分类名字},{网站名}','{分类名字}-{网站名}'),(66,'about','about_about','关于信息内容页','{标题}-{网站名}','{标题},{网站名}','{标题}-{网站名}'),(67,'user','user_apilogin','第三方账号注册','完善账号信息-{网站名}','完善账号信息,{网站名}','完善账号信息,{网站名}'),(68,'picture','picture_picture','图集内容','{标题}-{网站名}','{标题}-{网站名}','{标题}-{网站名}'),(69,'picture','picture_type','图集分类列表','{分类名字}-{网站名}','{分类名字}-{网站名}','{分类名字}-{网站名}'),(70,'picture','picture_search','图集搜素','{搜索词}的搜索结果页','{搜索词}的搜索结果页','{搜索词}的搜索结果页'),(71,'picture','picture_replay','图集评论','{标题}的所有评论列表','{标题}的所有评论列表','{标题}的所有评论列表'),(72,'picture','picture_toplist','图集排行列表','{排行类型}图集排行榜','{排行类型}图集排行榜','{排行类型}图集排行榜'),(73,'user','user_signlist','用户签到列表','用户签到列表','用户签到列表','用户签到列表'),(74,'novel','novel_index','小说首页','小说首页','小说首页','小说首页'),(76,'picture','picture_index','图集首页','图集首页-{网站名}','图集首页-{网站名}','图集首页-{网站名}'),(77,'user','user_rec','我的推荐','我的推荐-{网站名}','我的推荐','我的推荐'),(78,'user','user_dingyue','我的订阅','我的订阅-{网站名}','我的订阅','我的订阅'),(79,'user','user_fcoll','好友收藏','{好友昵称}的收藏-{网站名}','{好友昵称}的收藏,{网站名}','{好友昵称}的收藏,{网站名}'),(80,'user','user_fdingyue','好友订阅','{好友昵称}的订阅-{网站名}','{好友昵称}的订阅,{网站名}','{好友昵称}的订阅,{网站名}'),(81,'user','user_frec','好友推荐','{好友昵称}的推荐-{网站名}','{好友昵称}的订阅,{网站名}','{好友昵称}的推荐,{网站名}'),(82,'user','user_fshelf','好友书架','{好友昵称}的书架-{网站名}','{好友昵称}的书架,{网站名}','{好友昵称}的书架,{网站名}'),(83,'novel','novel_tindex','分类首页','{分类名字}-小说分类首页-{网站名}','{分类名字}-小说分类首页-{网站名}','{分类名字}-小说分类首页-{网站名}'),(84,'author','author_apply','申请作家','申请作家-{网站名}','申请作家,{网站名}','申请作家-{网站名}'),(85,'author','author_agreement','申请作家协议','申请作家协议-{网站名}','申请作家协议,{网站名}','申请作家协议-{网站名}'),(86,'author','author_novel_volumeedit','小说分卷编辑','小说分卷编辑-{网站名}','小说分卷编辑,{网站名}','小说分卷编辑-{网站名}'),(87,'author','author_article_articlelist','文章投稿列表','文章投稿列表-{网站名}','文章投稿列表,{网站名}','文章投稿列表-{网站名}'),(88,'author','author_article_draftedit','文章草稿编辑','编辑草稿-{网站名}','编辑草稿,{网站名}','编辑草稿-{网站名}'),(89,'author','author_article_draftlist','文章草稿箱','草稿箱-{网站名}','草稿箱,{网站名}','草稿箱-{网站名}'),(90,'author','author_article_articleedit','文章编辑','文章编辑-{网站名}','文章编辑,{网站名}','文章编辑-{网站名}'),(91,'user','user_charge','在线充值','在线充值-{网站名}','在线充值,{网站名}','在线充值-{网站名}'),(92,'author','author_novel_incomelist','小说收入列表','小说收入列表-{网站名}','小说收入列表,{网站名}','小说收入列表-{网站名}'),(93,'user','user_cash_apply','在线申请提现','在线申请提现-{网站名}','在线申请提现,{网站名}','在线申请提现-{网站名}'),(94,'user','user_cash_list','提现申请记录','提现申请记录-{网站名}','提现申请记录,{网站名}','提现申请记录-{网站名}'),(95,'about','about_tindex','关于首页','关于首页-{网站名}','关于首页,{网站名}','关于首页-{网站名}'),(96,'down','down_down','下载内容','{下载内容}-{网站名}','{下载内容},{网站名}','{下载内容}-{网站名}'),(97,'novel','novel_index_boy','男生小说首页','男生小说首页','男生小说首页','男生小说首页'),(98,'novel','novel_index_girl','女生小说首页','女生小说首页','女生小说首页','女生小说首页'),(99,'user','user_read','阅读记录','阅读记录-{网站名}','阅读记录,{网站名}','阅读记录-{网站名}'),(100,'user','user_sub','我的订阅','我的订阅-{网站名}','我的订阅,{网站名}','我的订阅-{网站名}'),(101,'user','user_charge_code','扫码支付','{支付方式}在线扫码支付-{网站名}','{支付方式}在线扫码支付,{网站名}','{支付方式}在线扫码支付-{网站名}'),(102,'user','user_charge_success','支付成功','支付成功-{网站名}','支付成功,{网站名}','支付成功-{网站名}'),(103,'zt','zt_zt','专题详情','{名字}-{网站名}','{名字},{网站名}','{名字}-{网站名}'),(104,'app','app_replay','{名字}评论列表','{名字}的所有评论列表','{名字}评论','{名字}的所有评论列表'),(105,'picture','picture_tindex','分类首页','{分类名字}-图集分类首页-{网站名}','{分类名字}-图集分类首页-{网站名}','{分类名字}-小说分类首页-{网站名}'),(106,'author','author_author','作者个人中心','{笔名}-{网站名}','{笔名},{网站名}','{笔名}-{网站名}'),(107,'author','author_novel_statistics','小说数据统计','小说数据统计','小说数据统计','小说数据统计');

/*Table structure for table `wm_seo_spider` */

DROP TABLE IF EXISTS `wm_seo_spider`;

CREATE TABLE `wm_seo_spider` (
  `spider_id` int(4) NOT NULL AUTO_INCREMENT,
  `spider_group` varchar(30) DEFAULT NULL COMMENT '蜘蛛的分组',
  `spider_group_name` varchar(30) DEFAULT NULL COMMENT '蜘蛛分组的名字',
  `spider_name` varchar(30) DEFAULT NULL COMMENT '蜘蛛的名字',
  `spider_title` varchar(30) DEFAULT NULL COMMENT '蜘蛛的标识',
  `spider_url` varchar(1000) DEFAULT NULL COMMENT '蜘蛛爬行的url',
  `spider_ua` varchar(1000) DEFAULT NULL COMMENT '蜘蛛的ua',
  `spider_time` int(4) DEFAULT NULL COMMENT '蜘蛛爬行时间',
  PRIMARY KEY (`spider_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='蜘蛛爬行记录表';

/*Data for the table `wm_seo_spider` */

/*Table structure for table `wm_seo_urls` */

DROP TABLE IF EXISTS `wm_seo_urls`;

CREATE TABLE `wm_seo_urls` (
  `urls_id` int(4) NOT NULL AUTO_INCREMENT,
  `urls_module` varchar(20) DEFAULT NULL COMMENT '所属模块',
  `urls_page` varchar(50) NOT NULL COMMENT '页面标识',
  `urls_pagename` varchar(20) DEFAULT NULL COMMENT '页面名字',
  `urls_url1` varchar(250) NOT NULL COMMENT '动态地址',
  `urls_url2` varchar(250) NOT NULL COMMENT '静态地址',
  `urls_url3` varchar(250) DEFAULT NULL COMMENT '普通模式地址',
  `urls_url4` varchar(250) DEFAULT NULL COMMENT '兼容模式地址',
  `urls_url5` varchar(250) DEFAULT NULL COMMENT 'PATHINFO模式地址',
  `urls_url6` varchar(250) DEFAULT NULL COMMENT 'REWRITE模式地址',
  PRIMARY KEY (`urls_id`),
  UNIQUE KEY `page` (`urls_page`)
) ENGINE=MyISAM AUTO_INCREMENT=130 DEFAULT CHARSET=utf8 COMMENT='seo伪静态地址';

/*Data for the table `wm_seo_urls` */

insert  into `wm_seo_urls`(`urls_id`,`urls_module`,`urls_page`,`urls_pagename`,`urls_url1`,`urls_url2`,`urls_url3`,`urls_url4`,`urls_url5`,`urls_url6`) values (1,'all','index','首页','/index.php?pt={pt}','/index.html','/','/','/','/'),(2,'novel','novel_type','小说列表','/module/novel/type.php?pt={pt}&tid={tid}&page={page}','/{tid}/list/{page}.html','/?pt={pt}&module=novel&file=type&tid={tid}&page={page}','/?path=/novel/type/pt/{pt}/tid/{tid}/page/{page}','/index.php/novel/type/pt/{pt}/tid/{tid}/page/{page}','/novel/type/pt/{pt}/tid/{tid}/page/{page}'),(3,'novel','novel_info','小说介绍','/module/novel/info.php?pt={pt}&tid={tid}&nid={nid}','/{tid}/{nid}/info.html','/?module=novel&file=info&pt={pt}&tid={tid}&nid={nid}','/?path=/novel/info/pt/{pt}/tid/{tid}/nid/{nid}','/index.php/novel/info/pt/{pt}/tid/{tid}/nid/{nid}','/novel/info/pt/{pt}/tid/{tid}/nid/{nid}'),(4,'novel','novel_menu','小说目录','/module/novel/menu.php?pt={pt}&tid={tid}&nid={nid}&page={page}','/{tid}/{nid}/menu/{page}.html','/?module=novel&file=menu&pt={pt}&tid={tid}&nid={nid}&page={page}','/?path=/novel/menu/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}','/index.php/novel/menu/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}','/novel/menu/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}'),(5,'novel','novel_read','小说阅读','/module/novel/read.php?pt={pt}&tid={tid}&nid={nid}&cid={cid}','/{tid}/{nid}/read/{cid}.html','/?module=novel&file=read&pt={pt}&tid={tid}&nid={nid}&cid={cid}','/?path=/novel/read/pt/{pt}/tid/{tid}/nid/{nid}/cid/{cid}','/index.php/novel/read/pt/{pt}/tid/{tid}/nid/{nid}/cid/{cid}','/novel/read/pt/{pt}/tid/{tid}/nid/{nid}/cid/{cid}'),(6,'novel','novel_topindex','小说排行首页','/module/novel/topindex.php?pt={pt}','/top/index.html','/?module=novel&file=topindex&pt={pt}','/?path=/novel/topindex/pt/{pt}','/index.php/novel/topindex/pt/{pt}','/novel/topindex/pt/{pt}'),(7,'novel','novel_search','小说搜索','/module/novel/search.php?pt={pt}&type={type}&key={key}&page={page}','/search/{type}/{key}/{page}.html','/?module=novel&file=search&pt={pt}&type={type}&key={key}&page={page}','/?path=/novel/search/pt/{pt}/type/{type}/key/{key}/page/{page}','/index.php/novel/search/pt/{pt}/type/{type}/key/{key}/page/{page}','/novel/search/pt/{pt}/type/{type}/key/{key}/page/{page}'),(8,'novel','novel_toplist','小说排行列表','/module/novel/toplist.php?pt={pt}&tid={tid}&type={type}&page={page}','/top/list/{tid}/{type}/{page}.html','/?module=novel&file=toplist&pt={pt}&tid={tid}&type={type}&page={page}','/?path=/novel/toplist/pt/{pt}/tid/{tid}/type/{type}/page/{page}','/index.php/novel/toplist/pt/{pt}/tid/{tid}/type/{type}/page/{page}','/novel/toplist/pt/{pt}/tid/{tid}/type/{type}/page/{page}'),(9,'user','user_login','用户登录','/module/user/login.php?pt={pt}','/module/user/login.php?pt={pt}','/?module=user&file=login&pt={pt}','/?path=/user/login/pt/{pt}','/index.php/user/login/pt/{pt}','/user/login/pt/{pt}'),(10,'user','user_reg','用户注册','/module/user/reg.php?pt={pt}','/module/user/reg.php?pt={pt}','/?module=user&file=reg&pt={pt}','/?path=/user/reg/pt/{pt}','/index.php/user/reg/pt/{pt}','/user/reg/pt/{pt}'),(11,'user','user_getpsw','找回密码','/module/user/getpsw.php?pt={pt}','/module/user/getpsw.php?pt={pt}','/?module=user&file=getpsw&pt={pt}','/?path=/user/getpsw/pt/{pt}','/index.php/user/getpsw/pt/{pt}','/user/getpsw/pt/{pt}'),(12,'user','user_home','个人中心','/module/user/home.php?pt={pt}','/module/user/home.php?pt={pt}','/?module=user&file=home&pt={pt}','/?path=/user/home/pt/{pt}','/index.php/user/home/pt/{pt}','/user/home/pt/{pt}'),(13,'user','user_exit','退出登录','/module/user/exit.php?pt={pt}','/module/user/exit.php?pt={pt}','/?module=user&file=exit&pt={pt}','/?path=/user/exit/pt/{pt}','/index.php/user/exit/pt/{pt}','/user/exit/pt/{pt}'),(14,'user','user_basic','用户资料','/module/user/basic.php?pt={pt}','/module/user/basic.php?pt={pt}','/?module=user&file=basic&pt={pt}','/?path=/user/basic/pt/{pt}','/index.php/user/basic/pt/{pt}','/user/basic/pt/{pt}'),(16,'user','user_attribute','用户属性','/module/user/attribute.php?pt={pt}','/module/user/attribute.php?pt={pt}','/?module=user&file=attribute&pt={pt}','/?path=/user/attribute/pt/{pt}','/index.php/user/attribute/pt/{pt}','/user/attribute/pt/{pt}'),(18,'user','user_head','修改头像','/module/user/head.php?pt={pt}','/module/user/head.php?pt={pt}','/?module=user&file=head&pt={pt}','/?path=/user/head/pt/{pt}','/index.php/user/head/pt/{pt}','/user/head/pt/{pt}'),(19,'user','user_uppsw','修改密码','/module/user/uppsw.php?pt={pt}','/module/user/uppsw.php?pt={pt}','/?module=user&file=uppsw&pt={pt}','/?path=/user/uppsw/pt/{pt}','/index.php/user/uppsw/pt/{pt}','/user/uppsw/pt/{pt}'),(20,'user','user_bind','账号绑定','/module/user/bind.php?pt={pt}','/module/user/bind.php?pt={pt}','/?module=user&file=bind&pt={pt}','/?path=/user/bind/pt/{pt}','/index.php/user/bind/pt/{pt}','/user/bind/pt/{pt}'),(22,'user','user_coll','用户收藏等','/module/user/coll.php?module={module}&type={type}&page={page}&pt={pt}','/module/user/coll.php?module={module}&type={type}&page={page}&pt={pt}','/?module=user&file=coll&module={module}&type={type}&page={page}&pt={pt}','/?path=/user/coll/module/{module}/type/{type}/page/{page}/pt/{pt}','/index.php/user/coll/module/{module}/type/{type}/page/{page}/pt/{pt}','/user/coll/module/{module}/type/{type}/page/{page}/pt/{pt}'),(24,'novel','novel_replay','小说评论列表','/module/novel/replay.php?pt={pt}&tid={tid}&nid={nid}&page={page}','/{tid}/{nid}/replay/{page}.html','/?module=novel&file=replay&pt={pt}&tid={tid}&nid={nid}&page={page}','/?path=/novel/replay/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}','/index.php/novel/replay/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}','/novel/replay/pt/{pt}/tid/{tid}/nid/{nid}/page/{page}'),(26,'diy','diy_diy','自定义页','/module/diy/diy.php?pt={pt}&did={did}','/diy/{pinyin}/index.html','/?module=diy&file=diy&pt={pt}&did={did}','/?path=/diy/diy/pt/{pt}/did/{did}','/index.php/diy/diy/pt/{pt}/did/{did}','/diy/diy/pt/{pt}/did/{did}'),(27,'user','user_msglist','消息列表','/module/user/msglist.php?pt={pt}&page={page}','/module/user/msglist.php?pt={pt}&page={page}','/?module=user&file=msglist&pt={pt}&page={page}','/?path=/user/msglist/pt/{pt}/page/{page}','/index.php/user/msglist/pt/{pt}/page/{page}','/user/msglist/pt/{pt}/page/{page}'),(28,'user','user_msg','消息内容','/module/user/msg.php?pt={pt}&mid={mid}','/module/user/msg.php?pt={pt}&mid={mid}','/?module=user&file=msg&pt={pt}&mid={mid}','/?path=/user/msg/pt/{pt}/mid/{mid}','/index.php/user/msg/pt/{pt}/mid/{mid}','/user/msg/pt/{pt}/mid/{mid}'),(29,'author','author_index','作者首页','/module/author/index.php?pt={pt}','/module/author/index.php?pt={pt}','/?module=author&file=index&pt={pt}','/?path=/author/index/pt/{pt}','/index.php/author/index/pt/{pt}','/author/index/pt/{pt}'),(30,'author','author_novel_noveledit','新建小说','/module/author/novel_noveledit.php?pt={pt}&nid={nid}','/module/author/novel_noveledit.php?pt={pt}&nid={nid}','/?module=author&file=novel_noveledit&pt={pt}&nid={nid}','/?path=/author/novel_noveledit/pt/{pt}/nid/{nid}','/index.php/author/novel_noveledit/pt/{pt}/nid/{nid}','/author/novel_noveledit/pt/{pt}/nid/{nid}'),(31,'author','author_novel_novellist','小说管理','/module/author/novel_novellist.php?pt={pt}&page={page}','/module/author/novel_novellist.php?pt={pt}&page={page}','/?module=author&file=novel_novellist&pt={pt}&page={page}','/?path=/author/novel_novellist/pt/{pt}/page/{page}','/index.php/author/novel_novellist/pt/{pt}/page/{page}','/author/novel_novellist/pt/{pt}/page/{page}'),(32,'author','author_novel_volumelist','小说分卷列表','/module/author/novel_volumelist.php?pt={pt}&nid={nid}&page={page}','/module/author/novel_volumelist.php?pt={pt}&nid={nid}&page={page}','/?module=author&file=novel_volumelist&pt={pt}&nid={nid}&page={page}','/?path=/author/novel_volumelist/pt/{pt}/nid/{nid}/page/{page}','/index.php/author/novel_volumelist/pt/{pt}/nid/{nid}/page/{page}','/author/novel_volumelist/pt/{pt}/nid/{nid}/page/{page}'),(33,'author','author_createchapter','创建章节','/module/author/createchapter.php?pt={pt}&cid={cid}','/module/author/createchapter.php?pt={pt}&cid={cid}','/?module=author&file=createchapter&pt={pt}&cid={cid}','/?path=/author/createchapter/pt/{pt}/cid/{cid}','/index.php/author/createchapter/pt/{pt}/cid/{cid}','/author/createchapter/pt/{pt}/cid/{cid}'),(34,'author','author_novel_draftlist','小说草稿列表','/module/author/novel_draftlist.php?nid={nid}&page={page}&pt={pt}','/module/author/novel_draftlist.php?nid={nid}&page={page}&pt={pt}','/?module=author&file=novel_draftlist&nid={nid}&page={page}&pt={pt}','/?path=/author/novel_draftlist/nid/{nid}/page/{page}/pt/{pt}','/index.php/author/novel_draftlist/nid/{nid}/page/{page}/pt/{pt}','/author/novel_draftlist/nid/{nid}/page/{page}/pt/{pt}'),(36,'author','author_novel_draftedit','小说草稿编辑','/module/author/novel_draftedit.php?pt={pt}&nid={nid}&did={did}','/module/author/novel_draftedit.php?pt={pt}&nid={nid}&did={did}','/?module=author&file=novel_draftedit&pt={pt}&nid={nid}&did={did}','/?path=/author/novel_draftedit/pt/{pt}/nid/{nid}/did/{did}','/index.php/author/novel_draftedit/pt/{pt}/nid/{nid}/did/{did}','/author/novel_draftedit/pt/{pt}/nid/{nid}/did/{did}'),(37,'author','author_novel_chapterlist','小说章节列表','/module/author/novel_chapterlist.php?pt={pt}&nid={nid}&page={page}','/module/author/novel_chapterlist.php?pt={pt}&nid={nid}&page={page}','/?module=author&file=novel_chapterlist&pt={pt}&nid={nid}&page={page}','/?path=/author/novel_chapterlist/pt/{pt}/nid/{nid}/page/{page}','/index.php/author/novel_chapterlist/pt/{pt}/nid/{nid}/page/{page}','/author/novel_chapterlist/pt/{pt}/nid/{nid}/page/{page}'),(38,'zt','zt_zt','专题内容','/module/zt/zt.php?pt={pt}&zid={zid}','/module/zt/zt.php?pt={pt}&zid={zid}','/?module=zt&file=zt&pt={pt}&zid={zid}','/?path=/zt/zt/pt/{pt}/zid/{zid}','/index.php/zt/zt/pt/{pt}/zid/{zid}','/zt/zt/pt/{pt}/zid/{zid}'),(39,'zt','zt_type','专题分类','/module/zt/type.php?pt={pt}&tid={tid}&page={page}','/module/zt/type.php?pt={pt}&tid={tid}&page={page}','/?module=zt&file=type&pt={pt}&tid={tid}&page={page}','/?path=/zt/type/pt/{pt}/tid/{tid}/page/{page}','/index.php/zt/type/pt/{pt}/tid/{tid}/page/{page}','/zt/type/pt/{pt}/tid/{tid}/page/{page}'),(42,'user','user_fvistlist','好友访客列表','/module/user/fvistlist.php?pt={pt}&uid={uid}&page={page}','/module/user/fvistlist.php?pt={pt}&uid={uid}&page={page}','/?module=user&file=fvistlist&pt={pt}&uid={uid}&page={page}','/?path=/user/fvistlist/pt/{pt}/uid/{uid}/page/{page}','/index.php/user/fvistlist/pt/{pt}/uid/{uid}/page/{page}','/user/fvistlist/pt/{pt}/uid/{uid}/page/{page}'),(43,'author','author_basic','作者基本资料','/module/author/basic.php?pt={pt}','/module/author/basic.php?pt={pt}','/?module=author&file=basic&pt={pt}','/?path=/author/basic/pt/{pt}','/index.php/author/basic/pt/{pt}','/author/basic/pt/{pt}'),(44,'author','author_incomechapter','章节收入','/module/author/incomechapter.php?pt={pt}&page={page}','/module/author/incomechapter.php?pt={pt}&page={page}','/?module=author&file=incomechapter&pt={pt}&page={page}','/?path=/author/incomechapter/pt/{pt}/page/{page}','/index.php/author/incomechapter/pt/{pt}/page/{page}','/author/incomechapter/pt/{pt}/page/{page}'),(45,'author','author_incomedashang','打赏收入','/module/author/incomedashang.php?pt={pt}&page={page}','/module/author/incomedashang.php?pt={pt}&page={page}','/?module=author&file=incomedashang&pt={pt}&page={page}','/?path=/author/incomedashang/pt/{pt}/page/{page}','/index.php/author/incomedashang/pt/{pt}/page/{page}','/author/incomedashang/pt/{pt}/page/{page}'),(46,'author','author_mentionapply','提现申请','/module/author/mentionapply.php?pt={pt}','/module/author/mentionapply.php?pt={pt}','/?module=author&file=mentionapply&pt={pt}','/?path=/author/mentionapply/pt/{pt}','/index.php/author/mentionapply/pt/{pt}','/author/mentionapply/pt/{pt}'),(47,'author','author_mentionrecord','提现记录','/module/author/mentionrecord.php?pt={pt}&page={page}','/module/author/mentionrecord.php?pt={pt}&page={page}','/?module=author&file=mentionrecord&pt={pt}&page={page}','/?path=/author/mentionrecord/pt/{pt}/page/{page}','/index.php/author/mentionrecord/pt/{pt}/page/{page}','/author/mentionrecord/pt/{pt}/page/{page}'),(48,'article','article_type','文章列表','/module/article/type.php?pt={pt}&tid={tid}&page={page}','/module/article/type.php?pt={pt}&tid={tid}&page={page}','/?module=article&file=type&pt={pt}&tid={tid}&page={page}','/?path=/article/type/pt/{pt}/tid/{tid}/page/{page}','/index.php/article/type/pt/{pt}/tid/{tid}/page/{page}','/article/type/pt/{pt}/tid/{tid}/page/{page}'),(49,'article','article_article','文章内容','/module/article/article.php?pt={pt}&tid={tid}&aid={aid}','/module/article/article.php?pt={pt}&tid={tid}&aid={aid}','/?module=article&file=article&pt={pt}&tid={tid}&aid={aid}','/?path=/article/article/pt/{pt}/tid/{tid}/aid/{aid}','/index.php/article/article/pt/{pt}/tid/{tid}/aid/{aid}','/article/article/pt/{pt}/tid/{tid}/aid/{aid}'),(50,'article','article_search','文章搜索','/module/article/search.php?pt={pt}&key={key}&type={type}&page={page}','/module/article/search.php?pt={pt}&key={key}&type={type}&page={page}','/?module=article&file=search&pt={pt}&key={key}&type={type}&page={page}','/?path=/article/search/pt/{pt}/key/{key}/type/{type}/page/{page}','/index.php/article/search/pt/{pt}/key/{key}/type/{type}/page/{page}','/article/search/pt/{pt}/key/{key}/type/{type}/page/{page}'),(51,'article','article_replay','文章评论','/module/article/replay.php?pt={pt}&tid={tid}&aid={aid}&page={page}','/module/article/replay.php?pt={pt}&tid={tid}&aid={aid}&page={page}','/?module=article&file=replay&pt={pt}&tid={tid}&aid={aid}&page={page}','/?path=/article/replay/pt/{pt}/tid/{tid}/aid/{aid}/page/{page}','/index.php/article/replay/pt/{pt}/tid/{tid}/aid/{aid}/page/{page}','/article/replay/pt/{pt}/tid/{tid}/aid/{aid}/page/{page}'),(52,'novel','novel_index','小说首页','/module/novel/index.php?pt={pt}','/novel/index.html','/?module=novel&file=index&pt={pt}','/?path=/novel/index/pt/{pt}','/index.php/novel/index/pt/{pt}','/novel/index/pt/{pt}'),(54,'message','message_add','新增留言','/module/message/add.php?pt={pt}','/message/{pt}/add.html','/?module=message&file=add&pt={pt}','/?path=/message/add/pt/{pt}','/index.php/message/add/pt/{pt}','/message/add/pt/{pt}'),(55,'user','user_sign','用户签到','/module/user/sign.php?pt={pt}','/module/user/sign.php?pt={pt}','/?module=user&file=sign&pt={pt}','/?path=/user/sign/pt/{pt}','/index.php/user/sign/pt/{pt}','/user/sign/pt/{pt}'),(56,'bbs','bbs_index','论坛首页','/module/bbs/index.php?pt={pt}','/module/bbs/index.php?pt={pt}','/?module=bbs&file=index&pt={pt}','/?path=/bbs/index/pt/{pt}','/index.php/bbs/index/pt/{pt}','/bbs/index/pt/{pt}'),(57,'bbs','bbs_bbs','主题内容','/module/bbs/bbs.php?pt={pt}&tid={tid}&bid={bid}&page={page}','/module/bbs/bbs.php?pt={pt}&tid={tid}&bid={bid}&page={page}','/?module=bbs&file=bbs&pt={pt}&tid={tid}&bid={bid}&page={page}','/?path=/bbs/bbs/pt/{pt}/tid/{tid}/bid/{bid}/page/{page}','/index.php/bbs/bbs/pt/{pt}/tid/{tid}/bid/{bid}/page/{page}','/bbs/bbs/pt/{pt}/tid/{tid}/bid/{bid}/page/{page}'),(58,'bbs','bbs_type','板块分类','/module/bbs/type.php?pt={pt}&tid={tid}','/module/bbs/type.php?pt={pt}&tid={tid}','/?module=bbs&file=type&pt={pt}&tid={tid}','/?path=/bbs/type/pt/{pt}/tid/{tid}','/index.php/bbs/type/pt/{pt}/tid/{tid}','/bbs/type/pt/{pt}/tid/{tid}'),(59,'bbs','bbs_list','主题列表','/module/bbs/list.php?pt={pt}&tid={tid}&page={page}','/module/bbs/list.php?pt={pt}&tid={tid}&page={page}','/?module=bbs&file=list&pt={pt}&tid={tid}&page={page}','/?path=/bbs/list/pt/{pt}/tid/{tid}/page/{page}','/index.php/bbs/list/pt/{pt}/tid/{tid}/page/{page}','/bbs/list/pt/{pt}/tid/{tid}/page/{page}'),(83,'article','article_tindex','文章分类首页','/module/article/tindex.php?pt={pt}&tid={tid}','/module/article/tindex.php?pt={pt}&tid={tid}','/?module=article&file=tindex&pt={pt}&tid={tid}','/?path=/article/tindex/pt/{pt}/tid/{tid}','/index.php/article/tindex/pt/{pt}/tid/{tid}','/article/tindex/pt/{pt}/tid/{tid}'),(61,'bbs','bbs_post','发表主题','/module/bbs/post.php?pt={pt}&tid={tid}&bid={bid}','/module/bbs/post.php?pt={pt}&tid={tid}&bid={bid}','/?module=bbs&file=post&pt={pt}&tid={tid}&bid={bid}','/?path=/bbs/post/pt/{pt}/tid/{tid}/bid/{bid}','/index.php/bbs/post/pt/{pt}/tid/{tid}/bid/{bid}','/bbs/post/pt/{pt}/tid/{tid}/bid/{bid}'),(62,'link','link_index','友链首页','/module/link/index.php?pt={pt}','/module/link/index.php?pt={pt}','/?module=link&file=index&pt={pt}','/?path=/link/index/pt/{pt}','/index.php/link/index/pt/{pt}','/link/index/pt/{pt}'),(63,'link','link_show','友链展示','/module/link/show.php?pt={pt}&tid={tid}&lid={lid}','/module/link/show.php?pt={pt}&tid={tid}&lid={lid}','/?module=link&file=show&pt={pt}&tid={tid}&lid={lid}','/?path=/link/show/pt/{pt}/tid/{tid}/lid/{lid}','/index.php/link/show/pt/{pt}/tid/{tid}/lid/{lid}','/link/show/pt/{pt}/tid/{tid}/lid/{lid}'),(64,'link','link_link','友链点击','/module/link/click.php?lid={lid}&t={t}','/module/link/click.php?lid={lid}&t={t}','/?module=link&file=click&lid={lid}&t={t}','/?path=/link/click/lid/{lid}/t/{t}','/index.php/link/click/lid/{lid}/t/{t}','/link/click/lid/{lid}/t/{t}'),(65,'link','link_type','友链分类列表','/module/link/type.php?pt={pt}&tid={tid}&page={page}','/module/link/type.php?pt={pt}&tid={tid}&page={page}','/?module=link&file=type&pt={pt}&tid={tid}&page={page}','/?path=/link/type/pt/{pt}/tid/{tid}/page/{page}','/index.php/link/type/pt/{pt}/tid/{tid}/page/{page}','/link/type/pt/{pt}/tid/{tid}/page/{page}'),(66,'link','link_join','申请友链','/module/link/join.php?pt={pt}','/module/link/join.php?pt={pt}','/?module=link&file=join&pt={pt}','/?path=/link/join/pt/{pt}','/index.php/link/join/pt/{pt}','/link/join/pt/{pt}'),(67,'app','app_type','应用分类列表','/module/app/type.php?pt={pt}&tid={tid}&page={page}','/module/app/type.php?pt={pt}&tid={tid}&page={page}','/?module=app&file=type&pt={pt}&tid={tid}&page={page}','/?path=/app/type/pt/{pt}/tid/{tid}/page/{page}','/index.php/app/type/pt/{pt}/tid/{tid}/page/{page}','/app/type/pt/{pt}/tid/{tid}/page/{page}'),(111,'app','app_type_retrieval','应用列表筛选','/module/app/type.php?pt={pt}&tid={tid}&page={page}&rec={rec}&lang={lang}&cost={cost}&size={size}&platform={platform}&order={order}','/{tid}/list/{rec}_{lang}_{cost}_{size}_{platform}_{order}_{page}.html','/?module=app&file=type&pt={pt}&tid={tid}&page={page}&rec={rec}&lang={lang}&cost={cost}&size={size}&platform={platform}&order={order}','/?path=/app/type/pt/{pt}/tid/{tid}/page/{page}/rec/{rec}/lang/{lang}/cost/{cost}/size/{size}/platform/{platform}/order/{order}','/index.php/app/type/pt/{pt}/tid/{tid}/page/{page}/rec/{rec}/lang/{lang}/cost/{cost}/size/{size}/platform/{platform}/order/{order}','/app/type/pt/{pt}/tid/{tid}/page/{page}/rec/{rec}/lang/{lang}/cost/{cost}/size/{size}/platform/{platform}/order/{order}'),(68,'app','app_app','应用内容','/module/app/app.php?pt={pt}&tid={tid}&aid={aid}','/module/app/app.php?pt={pt}&tid={tid}&aid={aid}','/?module=app&file=app&pt={pt}&tid={tid}&aid={aid}','/?path=/app/app/pt/{pt}/tid/{tid}/aid/{aid}','/index.php/app/app/pt/{pt}/tid/{tid}/aid/{aid}','/app/app/pt/{pt}/tid/{tid}/aid/{aid}'),(69,'app','app_index','应用首页','/module/app/index.php?pt={pt}','/module/app/index.php?pt={pt}','/?module=app&file=index&pt={pt}','/?path=/app/index/pt/{pt}','/index.php/app/index/pt/{pt}','/app/index/pt/{pt}'),(70,'down','down_down','下载内容','/module/down/down.php?pt={pt}&module={module}&fid={fid}&cid={cid}','/module/down/down.php?pt={pt}&module={module}&fid={fid}&cid={cid}','/?module=down&file=down&pt={pt}&module={module}&fid={fid}&cid={cid}','/?path=/down/down/pt/{pt}/module/{module}/fid/{fid}/cid/{cid}','/index.php/down/down/pt/{pt}/module/{module}/fid/{fid}/cid/{cid}','/down/down/pt/{pt}/module/{module}/fid/{fid}/cid/{cid}'),(71,'app','app_search','应用搜索','/module/app/search.php?pt={pt}&type={type}&key={key}&page={page}','/module/app/search.php?pt={pt}&type={type}&key={key}&page={page}','/?module=app&file=search&pt={pt}&type={type}&key={key}&page={page}','/?path=/app/search/pt/{pt}/type/{type}/key/{key}/page/{page}','/index.php/app/search/pt/{pt}/type/{type}/key/{key}/page/{page}','/app/search/pt/{pt}/type/{type}/key/{key}/page/{page}'),(72,'article','article_index','文章首页','/module/article/index.php?pt={pt}','/html/article/index_{pt}.html','/?module=article&file=index&pt={pt}','/?path=/article/index/pt/{pt}','/index.php/article/index/pt/{pt}','/article/index/pt/{pt}'),(73,'bbs','bbs_search','论坛搜索','/module/bbs/search.php?pt={pt}&key={key}&type={type}&page={page}','/module/bbs/search.php?pt={pt}&key={key}&type={type}&page={page}','/?module=bbs&file=search&pt={pt}&key={key}&type={type}&page={page}','/?path=/bbs/search/pt/{pt}/key/{key}/type/{type}/page/{page}','/index.php/bbs/search/pt/{pt}/key/{key}/type/{type}/page/{page}','/bbs/search/pt/{pt}/key/{key}/type/{type}/page/{page}'),(74,'about','about_type','关于信息列表','/module/about/type.php?pt={pt}&tid={tid}','/module/about/type.php?pt={pt}&tid={tid}','/?module=about&file=type&pt={pt}&tid={tid}','/?path=/about/type/pt/{pt}/tid/{tid}','/index.php/about/type/pt/{pt}/tid/{tid}','/about/type/pt/{pt}/tid/{tid}'),(75,'about','about_about','关于信息内容','/module/about/about.php?pt={pt}&aid={aid}','/module/about/about.php?pt={pt}&aid={aid}','/?module=about&file=about&pt={pt}&aid={aid}','/?path=/about/about/pt/{pt}/aid/{aid}','/index.php/about/about/pt/{pt}/aid/{aid}','/about/about/pt/{pt}/aid/{aid}'),(76,'picture','picture_type','图集列表','/module/picture/type.php?pt={pt}&tid={tid}&page={page}','/module/picture/type.php?pt={pt}&tid={tid}&page={page}','/?module=picture&file=type&pt={pt}&tid={tid}&page={page}','/?path=/picture/type/pt/{pt}/tid/{tid}/page/{page}','/index.php/picture/type/pt/{pt}/tid/{tid}/page/{page}','/picture/type/pt/{pt}/tid/{tid}/page/{page}'),(77,'picture','picture_picture','图集内容','/module/picture/picture.php?pt={pt}&tid={tid}&pid={pid}&page={page}','/module/picture/picture.php?pt={pt}&tid={tid}&pid={pid}&page={page}','/?module=picture&file=picture&pt={pt}&tid={tid}&pid={pid}&page={page}','/?path=/picture/picture/pt/{pt}/tid/{tid}/pid/{pid}/page/{page}','/index.php/picture/picture/pt/{pt}/tid/{tid}/pid/{pid}/page/{page}','/picture/picture/pt/{pt}/tid/{tid}/pid/{pid}/page/{page}'),(78,'picture','picture_search','图集搜索','/module/picture/search.php?pt={pt}&key={key}&type={type}&page={page}','/module/picture/search.php?pt={pt}&key={key}&type={type}&page={page}','/?module=picture&file=search&pt={pt}&key={key}&type={type}&page={page}','/?path=/picture/search/pt/{pt}/key/{key}/type/{type}/page/{page}','/index.php/picture/search/pt/{pt}/key/{key}/type/{type}/page/{page}','/picture/search/pt/{pt}/key/{key}/type/{type}/page/{page}'),(79,'picture','picture_replay','图集评论','/module/picture/replay.php?pt={pt}&tid={tid}&cid={cid}&page={page}','/module/picture/replay.php?pt={pt}&tid={tid}&cid={cid}&page={page}','/?module=picture&file=replay&pt={pt}&tid={tid}&cid={cid}&page={page}','/?path=/picture/replay/pt/{pt}/tid/{tid}/cid/{cid}/page/{page}','/index.php/picture/replay/pt/{pt}/tid/{tid}/cid/{cid}/page/{page}','/picture/replay/pt/{pt}/tid/{tid}/cid/{cid}/page/{page}'),(80,'picture','picture_toplist','图集排行列表','/module/picture/toplist.php?pt={pt}&tid={tid}&page={page}','/module/picture/toplist.php?pt={pt}&tid={tid}&page={page}','/?module=picture&file=toplist&pt={pt}&tid={tid}&page={page}','/?path=/picture/toplist/pt/{pt}/tid/{tid}/page/{page}','/index.php/picture/toplist/pt/{pt}/tid/{tid}/page/{page}','/picture/toplist/pt/{pt}/tid/{tid}/page/{page}'),(81,'user','user_signlist','签到列表','/module/user/signlist.php?pt={pt}&page={page}','/module/user/signlist.php?pt={pt}&page={page}','/?module=user&file=signlist&pt={pt}&page={page}','/?path=/user/signlist/pt/{pt}/page/{page}','/index.php/user/signlist/pt/{pt}/page/{page}','/user/signlist/pt/{pt}/page/{page}'),(23,'user','user_fhome','好友资料','/module/user/fhome.php?pt={pt}&uid={uid}','/module/user/fhome.php?pt={pt}&uid={uid}','/?module=user&file=fhome&pt={pt}&uid={uid}','/?path=/user/fhome/pt/{pt}/uid/{uid}','/index.php/user/fhome/pt/{pt}/uid/{uid}','/user/fhome/pt/{pt}/uid/{uid}'),(82,'user','user_apilogin','接口登录','/module/user/apilogin.php','/module/user/apilogin.php','/?module=user&file=apilogin','/?path=/user/apilogin','/index.php/user/apilogin','/user/apilogin'),(84,'picture','picture_index','图集首页','/module/picture/index.php?pt={pt}','/module/picture/index.php?pt={pt}','/?module=picture&file=index&pt={pt}','/?path=/picture/index/pt/{pt}','/index.php/picture/index/pt/{pt}','/picture/index/pt/{pt}'),(85,'user','user_fcoll','好友收藏等','/module/user/fcoll.php?module={module}&type={type}&page={page}&pt={pt}&uid={uid}','/module/user/fcoll.php?module={module}&type={type}&page={page}&pt={pt}&uid={uid}','/?module=user&file=fcoll&module={module}&type={type}&page={page}&pt={pt}&uid={uid}','/?path=/user/fcoll/module/{module}/type/{type}/page/{page}/pt/{pt}/uid/{uid}','/index.php/user/fcoll/module/{module}/type/{type}/page/{page}/pt/{pt}/uid/{uid}','/user/fcoll/module/{module}/type/{type}/page/{page}/pt/{pt}/uid/{uid}'),(86,'novel','novel_tindex','小说分类首页','/module/novel/tindex.php?pt={pt}&tid={tid}','/{tid}/index.html','/?module=novel&file=tindex&pt={pt}&tid={tid}','/?path=/novel/tindex/pt/{pt}/tid/{tid}','/index.php/novel/tindex/pt/{pt}/tid/{tid}','/novel/tindex/pt/{pt}/tid/{tid}'),(87,'author','author_apply','申请作家','/module/author/apply.php?pt={pt}','/module/author/apply.php?pt={pt}','/?module=author&file=apply&pt={pt}','/?path=/author/apply/pt/{pt}','/index.php/author/apply/pt/{pt}','/author/apply/pt/{pt}'),(88,'author','author_agreement','申请作家协议','/module/author/agreement.php?pt={pt}','/module/author/agreement.php?pt={pt}','/?module=author&file=agreement&pt={pt}','/?path=/author/agreement/pt/{pt}','/index.php/author/agreement/pt/{pt}','/author/agreement/pt/{pt}'),(89,'author','author_novel_volumeedit','小说分卷编辑','/module/author/novel_volumeedit.php?pt={pt}&nid={nid}&vid={vid}','/module/author/novel_volumeedit.php?pt={pt}&nid={nid}&vid={vid}','/?module=author&file=novel_volumeedit&pt={pt}&nid={nid}&vid={vid}','/?path=/author/novel_volumeedit/pt/{pt}/nid/{nid}/vid/{vid}','/index.php/author/novel_volumeedit/pt/{pt}/nid/{nid}/vid/{vid}','/author/novel_volumeedit/pt/{pt}/nid/{nid}/vid/{vid}'),(90,'author','author_article_articlelist','文章投稿列表','/module/author/article_articlelist.php?pt={pt}&page={page}','/module/author/article_articlelist.php?pt={pt}&page={page}','/?module=author&file=article_articlelist&pt={pt}&page={page}','/?path=/author/article_articlelist/pt/{pt}/page/{page}','/index.php/author/article_articlelist/pt/{pt}/page/{page}','/author/article_articlelist/pt/{pt}/page/{page}'),(91,'author','author_article_draftedit','文章草稿箱列表','/module/author/article_draftedit.php?did={did}&pt={pt}','/module/author/article_draftedit.php?did={did}&pt={pt}','/?module=author&file=article_draftedit&did={did}&pt={pt}','/?path=/author/article_draftedit/did/{did}/pt/{pt}','/index.php/author/article_draftedit/did/{did}/pt/{pt}','/author/article_draftedit/did/{did}/pt/{pt}'),(92,'author','author_article_draftlist','文章草稿列表','/module/author/article_draftlist.php?page={page}&pt={pt}','/module/author/article_draftlist.php?page={page}&pt={pt}','/?module=author&file=article_draftlist&page={page}&pt={pt}','/?path=/author/article_draftlist/page/{page}/pt/{pt}','/index.php/author/article_draftlist/page/{page}/pt/{pt}','/author/article_draftlist/page/{page}/pt/{pt}'),(93,'author','author_article_articleedit','文章编辑','/module/author/article_articleedit.php?id={id}&pt={pt}','/module/author/article_articleedit.php?id={id}&pt={pt}','/?module=author&file=article_articleedit&id={id}&pt={pt}','/?path=/author/article_articleedit/id/{id}/pt/{pt}','/index.php/author/article_articleedit/id/{id}/pt/{pt}','/author/article_articleedit/id/{id}/pt/{pt}'),(94,'sitemap','sitemap_html_index','HTML地图','/wmcms/module/sitemap/index.php?type=html','/html/sitemap/index.html','/wmcms/module/sitemap/index.php?type=html','/wmcms/module/sitemap/index.php?type=html','/wmcms/module/sitemap/index.php?type=html','/wmcms/module/sitemap/index.php?type=html'),(95,'sitemap','sitemap_xml_index','XML地图','/wmcms/module/sitemap/index.php?type=xml','/html/sitemap/sitemap.xml','/wmcms/module/sitemap/index.php?type=xml','/wmcms/module/sitemap/index.php?type=xml','/wmcms/module/sitemap/index.php?type=xml','/wmcms/module/sitemap/index.php?type=xml'),(96,'sitemap','sitemap_rss_index','RSS地图','/wmcms/module/sitemap/index.php?type=rss','/html/sitemap/rss.html','/wmcms/module/sitemap/index.php?type=rss','/wmcms/module/sitemap/index.php?type=rss','/wmcms/module/sitemap/index.php?type=rss','/wmcms/module/sitemap/index.php?type=rss'),(97,'sitemap','sitemap_rss_list','网站地图列表','/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}','/html/sitemap/rss/{module}/{tid}.xml','/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}','/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}','/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}','/wmcms/module/sitemap/list.php?type={type}&module={module}&tid={tid}'),(98,'user','user_charge','在线充值','/module/user/charge.php?pt={pt}','/module/user/charge.php?pt={pt}','/?module=user&file=charge&pt={pt}','/?path=/user/charge/pt/{pt}','/index.php/user/charge/pt/{pt}','/user/charge/pt/{pt}'),(99,'author','author_novel_incomelist','小说收入列表','/module/author/novel_incomelist.php?type={type}&page={page}&pt={pt}','/module/author/novel_incomelist.php?type={type}&page={page}&pt={pt}','/?module=author&file=novel_incomelist&type={type}&page={page}&pt={pt}','/?path=/author/novel_incomelist/type/{type}/page/{page}/pt/{pt}','/index.php/author/novel_incomelist/type/{type}/page/{page}/pt/{pt}','/author/novel_incomelist/type/{type}/page/{page}/pt/{pt}'),(100,'user','user_cash_apply','在线申请提现','/module/user/cash_apply.php?pt={pt}','/module/user/cash_apply.php?pt={pt}','/?module=user&file=cash_apply&pt={pt}','/?path=/user/cash_apply/pt/{pt}','/index.php/user/cash_apply/pt/{pt}','/user/cash_apply/pt/{pt}'),(101,'user','user_cash_list','提现申请记录','/module/user/cash_list.php?page={page}&pt={pt}','/module/user/cash_list.php?page={page}&pt={pt}','/?module=user&file=cash_list&page={page}&pt={pt}','/?path=/user/cash_list/page/{page}/pt/{pt}','/index.php/user/cash_list/page/{page}/pt/{pt}','/user/cash_list/page/{page}/pt/{pt}'),(102,'about','about_tindex','关于首页','/module/about/tindex.php?pt={pt}&tid={tid}','/module/about/tindex.php?pt={pt}&tid={tid}','/?module=about&file=tindex&pt={pt}&tid={tid}','/?path=/about/tindex/pt/{pt}/tid/{tid}','/index.php/about/tindex/pt/{pt}/tid/{tid}','/about/tindex/pt/{pt}/tid/{tid}'),(103,'sitemap','sitemap_site_index','结构化数据','/wmcms/module/sitemap/index.php?type=site','/html/sitemap/site.xml','/wmcms/module/sitemap/index.php?type=site','/wmcms/module/sitemap/index.php?type=site','/wmcms/module/sitemap/index.php?type=site','/wmcms/module/sitemap/index.php?type=site'),(104,'novel','novel_type_retrieval','小说列表筛选','/module/novel/type.php?pt={pt}&tid={tid}&page={page}&process={process}&word={word}&chapter={chapter}&copy={copy}&cost={cost}&letter={letter}&order={order}','/{tid}/list/{process}_{word}_{chapter}_{copy}_{cost}_{letter}_{order}_{page}.html','/?module=novel&file=type&pt={pt}&tid={tid}&page={page}&process={process}&word={word}&chapter={chapter}&copy={copy}&cost={cost}&letter={letter}&order={order}','/?path=/novel/type/pt/{pt}/tid/{tid}/page/{page}/process/{process}/word/{word}/chapter/{chapter}/copy/{copy}/cost/{cost}/letter/{letter}/order/{order}','/index.php/novel/type/pt/{pt}/tid/{tid}/page/{page}/process/{process}/word/{word}/chapter/{chapter}/copy/{copy}/cost/{cost}/letter/{letter}/order/{order}','/novel/type/pt/{pt}/tid/{tid}/page/{page}/process/{process}/word/{word}/chapter/{chapter}/copy/{copy}/cost/{cost}/letter/{letter}/order/{order}'),(105,'novel','novel_index_boy','男生小说首页','/module/novel/index_boy.php?pt={pt}','/index_boy.html','/?module=novel&file=index_boy&pt={pt}','/?path=/novel/index_boy/pt/{pt}','/index.php/novel/index_boy/pt/{pt}','/novel/index_boy/pt/{pt}'),(106,'novel','novel_index_girl','女生小说首页','/module/novel/index_girl.php?pt={pt}','/index_girl.html','/?module=novel&file=index_girl&pt={pt}','/?path=/novel/index_girl/pt/{pt}','/index.php/novel/index_girl/pt/{pt}','/novel/index_girl/pt/{pt}'),(107,'user','user_read','阅读记录','/module/user/read.php?pt={pt}&module={module}&page={page}','/module/user/read.php?pt={pt}&module={module}&page={page}','/?module=user&file=read&pt={pt}&module={module}&page={page}','/?path=/user/read/pt/{pt}/module/{module}/page/{page}','/index.php/user/read/pt/{pt}/module/{module}/page/{page}','/user/read/pt/{pt}/module/{module}/page/{page}'),(108,'user','user_charge_code','在线扫码支付','/module/user/charge_code.php?pt={pt}&code={code}&sn={sn}','/module/user/charge_code.php?pt={pt}&code={code}&sn={sn}','/?module=user&file=charge_code&pt={pt}&code={code}&sn={sn}','/?path=/user/charge_code/pt/{pt}/code/{code}/sn/{sn}','/index.php/user/charge_code/pt/{pt}/code/{code}/sn/{sn}','/user/charge_code/pt/{pt}/code/{code}/sn/{sn}'),(109,'user','user_charge_success','支付成功','/module/user/charge_success.php?pt={pt}','/module/user/charge_success.php?pt={pt}','/?module=user&file=charge_success&pt={pt}','/?path=/user/charge_success/pt/{pt}','/index.php/user/charge_success/pt/{pt}','/user/charge_success/pt/{pt}'),(110,'replay','replay_list','评论列表','/module/replay/list.php?pt={pt}&module={module}&page={page}','/module/replay/list.php?pt={pt}&module={module}&page={page}','/?module=replay&file=list&pt={pt}&module={module}&page={page}','/?path=/replay/list/pt/{pt}/module/{module}/page/{page}','/index.php/replay/list/pt/{pt}/module/{module}/page/{page}','/replay/list/pt/{pt}/module/{module}/page/{page}'),(112,'picture','picture_tindex','图集分类首页','/module/picture/tindex.php?pt={pt}&tid={tid}','/{tid}/index.html','/?module=picture&file=tindex&pt={pt}&tid={tid}','/?path=/picture/tindex/pt/{pt}/tid/{tid}','/index.php/picture/tindex/pt/{pt}/tid/{tid}','/picture/tindex/pt/{pt}/tid/{tid}'),(113,'author','author_author','作者个人中心','/module/author/author.php?pt={pt}&aid={aid}','/module/author/author.php?pt={pt}&aid={aid}','/module/author/author.php?pt={pt}&aid={aid}','/?path=/author/author/pt/{pt}/aid/{aid}','/index.php/author/author/pt/{pt}/aid/{aid}','/author/author/pt/{pt}/aid/{aid}'),(114,'author','author_novel_statistics','小说数据统计','/module/author/novel_statistics.php?pt={pt}','/module/author/novel_statistics.php?pt={pt}','/?module=author&file=novel_statistics&pt={pt}','/?path=/author/novel_statistics/pt/{pt}','/index.php/author/novel_statistics/pt/{pt}','/author/novel_statistics/pt/{pt}'),(129,'editor','editor_basic','编辑基本资料','/module/editor/basic.php?pt={pt}','/module/editor/basic.php?pt={pt}','/?module=editor&file=basic&pt={pt}','/?path=/editor/basic/pt/{pt}','/index.php/editor/basic/pt/{pt}','/editor/basic/pt/{pt}'),(125,'editor','editor_index','编辑首页','/module/editor/index.php?pt={pt}','/module/editor/index.php?pt={pt}','/?module=editor&file=index&pt={pt}','/?path=/editor/index/pt/{pt}','/index.php/editor/index/pt/{pt}','/editor/index/pt/{pt}'),(126,'editor','editor_novel','编辑新书审核','/module/editor/novel.php?page={page}&status={status}&pt={pt}','/module/editor/novel.php?page={page}&status={status}&pt={pt}','/?module=editor&file=novel&page={page}&status={status}&pt={pt}','/?path=/editor/novel/page/{page}/status/{status}/pt/{pt}','/index.php/editor/novel/page/{page}/status/{status}/pt/{pt}','/editor/novel/page/{page}/status/{status}/pt/{pt}'),(127,'editor','editor_novel_cover','编辑封面审核','/module/editor/novel_cover.php?page={page}&status={status}&pt={pt}','/module/editor/novel_cover.php?page={page}&status={status}&pt={pt}','/?module=editor&file=novel_cover&page={page}&status={status}&pt={pt}','/?path=/editor/novel_cover/page/{page}/status/{status}/pt/{pt}','/index.php/editor/novel_cover/page/{page}/status/{status}/pt/{pt}','/editor/novel_cover/page/{page}/status/{status}/pt/{pt}'),(128,'editor','editor_novel_chapter','编辑章节审核','/module/editor/novel_chapter.php?page={page}&status={status}&pt={pt}','/module/editor/novel_chapter.php?page={page}&status={status}&pt={pt}','/?module=editor&file=novel_chapter&page={page}&status={status}&pt={pt}','/?path=/editor/novel_chapter/page/{page}/status/{status}/pt/{pt}','/index.php/editor/novel_chapter/page/{page}/status/{status}/pt/{pt}','/editor/novel_chapter/page/{page}/status/{status}/pt/{pt}');

/*Table structure for table `wm_site_product` */

DROP TABLE IF EXISTS `wm_site_product`;

CREATE TABLE `wm_site_product` (
  `product_id` int(4) NOT NULL AUTO_INCREMENT,
  `product_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否可用',
  `product_title` varchar(20) NOT NULL COMMENT '站点名字',
  `product_domain` varchar(100) NOT NULL COMMENT '域名',
  `product_admin` varchar(80) NOT NULL COMMENT '后台文件夹',
  `product_name` varchar(20) NOT NULL COMMENT '后台登录账号',
  `product_psw` varchar(50) NOT NULL COMMENT '后台登录密码',
  `product_order` int(4) NOT NULL DEFAULT '99' COMMENT '显示排序，越小越靠前',
  `product_time` int(4) NOT NULL COMMENT '创建时间',
  PRIMARY KEY (`product_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='产品线站群表';

/*Data for the table `wm_site_product` */

/*Table structure for table `wm_site_site` */

DROP TABLE IF EXISTS `wm_site_site`;

CREATE TABLE `wm_site_site` (
  `site_id` int(4) NOT NULL AUTO_INCREMENT,
  `site_status` tinyint(1) DEFAULT '1' COMMENT '是否可用',
  `site_title` varchar(30) DEFAULT NULL COMMENT '站点名字',
  `site_domain` varchar(30) DEFAULT NULL COMMENT '站点域名',
  `site_domain_type` tinyint(1) DEFAULT '1' COMMENT '域名类型，1为单域名，2为泛解析',
  `site_type` tinyint(1) DEFAULT '1' COMMENT '站点类型，1为数据独享站群，2为数据共享站群(泛解析时必为2)',
  `site_template` varchar(30) DEFAULT NULL COMMENT '使用的模版文件夹名字',
  `site_order` int(4) DEFAULT '99' COMMENT '排序',
  `site_time` int(4) DEFAULT '0' COMMENT '创建的时间',
  PRIMARY KEY (`site_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='站群站内站点表';

/*Data for the table `wm_site_site` */

/*Table structure for table `wm_system_apply` */

DROP TABLE IF EXISTS `wm_system_apply`;

CREATE TABLE `wm_system_apply` (
  `apply_id` int(4) NOT NULL AUTO_INCREMENT,
  `apply_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为申请中，1为通过，2为拒绝',
  `apply_module` varchar(20) NOT NULL COMMENT '申请的模块',
  `apply_type` varchar(20) NOT NULL COMMENT '模块中申请的类型',
  `apply_uid` int(4) NOT NULL COMMENT '用户id或者作者id',
  `apply_cid` int(4) NOT NULL DEFAULT '0' COMMENT '所属内容的id',
  `apply_pid` int(11) DEFAULT '0' COMMENT '内容父id',
  `apply_createtime` int(4) NOT NULL COMMENT '申请时间',
  `apply_manager_id` int(4) NOT NULL DEFAULT '0' COMMENT '操作的管理员',
  `apply_editor_id` int(11) DEFAULT '0' COMMENT '操作的编辑',
  `apply_updatetime` int(4) NOT NULL DEFAULT '0' COMMENT '处理的时间',
  `apply_remark` varchar(200) NOT NULL COMMENT '处理备注',
  `apply_option` mediumtext COMMENT '特殊的数据',
  PRIMARY KEY (`apply_id`),
  KEY `status_index` (`apply_status`),
  KEY `module_index` (`apply_module`),
  KEY `type_index` (`apply_type`),
  KEY `uid_index` (`apply_uid`),
  KEY `cid_index` (`apply_cid`),
  KEY `editor_index` (`apply_editor_id`),
  KEY `pid_index` (`apply_pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='全系统申请记录表';

/*Data for the table `wm_system_apply` */

/*Table structure for table `wm_system_competence` */

DROP TABLE IF EXISTS `wm_system_competence`;

CREATE TABLE `wm_system_competence` (
  `comp_id` int(4) NOT NULL AUTO_INCREMENT,
  `comp_name` varchar(20) NOT NULL COMMENT '权限名',
  `comp_site` varchar(5000) NOT NULL DEFAULT '0' COMMENT '账号管理站点权限，0为所有站点',
  `comp_content` varchar(5000) NOT NULL COMMENT '权限内容',
  PRIMARY KEY (`comp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统权限表';

/*Data for the table `wm_system_competence` */

/*Table structure for table `wm_system_domain` */

DROP TABLE IF EXISTS `wm_system_domain`;

CREATE TABLE `wm_system_domain` (
  `domain_id` int(4) NOT NULL AUTO_INCREMENT,
  `domain_title` varchar(10) NOT NULL COMMENT '模块标题',
  `domain_name` varchar(10) NOT NULL COMMENT '模块名字',
  `domain_domain` varchar(30) NOT NULL COMMENT '模块绑定域名',
  `domain_index` varchar(30) DEFAULT NULL COMMENT '模块首页',
  `domain_order` int(4) DEFAULT '9' COMMENT '域名排序',
  PRIMARY KEY (`domain_id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 COMMENT='模块绑定域名表';

/*Data for the table `wm_system_domain` */

insert  into `wm_system_domain`(`domain_id`,`domain_title`,`domain_name`,`domain_domain`,`domain_index`,`domain_order`) values (1,'文章模块','article','','index',9),(2,'论坛模块','bbs','','index',9),(3,'应用模块','app','','index',9),(4,'用户模块','user','','homne',9),(5,'友链模块','link','','index',9),(6,'原创模块','author','','index',9),(7,'小说模块','novel','','index',9);

/*Table structure for table `wm_system_email` */

DROP TABLE IF EXISTS `wm_system_email`;

CREATE TABLE `wm_system_email` (
  `email_id` int(4) NOT NULL AUTO_INCREMENT,
  `email_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '邮件服务使用状态，0为禁用，1为启用',
  `email_type` tinyint(1) DEFAULT '1' COMMENT '邮箱类型，1为smtp，2为sendmail,3为php smtp函数',
  `email_smtp` varchar(20) DEFAULT NULL COMMENT 'smtp服务器',
  `email_port` varchar(10) DEFAULT NULL COMMENT 'smtp端口',
  `email_name` varchar(30) DEFAULT NULL COMMENT '邮箱登录账号',
  `email_psw` varchar(50) DEFAULT NULL COMMENT '邮箱登录密码',
  `email_send` varchar(30) DEFAULT NULL COMMENT '发信账户',
  PRIMARY KEY (`email_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='邮件服务配置';

/*Data for the table `wm_system_email` */

insert  into `wm_system_email`(`email_id`,`email_status`,`email_type`,`email_smtp`,`email_port`,`email_name`,`email_psw`,`email_send`) values (1,0,1,'smtp.163.com','25','user@163.com','1TFfVrRCnfnximMmLHE','user@163.com');

/*Table structure for table `wm_system_email_log` */

DROP TABLE IF EXISTS `wm_system_email_log`;

CREATE TABLE `wm_system_email_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_status` tinyint(1) DEFAULT '0' COMMENT '使用状态:0=未使用,1已使用,2=已过期',
  `log_send` tinyint(1) DEFAULT '0' COMMENT '发送状态:1=成功,0=失败,2=等待发送',
  `log_type` varchar(20) DEFAULT NULL COMMENT '信件类型',
  `log_sender` varchar(50) NOT NULL COMMENT '发信账户',
  `log_receive` varchar(50) NOT NULL COMMENT '收信账户',
  `log_title` varchar(200) NOT NULL COMMENT '邮件主题',
  `log_content` text NOT NULL COMMENT '邮件正文',
  `log_remark` varchar(500) DEFAULT '发送成功' COMMENT '备注信息',
  `log_time` int(11) DEFAULT '0' COMMENT '邮件任务创建的时间',
  `log_sendtime` int(11) DEFAULT '0' COMMENT '邮件发送的时间',
  `log_exptime` int(11) DEFAULT '0' COMMENT '邮件的有效期',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件任务记录日志表';

/*Data for the table `wm_system_email_log` */

/*Table structure for table `wm_system_email_temp` */

DROP TABLE IF EXISTS `wm_system_email_temp`;

CREATE TABLE `wm_system_email_temp` (
  `temp_id` varchar(20) NOT NULL COMMENT '模版的id',
  `temp_status` tinyint(1) DEFAULT '1' COMMENT '0为禁用，1为起启用',
  `temp_name` varchar(20) DEFAULT NULL COMMENT '模版标题',
  `temp_desc` varchar(100) DEFAULT NULL COMMENT '模版描述',
  `temp_title` varchar(200) NOT NULL COMMENT '发信标题',
  `temp_content` text NOT NULL COMMENT '发信内容',
  PRIMARY KEY (`temp_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='邮件模版表';

/*Data for the table `wm_system_email_temp` */

insert  into `wm_system_email_temp`(`temp_id`,`temp_status`,`temp_name`,`temp_desc`,`temp_title`,`temp_content`) values ('user_welcome',0,'欢迎邮件','注册账号成功时候发送的欢迎邮件','欢迎您加入{网站名}','{#lt}p{#gt}尊敬的{用户名}您好,欢迎您注册 {网站名}。{#lt}/p{#gt}'),('user_getpsw',1,'找回密码','找回密码时候发送的邮件','密码找回的邮件！','{#lt}p{#gt}尊敬的用户您好!{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}这封信是由 {#123}网站名{#125} 自动发送的。{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}您收到这封邮件，是由于在您 {#123}网站名{#125} 使用{#lt}span style={#34}color: rgb(255, 0, 0);{#34}{#gt}找回密码服务{#lt}/span{#gt}。如果您并没有访问过 {#123}网站名{#125}，或没有进行上述操作，请忽 略这封邮件。您不需要退订或进行其他进一步的操作。{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}验证码为：{#123}验证码{#125}{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}'),('user_veremail',1,'验证邮箱','验证用户的邮箱','用户名{用户名}，邮箱验证邮件！','{#lt}p{#gt}尊敬的{#123}用户名{#125}您好!{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}这封信是由 {#123}网站名{#125} 自动发送的。{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}您收到这封邮件，是由于在您 {#123}网站名{#125} 使用{#lt}span style={#34}color: rgb(255, 0, 0);{#34}{#gt}用户邮箱验证服务{#lt}/span{#gt}。如果您并没有访问过 {#123}网站名{#125}，或没有进行上述操作，请忽 略这封邮件。您不需要退订或进行其他进一步的操作。{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}验证码为：{#123}验证码{#125}{#lt}/p{#gt}'),('user_reg',1,'注册验证码','注册的时候获取邮件验证码','期待您的加入','{#lt}p{#gt}尊敬的用户您好!{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}这封信是由 {#123}网站名{#125} 自动发送的。{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}您收到这封邮件，是由于在您 {#123}网站名{#125} 使用{#lt}span style={#34}color: rgb(255, 0, 0);{#34}{#gt}获取注册验证码{#lt}/span{#gt}。如果您并没有访问过 {#123}网站名{#125}，或没有进行上述操作，请忽 略这封邮件。您不需要退订或进行其他进一步的操作。{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}验证码为：{#123}验证码{#125}{#lt}/p{#gt}{#lt}p{#gt}{#lt}br style={#34}white-space: normal;{#34}/{#gt}{#lt}/p{#gt}{#lt}p{#gt}{#lt}br style={#34}white-space: normal;{#34}/{#gt}{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}'),('user_login',1,'登录验证码','登录的时候获取邮件验证码','欢迎您登录{#123}网站名{#125}','{#lt}p{#gt}尊敬的{#123}用户名{#125}您好!{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}这封信是由 {#123}网站名{#125} 自动发送的。{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}您收到这封邮件，是由于在您 {#123}网站名{#125} 使用{#lt}span style={#34}color: rgb(255, 0, 0);{#34}{#gt}获取登录验证码{#lt}/span{#gt}。如果您并没有访问过 {#123}网站名{#125}，或没有进行上述操作，请忽 略这封邮件。您不需要退订或进行其他进一步的操作。{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}{#lt}p{#gt}验证码为：{#123}验证码{#125}{#lt}/p{#gt}{#lt}p{#gt}{#lt}br style={#34}white-space: normal;{#34}/{#gt}{#lt}/p{#gt}{#lt}p{#gt}{#lt}br style={#34}white-space: normal;{#34}/{#gt}{#lt}/p{#gt}{#lt}p{#gt}{#lt}br style={#34}white-space: normal;{#34}/{#gt}{#lt}/p{#gt}{#lt}p{#gt}{#lt}br/{#gt}{#lt}/p{#gt}'),('warning_code_eroor',1,'预警通知_代码报错','预警通知_代码报错','预警通知_代码报错','{#lt}p{#gt}尊敬的管理员：网站发生错误，{#123}代码报错详情{#125}！{#lt}/p{#gt}'),('warning_admin_login',1,'预警通知_后台登录','预警通知_后台登录','预警通知_后台登录','{#lt}p{#gt}尊敬的管理员：IP{#123}后台登录ip{#125}于{#123}后台登录时间{#125}登录账号{#123}后台登录账号{#125}，登录状态：{#123}后台登录状态{#125}！{#lt}/p{#gt}');

/*Table structure for table `wm_system_errlog` */

DROP TABLE IF EXISTS `wm_system_errlog`;

CREATE TABLE `wm_system_errlog` (
  `errlog_id` int(4) NOT NULL AUTO_INCREMENT,
  `errlog_status` tinyint(1) DEFAULT '0' COMMENT '是否上传，0为没有',
  `errlog_url` varchar(255) DEFAULT NULL COMMENT '错误的url',
  `errlog_state` varchar(20) DEFAULT NULL COMMENT 'SQLSTATE 错误码',
  `errlog_code` varchar(20) DEFAULT NULL COMMENT '驱动错误码',
  `errlog_msg` varchar(255) DEFAULT NULL COMMENT '驱动错误信息',
  `errlog_sql` varchar(2000) DEFAULT NULL COMMENT '错误的sql语句',
  `errlog_ip` varchar(15) DEFAULT NULL COMMENT '访问ip',
  `errlog_time` int(4) DEFAULT NULL COMMENT '错误的时间',
  PRIMARY KEY (`errlog_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统sql错误记录表';

/*Data for the table `wm_system_errlog` */

/*Table structure for table `wm_system_menu` */

DROP TABLE IF EXISTS `wm_system_menu`;

CREATE TABLE `wm_system_menu` (
  `menu_id` int(4) unsigned NOT NULL AUTO_INCREMENT,
  `menu_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '菜单的显示状态，0为隐藏，1为显示',
  `menu_title` varchar(20) NOT NULL COMMENT '目录标题',
  `menu_name` varchar(50) NOT NULL COMMENT '目录名字，如果为权限菜单的时候，此值为t的类型',
  `menu_pid` int(4) NOT NULL DEFAULT '0' COMMENT '父级id',
  `menu_group` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为普通目录，1为组目录,2为权限菜单',
  `menu_order` int(1) DEFAULT '10' COMMENT '显示顺序',
  `menu_file` varchar(40) DEFAULT NULL COMMENT '目录文件名字',
  `menu_url` tinyint(1) DEFAULT '0' COMMENT '是否加上name为type值',
  `menu_ico` varchar(50) DEFAULT NULL COMMENT '菜单的图标名',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM AUTO_INCREMENT=921 DEFAULT CHARSET=utf8 COMMENT='系统目录菜单表';

/*Data for the table `wm_system_menu` */

insert  into `wm_system_menu`(`menu_id`,`menu_status`,`menu_title`,`menu_name`,`menu_pid`,`menu_group`,`menu_order`,`menu_file`,`menu_url`,`menu_ico`) values (1,1,'系统管理','system',0,0,1,NULL,0,'fa-cog'),(2,1,'网站设置','set',1,0,1,NULL,0,'fa-cogs'),(3,1,'基本设置','config',2,0,1,'system.set.config',0,'fa-wrench'),(4,1,'API管理','api',245,0,1,'system.set.api',0,'fa-random'),(5,1,'水印上传','water',2,0,3,'system.set.water',0,'fa-cloud-upload'),(6,1,'域名设置','domain',2,0,4,'system.set.domain',0,'fa-bars'),(7,1,'模版管理','templates',2,0,5,'system.set.templates',0,'fa-clone'),(8,1,'去除BOM','delbom',2,0,8,'system.set.delbom',0,'fa-ban'),(9,1,'菜单管理','menu',1,0,2,NULL,0,'fa-align-justify'),(10,0,'新增菜单','add',9,0,1,'system.menu.edit',0,NULL),(11,1,'菜单列表','menu',9,0,2,'system.menu.menu',0,'fa-list-ul'),(12,1,'网站SEO','seo',1,0,4,NULL,0,'fa-recycle'),(13,1,'SEO设置','config',12,0,1,'system.seo.keys',0,'fa-refresh'),(14,1,'伪静态','rewrite',12,0,2,'system.seo.rewrite',0,'fa-square'),(15,1,'静态生成','html',12,0,3,'system.seo.html',0,'fa-sticky-note'),(16,1,'权限管理','competence',1,0,7,'',0,'fa-arrows-alt'),(17,1,'新增权限','edit',16,0,1,'system.competence.edit',0,'fa-plus-square'),(18,1,'权限列表','list',16,0,2,'system.competence.list',0,'fa-list-ul'),(19,1,'后台账号','manager',1,0,8,NULL,0,'fa-user-secret'),(20,1,'新增管理','edit',19,0,1,'system.manager.edit',0,'fa-user-plus'),(22,1,'安全管理','safe',1,0,9,'',0,'fa-tasks'),(21,1,'管理列表','manager',19,0,2,'system.manager.list',0,'fa-list-ul'),(23,1,'登录记录','log',22,0,5,'system.safe.log',0,'fa-location-arrow'),(24,1,'修改密码','uppsw',22,0,20,'system.safe.account.uppsw',0,'fa-unlock-alt'),(25,1,'模块管理','module',0,0,2,'',0,'fa-th-large'),(26,1,'文章模块','article',25,0,1,NULL,0,'fa-file-text'),(27,1,'分类管理','type',26,1,1,NULL,0,NULL),(28,1,'新增分类','add',27,0,1,'article.type.edit',0,NULL),(29,1,'分类列表','list',27,0,2,'article.type.list',0,NULL),(30,1,'文章管理','article',26,1,2,'',0,NULL),(31,1,'添加文章','add',30,0,1,'article.article.edit',0,NULL),(32,1,'文章列表','list',30,0,2,'article.article.list',0,NULL),(33,1,'模块设置','set',26,0,3,NULL,0,NULL),(34,1,'作者来源','author',33,0,1,'article.author.list',0,NULL),(35,1,'模块配置','config',33,0,2,'article.set.config',0,NULL),(36,1,'关于模块','about',25,0,2,'',0,'fa-info-circle'),(37,1,'关于分类','type',36,1,1,'',0,NULL),(38,1,'添加分类','add',37,0,1,'about.type.edit',0,NULL),(39,1,'分类列表','list',37,0,2,'about.type.list',0,NULL),(40,1,'关于信息','about',36,1,2,'',0,NULL),(41,1,'添加信息','add',40,0,1,'about.about.edit',0,NULL),(42,1,'信息列表','list',40,0,2,'about.about.list',0,NULL),(43,1,'图集模块','picture',25,0,3,'',0,'fa-picture-o'),(44,1,'分类管理','type',43,1,1,'',0,NULL),(45,1,'添加分类','add',44,0,1,'picture.type.edit',0,NULL),(46,1,'分类列表','list',44,0,2,'picture.type.list',0,NULL),(47,1,'图集管理','picture',43,1,2,'',0,NULL),(48,1,'添加图集','add',47,0,1,'picture.picture.edit',0,NULL),(49,1,'图集列表','list',47,0,2,'picture.picture.list',0,NULL),(50,1,'模块设置','set',43,1,3,'',0,NULL),(51,1,'模块配置','config',50,0,1,'picture.set.config',0,NULL),(52,1,'论坛模块','bbs',25,0,4,'',0,'fa-comments'),(53,1,'版块管理','type',52,1,1,'',0,NULL),(54,1,'添加版块','add',53,0,1,'bbs.type.edit',0,NULL),(55,1,'版块列表','type',53,0,2,'bbs.type.list',0,NULL),(56,1,'主题管理','bbs',52,1,3,'',0,NULL),(57,1,'主题列表','bbs',56,0,1,'bbs.bbs.list',0,NULL),(58,1,'回帖列表','replay',56,0,2,'bbs.replay.list',0,NULL),(59,1,'模块设置','set',52,1,4,'',0,NULL),(60,1,'模块配置','config',59,0,1,'bbs.set.config',0,NULL),(61,1,'应用模块','app',25,0,5,'',0,'fa-android'),(62,1,'分类管理','type',61,1,1,'',0,NULL),(63,1,'添加分类','add',62,0,1,'app.type.edit',0,NULL),(64,1,'分类列表','list',62,0,2,'app.type.list',0,NULL),(65,1,'应用管理','app',61,1,2,'',0,NULL),(66,1,'添加应用','add',65,0,1,'app.app.edit',0,NULL),(67,1,'应用列表','list',65,0,2,'app.app.list',0,NULL),(68,1,'资料属性','attr',61,1,3,'',0,NULL),(69,1,'资料预设','abs',68,0,1,'app.attr.abs',0,NULL),(70,1,'厂商新增','add',68,0,2,'app.firms.edit',0,NULL),(71,1,'厂商列表','list',68,0,3,'app.firms.list',0,NULL),(72,1,'模块设置','set',61,1,4,'',0,NULL),(73,1,'模块配置','config',72,0,1,'app.set.config',0,NULL),(74,1,'小说模块','novel',25,0,0,'',0,'fa-book'),(75,1,'分类管理','type',74,1,1,'',0,NULL),(76,1,'添加分类','add',75,0,1,'novel.type.edit',0,NULL),(77,1,'分类列表','list',75,0,2,'novel.type.list',0,NULL),(78,1,'小说管理','novel',74,1,2,'',0,NULL),(79,1,'添加小说','add',78,0,1,'novel.novel.edit',0,NULL),(80,1,'小说列表','list',78,0,2,'novel.novel.list',0,NULL),(81,1,'分卷管理','volume',74,1,3,'',0,NULL),(82,1,'添加分卷','add',81,0,1,'novel.volume.edit',0,NULL),(83,1,'分卷列表','list',81,0,2,'novel.volume.list',0,NULL),(84,1,'章节管理','chapter',74,1,4,'',0,NULL),(85,1,'添加章节','add',84,0,1,'novel.chapter.edit',0,NULL),(86,1,'章节列表','list',84,0,2,'novel.chapter.list',0,NULL),(87,1,'模块设置','set',74,1,6,'',0,NULL),(88,1,'推荐管理','rec',78,0,3,'novel.novel.rec',0,NULL),(91,1,'小说配置','config',87,0,4,'novel.set.config',0,NULL),(92,1,'原创模块','author',25,0,7,'',0,'fa-users'),(93,1,'作家管理','author',92,1,1,'',0,NULL),(94,1,'添加作家','add',93,0,1,'author.author.edit',0,NULL),(95,1,'作家列表','list',93,0,2,'author.author.list',0,NULL),(96,1,'原创小说','novel',92,1,2,'',0,NULL),(97,1,'原创列表','list',96,0,2,'author.novel.novel.list',0,NULL),(98,1,'待审章节','status',96,0,3,'author.novel.chapter.list',0,NULL),(99,1,'财务管理','finance',107,1,5,'',0,'fa-jpy'),(100,1,'提现申请','cash',99,0,1,'finance.order.cash',0,'fa-money'),(101,1,'资金记录','log',99,0,2,'finance.finance.list',0,'fa-vine'),(102,1,'充值记录','charge',99,0,3,'finance.order.charge',0,' fa-buysellads'),(103,1,'模块设置','set',92,1,9,'',0,NULL),(104,1,'作家写作等级','level',103,0,1,'author.level.author',0,NULL),(105,1,'作品签约等级','sign',103,0,2,'author.level.sign',0,NULL),(106,1,'模块配置','config',103,0,5,'author.set.config',0,NULL),(107,1,'用户模块','user',0,0,3,'',0,'fa-user'),(108,1,'用户管理','user',107,1,1,'',0,'fa-user'),(109,1,'添加用户','add',108,0,1,'user.user.edit',0,'fa-user-plus'),(110,1,'用户列表','list',108,0,2,'user.user.list',0,'fa-users'),(111,1,'预设管理','preset',107,1,2,'',0,'fa-tachometer'),(112,1,'预设头像','head',111,0,1,'user.preset.head',0,'fa-smile-o'),(113,1,'等级配置','lv',111,0,2,'user.preset.lv',0,'fa-plane'),(114,1,'互动管理','interactive',107,1,3,'',0,'fa-commenting'),(115,1,'发送内信','send',114,0,2,'user.msg.send',0,'fa-paper-plane'),(116,1,'内信列表','list',114,0,3,'user.msg.list',0,'fa-envelope'),(117,1,'模块设置','set',107,1,4,'',0,'fa-th-large'),(118,1,'用户配置','config',117,0,1,'user.set.config',0,'fa-user-secret'),(119,1,'友链模块','link',25,0,8,'',0,'fa-link'),(120,1,'分类管理','type',119,1,1,'',0,NULL),(121,1,'添加分类','add',120,0,1,'link.type.edit',0,NULL),(122,1,'分类列表','list',120,0,2,'link.type.list',0,NULL),(123,1,'友链管理','link',119,0,2,'',0,NULL),(124,1,'添加友链','add',123,0,1,'link.link.edit',0,NULL),(125,1,'友链列表','list',123,0,2,'link.link.list',0,NULL),(126,1,'模块设置','set',119,1,3,'',0,NULL),(127,1,'欠量站点','owed',126,0,1,'link.owed.list',1,NULL),(128,1,'点击记录','click',126,0,2,'link.click.list',0,NULL),(129,1,'模块配置','config',126,0,3,'link.set.config',0,NULL),(130,1,'运营中心','operations',0,0,4,'',0,'fa-arrows'),(131,1,'评论管理','replay',130,1,1,'',0,'fa-comment'),(132,1,'今日评论','today',131,0,1,'operate.replay.list',1,'fa-square'),(133,1,'热门评论','hot',131,0,2,'operate.replay.list',1,'fa-th-large'),(134,1,'所有评论','all',131,0,3,'operate.replay.list',1,'fa-th'),(135,1,'评论配置','config',131,0,4,'operate.replay.config',0,'fa-wrench'),(136,1,'搜索管理','search',130,1,2,'',0,'fa-search'),(137,1,'全部搜索','all',136,0,1,'operate.search.list',1,'fa-search-plus'),(138,1,'标题搜索','name',136,0,2,'operate.search.list',1,'fa-search-minus'),(139,1,'作者搜索','author',136,0,3,'operate.search.list',1,'fa-search-minus'),(140,1,'标签搜索','tag',136,0,4,'operate.search.list',1,'fa-search-minus'),(141,1,'幻灯片管理','falsh',130,1,3,'',0,'fa-file-video-o'),(142,1,'添加幻灯片','add',141,0,3,'operate.flash.edit',0,'fa-plus-circle'),(143,1,'幻灯片列表','flash',141,0,4,'operate.flash.list',0,'fa-file-text-o'),(144,1,'留言管理','message',130,1,4,'',0,'fa-commenting'),(145,1,'留言列表','message',144,0,1,'operate.message.list',0,'fa-list'),(146,1,'预设模版','templates',130,1,5,'',0,'fa-align-justify'),(147,1,'上传模版','add',146,0,1,'system.templates.edit',0,'fa-cloud-upload'),(148,1,'模版列表','list',146,0,2,'system.templates.list',0,'fa-list'),(149,1,'专题管理','zt',130,1,6,'',0,'fa-newspaper-o'),(150,1,'新增专题','add',149,0,3,'operate.zt.edit',0,'fa-plus-circle'),(151,1,'专题列表','list',149,0,4,'operate.zt.list',0,'fa-indent'),(152,1,'单页管理','diy',130,1,7,'',0,'fa-cubes'),(153,1,'新增单页','add',152,0,1,'operate.diy.edit',0,'fa-plus-circle'),(154,1,'单页列表','list',152,0,2,'operate.diy.list',0,'fa-list-alt'),(155,1,'统计管理','tongji',130,1,8,'',0,'fa-bar-chart'),(156,1,'统计代码','code',155,0,1,'operate.tongji.code',0,'fa-file-code-o'),(157,1,'广告管理','ad',130,1,9,'',0,'fa-dollar'),(158,1,'添加广告','add',157,0,3,'operate.ad.edit',0,'fa-plus-circle'),(159,1,'广告列表','list',157,0,4,'operate.ad.list',0,'fa-list-ul'),(160,1,'数据中心','data',0,0,5,'',0,'fa-bar-chart'),(161,1,'数据统计','chart',160,0,3,'',0,'fa-area-chart'),(162,1,'图形数据','graph',161,1,1,'',0,NULL),(163,1,'数据统计图','tongji',162,0,1,'data.chart.tongji',0,NULL),(164,1,'数据增长图','week',162,0,2,'data.chart.add',1,NULL),(165,0,'数据采集','collection',160,0,4,'',0,'fa-list-ul'),(166,1,'规则管理','rules',165,1,1,'',0,NULL),(167,1,'添加规则','edit',166,0,1,'data.collection.rules',0,NULL),(168,1,'规则列表','rules',166,0,2,'data.collection.rules',0,NULL),(169,1,'任务管理','task',165,1,2,'',0,NULL),(170,1,'添加任务','edit',169,0,1,'data.collection.rules',0,NULL),(171,1,'任务列表','task',169,0,2,'data.collection.rules',0,NULL),(172,1,'执行任务','start',169,0,3,'data.collection.rules',0,NULL),(173,1,'邮件设置','email',245,0,6,'system.set.email',0,'fa-envelope'),(177,1,'修改基本设置','system',3,2,1,'system.set.config',0,NULL),(178,1,'修改api设置','api',4,2,1,'system.set.api',0,NULL),(179,1,'修改上传设置','config',5,2,1,'system.set.water',0,NULL),(180,1,'修改域名设置','domain',6,2,1,'system.set.domain',0,NULL),(181,1,'修改模版','update',7,2,1,'system.set.templates',0,NULL),(182,1,'卸载模版','uninstall',7,2,2,'system.set.templates',0,NULL),(183,1,'安装模版','install',7,2,3,'system.set.templates',0,NULL),(184,1,'修改邮件配置','update',173,2,5,'system.set.email',0,NULL),(185,0,'发送测试邮件','test',173,2,6,'system.email.email',0,NULL),(186,1,'删除BOM','delbom',8,2,1,'system.set.delbom',0,NULL),(187,1,'修改菜单顺序','order',11,2,1,'system.menu.menu',0,NULL),(188,1,'修改菜单','edit',11,2,3,'system.menu.menu',0,NULL),(189,1,'删除菜单','del',11,2,4,'system.menu.menu',0,NULL),(190,1,'新增菜单','add',11,2,0,'system.menu.menu',0,NULL),(191,1,'新增SEO页面','add',13,0,1,'system.seo.keys.edit',0,NULL),(192,1,'修改SEO页面','edit',13,0,2,'system.seo.keys.edit',0,NULL),(193,1,'新增SEO操作','add',191,2,10,'system.seo.keys',0,NULL),(194,1,'修改SEO操作','edit',192,2,10,'system.seo.keys',0,NULL),(195,1,'新增URL链接','add',14,0,1,'system.seo.rewrite.edit',0,NULL),(196,1,'修改URL链接','edit',14,0,1,'system.seo.rewrite.edit',0,NULL),(197,1,'新增URL操作','add',195,2,1,'system.seo.rewrite',0,NULL),(198,1,'修改URL操作','edit',196,2,1,'system.seo.rewrite',0,NULL),(199,1,'新增权限操作','add',17,2,1,'system.competence.competence',0,NULL),(200,1,'修改权限操作','edit',17,2,2,'system.competence.competence',0,NULL),(201,1,'新增管理员操作','add',20,2,1,'system.manager.manager',0,NULL),(202,1,'修改管理员操作','edit',20,2,2,'system.manager.manager',0,NULL),(203,1,'禁用恢复管理员','status',20,2,3,'system.manager.manager',0,NULL),(204,1,'删除管理员账号','del',20,2,4,'system.manager.manager',0,NULL),(205,1,'删除登录记录','del',23,2,1,'system.safe.log',0,NULL),(206,1,'登录记录详情','detail',23,0,2,'system.safe.log.detail',0,NULL),(207,1,'修改密码操作','uppsw',24,2,1,'system.safe.account',0,NULL),(208,1,'生成关键词缓存','config',13,2,3,'system.seo.keys',0,NULL),(209,1,'生成URL缓存','config',14,2,4,'system.seo.rewrite',0,NULL),(210,1,'增加文章分类操作','add',28,2,1,'article.type',0,NULL),(211,1,'修改文章分类操作','edit',28,2,1,'article.type',0,NULL),(212,1,'编辑模版','edit',147,0,2,'system.templates.edit',0,NULL),(213,1,'新增模版操作','add',147,2,1,'system.templates.templates',0,NULL),(214,1,'模版查找带回','lookup',148,0,1,'system.templates.lookup',0,NULL),(215,1,'请求日志','requset',22,0,7,'system.safe.request',0,'fa-random'),(216,1,'请求详情','detail',215,0,1,'system.safe.request.detail',0,NULL),(217,0,'删除请求日志','del',215,2,2,'system.safe.request',0,NULL),(218,0,'清空请求日志','clear',215,2,3,'system.safe.request',0,NULL),(219,0,'清空登录记录','clear',23,2,4,'system.safe.log',0,NULL),(220,1,'操作记录','operation',22,0,9,'system.safe.operation',0,'fa-list'),(221,0,'删除操作记录','del',220,2,1,'system.safe.operation',0,NULL),(222,0,'清空操作记录','clear',220,2,2,'system.safe.operation',0,NULL),(223,0,'操作记录详情','detail',220,0,1,'system.safe.operation.detail',0,NULL),(224,0,'删除权限操作','del',18,2,2,'system.competence.competence',0,NULL),(225,0,'修改文章操作','edit',31,2,1,'article.article',0,NULL),(226,0,'添加文章操作','add',31,2,2,'article.article',0,NULL),(227,0,'编辑文章分类','edit',27,0,10,'article.type.edit',0,NULL),(228,1,'删除文章分类操作','del',28,2,2,'article.type',0,NULL),(229,1,'删除文章操作','del',31,2,3,'article.article',0,NULL),(230,1,'审核文章操作','status',31,2,4,'article.article',0,NULL),(231,1,'文章移动操作','move',31,2,5,'article.article',0,NULL),(232,1,'设置属性操作','attr',31,2,6,'article.article',0,NULL),(233,1,'编辑文章内容','edit',31,0,2,'article.article.edit',0,NULL),(234,1,'编辑作者操作','edit',31,2,7,'article.author',0,NULL),(235,1,'删除作者操作','del',31,2,8,'article.author',0,NULL),(236,1,'添加作者操作','add',31,2,9,'article.author',0,NULL),(237,1,'编辑作者来源','edit',34,0,1,'article.author.edit',0,NULL),(238,1,'新增作者来源','add',34,0,2,'article.author.edit',0,NULL),(239,1,'配置列表','list',245,0,1,'system.config.list',0,'fa-list-ul'),(240,1,'编辑配置','edit',239,0,1,'system.config.edit',0,NULL),(241,1,'添加配置','add',239,0,2,'system.config.edit',0,NULL),(242,1,'添加配置操作','add',241,2,1,'system.config.config',0,NULL),(243,1,'编辑配置操作','edit',241,2,2,'system.config.config',0,NULL),(244,1,'删除配置操作','del',241,2,3,'system.config.config',0,NULL),(245,1,'配置管理','option',1,0,2,'',0,'fa-server'),(246,1,'选项管理','optionlist',245,0,2,'system.config.option.list',0,'fa-file-text-o'),(247,1,'添加选项','add',246,0,3,'system.config.option.edit',0,NULL),(248,1,'编辑选项','edit',246,0,4,'system.config.option.edit',0,NULL),(249,1,'编辑选项操作','edit',246,2,1,'system.config.option',0,NULL),(250,1,'添加选项操作','add',246,2,2,'system.config.option',0,NULL),(251,1,'获取分组配置','getconfig',246,2,3,'system.config.config',0,NULL),(252,1,'删除选项操作','del',246,2,4,'system.config.option',0,NULL),(253,1,'修改配置操作','edit',35,2,1,'article.config',0,NULL),(254,1,'错误日志','errlog',22,0,11,'system.safe.errlog',0,'fa-bug'),(255,1,'错误日志详情','detail',254,0,1,'system.safe.errlog.detail',0,NULL),(256,1,'删除日志操作','del',254,2,1,'system.safe.errlog',0,NULL),(257,1,'清空日志操作','clear',254,2,2,'system.safe.errlog',0,NULL),(258,1,'作者来源选择','lookup',31,0,10,'article.author.lookup',0,NULL),(259,1,'回收站','recycle',30,0,3,'article.article.recycle',0,NULL),(260,1,'还原操作','reduction',259,2,1,'article.article',0,NULL),(261,1,'彻底删除操作','realdel',259,2,2,'article.article',0,NULL),(262,0,'增加小说分类操作','add',76,2,1,'novel.type',0,NULL),(263,0,'修改小说分类操作','edit',76,2,2,'novel.type',0,NULL),(264,0,'删除小说分类操作','del',77,2,1,'novel.type',0,NULL),(265,1,'添加小说操作','add',79,2,1,'novel.novel',0,NULL),(266,1,'删除小说操作','del',80,2,1,'novel.novel',0,NULL),(267,1,'审核小说操作','status',80,2,2,'novel.novel',0,NULL),(268,1,'移动小说操作','move',80,2,3,'novel.novel',0,NULL),(269,0,'批量推荐操作','rec',80,2,4,'novel.rec',0,NULL),(270,1,'修改小说操作','edit',79,2,2,'novel.novel',0,NULL),(271,1,'新增分卷操作','add',82,2,1,'novel.volume',0,NULL),(272,1,'小说搜索操作','search',80,2,5,'novel.novel',0,NULL),(273,1,'删除分卷操作','del',83,2,1,'novel.volume',0,NULL),(274,0,'修改小说分类','edit',75,0,2,'novel.type.edit',0,NULL),(275,0,'修改小说','edit',78,0,10,'novel.novel.edit',0,NULL),(276,0,'修改小说分卷','edit',81,0,10,'novel.volume.edit',0,NULL),(277,0,'获得分卷操作','getvolume',81,2,3,'novel.volume',0,NULL),(278,1,'添加章节操作','add',85,2,1,'novel.chapter',0,NULL),(279,0,'编辑章节','edit',85,0,2,'novel.chapter.edit',0,NULL),(280,1,'编辑章节内容','edit',279,2,1,'novel.chapter',0,NULL),(281,1,'删除章节操作','del',86,2,1,'novel.chapter',0,NULL),(282,1,'清空章节操作','clear',86,2,2,'novel.chapter',0,NULL),(283,0,'删除推荐操作','del',88,2,2,'novel.rec',0,NULL),(284,0,'修改推荐','edit',88,0,1,'novel.novel.rec.edit',0,NULL),(285,0,'修改推荐操作','edit',88,2,10,'novel.rec',0,NULL),(286,1,'修改配置操作','edit',91,2,10,'novel.config',0,NULL),(287,1,'修改分卷操作','edit',82,2,2,'novel.volume',0,NULL),(288,1,'新增用户操作','add',109,2,1,'user.user',0,NULL),(290,1,'删除用户操作','del',110,2,1,'user.user',0,NULL),(289,1,'修改用户操作','edit',292,2,2,'user.user',0,NULL),(291,1,'审核用户操作','status',110,2,2,'user.user',0,NULL),(292,0,'编辑用户','edit',110,0,1,'user.user.edit',0,NULL),(293,0,'删除头像操作','del',112,2,1,'user.head',0,NULL),(294,0,'添加头像操作','add',112,2,2,'user.head',0,NULL),(295,0,'添加等级操作','add',113,2,1,'user.lv',0,NULL),(296,1,'修改等级操作','edit',113,2,2,'user.lv',0,NULL),(297,0,'删除等级操作','del',113,2,3,'user.lv',0,NULL),(298,0,'发送消息操作','send',115,2,1,'user.msg',0,NULL),(299,0,'删除消息操作','del',116,2,1,'user.msg',0,NULL),(300,0,'清空消息操作','clear',116,2,1,'user.msg',0,NULL),(475,0,'移动章节顺序','order',84,0,3,'novel.chapter.order',0,NULL),(302,0,'修改配置操作','edit',118,2,1,'user.config',0,NULL),(303,0,'删除评论操作','del',131,2,1,'operate.replay',0,NULL),(304,0,'清空评论操作','clear',131,2,2,'operate.replay',0,NULL),(305,0,'评论审核操作','status',131,2,3,'operate.replay',0,NULL),(306,0,'修改配置操作','edit',135,2,1,'operate.replay.config',0,NULL),(307,0,'删除搜索操作','del',137,2,1,'operate.search',0,NULL),(308,0,'清空搜索操作','clear',137,2,2,'operate.search',0,NULL),(309,0,'编辑幻灯片','edit',143,0,1,'operate.flash.edit',0,NULL),(310,0,'添加幻灯片操作','add',142,2,1,'operate.flash',0,NULL),(311,0,'修改幻灯片操作','edit',309,2,2,'operate.flash',0,NULL),(312,0,'幻灯片显示操作','status',143,2,1,'operate.flash',0,NULL),(313,0,'删除幻灯片操作','del',143,2,2,'operate.flash',0,NULL),(314,0,'清空幻灯片操作','clear',143,2,3,'operate.flash',0,NULL),(315,0,'留言审核操作','status',145,2,1,'operate.message',0,NULL),(316,0,'删除留言操作','del',145,2,2,'operate.message',0,NULL),(317,0,'清空留言操作','clear',145,2,3,'operate.message',0,NULL),(318,0,'获取模块分类','gettype',147,2,1,'system.templates.templates',0,NULL),(319,0,'删除预设模版操作','del',147,2,1,'system.templates.templates',0,NULL),(320,0,'清空预设模版操作','clear',147,2,2,'system.templates.templates',0,NULL),(321,0,'修改预设模版操作','edit',147,2,2,'system.templates.templates',0,NULL),(322,0,'添加单页操作','add',153,2,1,'operate.diy',0,NULL),(323,0,'删除单页操作','del',154,2,1,'operate.diy',0,NULL),(324,0,'清空单页操作','clear',154,2,2,'operate.diy',0,NULL),(325,0,'单页审核操作','status',154,2,3,'operate.diy',0,NULL),(326,0,'修改单页操作','edit',327,2,2,'operate.diy',0,NULL),(327,0,'修改单页','edit',154,0,1,'operate.diy.edit',0,NULL),(328,0,'修改统计操作','edit',156,2,1,'operate.tongji',0,NULL),(329,0,'添加广告操作','add',158,2,1,'operate.ad',0,NULL),(330,0,'修改广告操作','edit',334,2,1,'operate.ad',0,NULL),(331,1,'广告审核操作','status',159,2,1,'operate.ad',0,NULL),(332,1,'删除广告操作','del',159,2,2,'operate.ad',0,NULL),(333,1,'清空广告操作','clear',159,2,3,'operate.ad',0,NULL),(334,0,'修改广告','edit',159,0,1,'operate.ad.edit',0,NULL),(335,1,'添加分类','add',157,0,1,'operate.ad.type.edit',0,'fa-plus-circle'),(336,1,'分类列表','list',157,0,2,'operate.ad.type.list',0,'fa-list'),(337,0,'新增分类操作','add',335,2,1,'operate.ad.type',0,NULL),(338,0,'修改分类操作','edit',341,2,2,'operate.ad.type',0,NULL),(339,0,'删除分类操作','del',336,2,1,'operate.ad.type',0,NULL),(340,0,'清空分类操作','clear',336,2,2,'operate.ad.type',0,NULL),(341,0,'编辑分类','edit',336,0,2,'operate.ad.type.edit',0,NULL),(342,0,'本月增长统计','month',162,0,3,'data.chart.add',0,NULL),(343,0,'本年增长统计','year',162,0,4,'data.chart.add',0,NULL),(344,0,'添加专题操作','add',150,2,1,'operate.zt',0,NULL),(345,0,'删除专题操作','del',151,2,1,'operate.zt',0,NULL),(346,0,'清空专题操作','clear',151,2,2,'operate.zt',0,NULL),(347,0,'专题审核操作','status',151,2,3,'operate.zt',0,NULL),(348,0,'修改专题操作','edit',349,2,4,'operate.zt',0,NULL),(349,0,'修改专题','edit',151,0,1,'operate.zt.edit',0,NULL),(350,0,'专题节点列表','node',151,0,1,'operate.zt.node.list',0,NULL),(351,0,'增加专题节点','add',350,0,2,'operate.zt.node.edit',0,NULL),(352,0,'修改专题节点','edit',350,0,3,'operate.zt.node.edit',0,NULL),(353,1,'增加节点操作','add',351,2,1,'operate.zt.node',0,NULL),(354,1,'修改节点操作','edit',352,2,1,'operate.zt.node',0,NULL),(355,1,'删除节点操作','del',350,2,1,'operate.zt.node',0,NULL),(356,1,'清空节点操作','clear',350,2,2,'operate.zt.node',0,NULL),(357,1,'顶踩记录','dingcai',359,0,1,'operate.operate.list',1,'fa-list-ul'),(358,1,'评分记录','score',359,0,2,'operate.operate.list',1,'fa-list-ul'),(359,1,'互动管理','interaction',130,0,2,'',0,'fa-thumbs-up'),(360,1,'删除互动操作','del',359,2,1,'operate.operate',0,NULL),(361,1,'清空互动操作','clear',359,2,1,'operate.operate',0,NULL),(362,1,'签到记录','sign',114,0,1,'user.sign.list',0,'fa-list-ul'),(363,1,'删除签到记录','del',362,2,10,'user.sign',0,NULL),(364,1,'清空签到记录','clear',362,2,10,'user.sign',0,NULL),(365,1,'文件管理','file',160,0,2,'',0,'fa-file'),(366,1,'文件列表','list',365,0,2,'data.file.list',0,'fa-list-ul'),(367,0,'文件重命名','rename',366,0,1,'data.file.rename',0,NULL),(368,0,'重命名操作','rename',367,2,1,'data.file',0,NULL),(370,0,'创建文件夹','createfolder',367,0,3,'data.file.createfolder',0,NULL),(369,0,'删除操作','del',367,2,2,'data.file',0,NULL),(371,0,'创建文件夹操作','createfolder',370,2,1,'data.file',0,NULL),(373,1,'移动文件操作','movefile',372,2,1,'data.file',0,NULL),(372,0,'移动文件','movefile',365,0,1,'data.file.movefile',0,NULL),(374,0,'创建文件','create',366,0,1,'data.file.file',0,NULL),(375,0,'编辑文件','edit',366,0,1,'data.file.file',0,NULL),(376,1,'创建文件操作','create',374,2,1,'data.file',0,NULL),(377,1,'编辑文件操作','edit',375,2,1,'data.file',0,NULL),(378,1,'数据库管理','mysql',160,0,1,'data.mysql',0,'fa-cubes'),(379,1,'执行SQL','runsql',378,0,2,'data.mysql.runsql',0,'fa-list-ul'),(380,1,'执行SQL操作','runsql',379,2,1,'data.mysql',0,NULL),(381,1,'数据库库列表','table',378,0,1,'data.mysql.table',0,'fa-list-ul'),(382,1,'优化表操作','optimize',381,2,1,'data.mysql',0,NULL),(383,1,'修复表操作','repair',381,2,2,'data.mysql',0,NULL),(384,1,'增加分类操作','add',121,2,1,'link.type',0,NULL),(385,1,'修改分类操作','edit',386,2,1,'link.type',0,NULL),(386,0,'修改友链分类','edit',122,0,2,'link.type.edit',0,NULL),(387,0,'删除分类操作','del',122,2,2,'link.type',0,NULL),(388,0,'添加友链操作','add',124,2,1,'link.link',0,NULL),(389,0,'修改友链操作','edit',395,2,2,'link.link',0,NULL),(390,0,'删除友链操作','del',125,2,3,'link.link',0,NULL),(391,0,'清空友链操作','clear',125,2,4,'link.link',0,NULL),(392,0,'审核友链操作','status',125,2,5,'link.link',0,NULL),(393,0,'推荐友链操作','attr',125,2,6,'link.link',0,NULL),(394,0,'移动友链操作','move',125,2,7,'link.link',0,NULL),(395,0,'修改友链','edit',125,0,1,'link.link.edit',0,NULL),(396,0,'删除点击记录','del',128,2,1,'link.click',0,NULL),(397,0,'清空点击记录','clear',128,2,2,'link.click',0,NULL),(398,0,'修改友链配置','edit',129,2,1,'link.config',0,NULL),(399,0,'添加分类操作','add',38,2,1,'about.type',0,NULL),(400,0,'修改分类操作','edit',39,2,1,'about.type',0,NULL),(401,0,'修改信息分类','edit',39,0,1,'about.type.edit',0,NULL),(402,1,'删除分类操作','del',39,2,2,'about.type',0,NULL),(403,0,'修改信息','edit',42,0,1,'about.about.edit',0,NULL),(404,0,'删除信息操作','del',42,2,3,'about.about',0,NULL),(405,0,'清空信息操作','clear',42,2,4,'about.about',0,NULL),(406,0,'添加信息操作','add',42,2,5,'about.about',0,NULL),(407,0,'修改信息操作','edit',42,2,6,'about.about',0,NULL),(408,0,'移动信息操作','move',42,2,7,'about.about',0,NULL),(409,0,'检查友链操作','checkseo',125,2,8,'link.link',0,NULL),(410,0,'检查回链操作','checkback',125,2,9,'link.link',0,NULL),(411,1,'用户金币奖惩','reward',110,0,3,'user.user.reward',0,NULL),(412,0,'金币奖惩操作','reward',411,2,1,'user.user',0,NULL),(413,0,'修改图集分类','edit',44,0,2,'picture.type.edit',0,NULL),(414,0,'增加分类操作','add',45,2,1,'picture.type',0,NULL),(415,0,'修改分类操作','edit',46,2,1,'picture.type',0,NULL),(416,0,'删除分类操作','del',46,2,2,'picture.type',0,NULL),(417,0,'添加图集操作','add',48,2,1,'picture.picture',0,NULL),(418,0,'修改图集操作','edit',49,2,2,'picture.picture',0,NULL),(419,0,'删除图集操作','del',49,2,3,'picture.picture',0,NULL),(420,0,'审核图集操作','status',49,2,4,'picture.picture',0,NULL),(421,0,'移动图集操作','move',49,2,5,'picture.picture',0,NULL),(422,0,'图集属性操作','attr',49,2,6,'picture.picture',0,NULL),(423,0,'修改图集','edit',49,0,7,'picture.picture.edit',0,NULL),(424,0,'修改设置操作','edit',51,2,1,'picture.config',0,NULL),(425,1,'快捷菜单','quick',9,0,3,'system.menu.quick',0,'fa-list-ul'),(426,0,'静态资源管理','static',147,0,3,'system.templates.static',0,NULL),(427,1,'删除资源操作','delstatic',426,2,1,'system.templates.templates',0,NULL),(428,1,'删除分类操作','del',64,2,1,'app.type',0,NULL),(429,1,'新增分类操作','add',63,2,2,'app.type',0,NULL),(430,1,'修改分类操作','edit',64,2,3,'app.type',0,NULL),(431,0,'修改应用分类','edit',64,0,2,'app.type.edit',0,NULL),(432,0,'修改应用','edit',67,0,1,'app.app.edit',0,NULL),(433,0,'删除应用操作','del',67,2,1,'app.app',0,NULL),(434,0,'增加应用操作','add',67,2,2,'app.app',0,NULL),(435,0,'修改应用操作','edit',432,2,3,'app.app',0,NULL),(436,0,'审核应用操作','status',67,2,4,'app.app',0,NULL),(437,0,'移动应用操作','move',67,2,5,'app.app',0,NULL),(438,0,'应用属性操作','attr',67,2,6,'app.app',0,NULL),(439,0,'添加资料','add',69,0,1,'app.attr.edit',0,NULL),(440,0,'修改资料','edit',69,0,1,'app.attr.edit',0,NULL),(441,0,'添加资料操作','add',439,2,1,'app.attr',0,NULL),(442,0,'修改资料操作','edit',440,2,1,'app.attr',0,NULL),(443,0,'删除资料操作','del',69,2,2,'app.attr',0,NULL),(444,0,'修改设置操作','edit',73,2,1,'app.config',0,NULL),(445,0,'添加厂商操作','add',70,2,1,'app.firms',0,NULL),(446,0,'修改厂商操作','edit',71,2,2,'app.firms',0,NULL),(447,0,'删除厂商操作','del',71,2,3,'app.firms',0,NULL),(448,0,'修改厂商','edit',71,0,1,'app.firms.edit',0,NULL),(449,0,'厂商搜索操作','search',71,2,1,'app.firms',0,NULL),(450,0,'备份程序操作','backup',366,2,1,'data.file',0,NULL),(451,0,'修改论坛版块','edit',55,0,2,'bbs.type.edit',0,NULL),(452,0,'增加版块操作','add',54,2,3,'bbs.type',0,NULL),(453,0,'修改版块操作','edit',55,2,1,'bbs.type',0,NULL),(454,0,'删除版块操作','del',55,2,2,'bbs.type',0,NULL),(455,0,'删除主题操作','del',57,2,1,'bbs.bbs',0,NULL),(456,0,'移动主题操作','move',57,2,2,'bbs.bbs',0,NULL),(457,0,'审核主题操作','status',57,2,3,'bbs.bbs',0,NULL),(458,0,'主题属性操作','attr',57,2,4,'bbs.bbs',0,NULL),(459,0,'修改配置操作','edit',60,2,1,'bbs.config',0,NULL),(460,1,'自定义标签','label',245,0,3,'system.config.label.list',0,'fa-list-ul'),(461,0,'添加标签','add',460,0,1,'system.config.label.edit',0,NULL),(462,0,'修改标签','edit',460,0,2,'system.config.label.edit',0,NULL),(463,1,'添加标签操作','add',461,2,1,'system.config.label',0,NULL),(464,1,'修改标签操作','edit',462,2,2,'system.config.label',0,NULL),(465,1,'删除标签操作','del',460,2,3,'system.config.label',0,NULL),(466,1,'LOGO管理','logo',2,0,3,'system.set.logo',0,'fa-file-image-o'),(467,1,'修改LOGO','logo',466,2,1,'system.set.logo',0,NULL),(468,0,'添加版主','add',469,0,1,'bbs.moder.edit',0,NULL),(469,0,'版主管理','moder',55,0,2,NULL,0,NULL),(470,0,'修改版主','edit',469,0,2,'bbs.moder.edit',0,NULL),(471,0,'添加版主操作','add',470,2,1,'bbs.moder',0,NULL),(472,0,'修改版主操作','edit',470,2,1,'bbs.moder',0,NULL),(473,1,'留言配置','config',144,0,2,'operate.message.config',0,'cogs'),(474,0,'修改留言配置','edit',473,2,10,'operate.message.config',0,NULL),(476,0,'移动顺序操作','order',475,2,1,'novel.chapter',0,NULL),(477,1,'功能绑定','article',33,0,3,'system.module.config',1,NULL),(478,0,'功能绑定操作','edit',477,2,1,'system.module.config',0,NULL),(479,1,'功能绑定','picture',50,0,3,'system.module.config',1,NULL),(480,1,'功能绑定','bbs',59,0,3,'system.module.config',1,NULL),(481,1,'功能绑定','app',72,0,3,'system.module.config',1,NULL),(482,1,'功能绑定','novel',87,0,5,'system.module.config',1,NULL),(483,1,'功能绑定','author',103,0,10,'system.module.config',1,NULL),(484,1,'功能绑定','link',126,0,3,'system.module.config',1,NULL),(485,1,'功能绑定','user',117,0,3,'system.module.config',1,'lock'),(486,1,'模块设置','config',36,0,3,'',0,NULL),(487,1,'功能绑定','about',486,0,3,'system.module.config',1,NULL),(488,1,'规则生成','createrewrite',12,0,8,'system.seo.createrewrite',0,'arrows-h'),(489,1,'自定义字段','field',245,0,4,'system.config.field.list',0,'fa-indent'),(490,1,'新增字段','add',489,0,1,'system.config.field.edit',0,NULL),(491,1,'修改字段','edit',489,0,2,'system.config.field.edit',0,NULL),(492,0,'获取模块分类','gettype',489,2,1,'system.config.field',0,NULL),(493,0,'新增字段操作','add',490,2,1,'system.config.field',0,NULL),(494,0,'修改字段操作','edit',491,2,1,'system.config.field',0,NULL),(495,0,'删除字段操作','del',489,2,3,'system.config.field',0,NULL),(496,0,'获得自定义字段','getfield',489,2,2,'system.config.field',0,NULL),(497,1,'缓存性能','cache',1,0,3,'',0,'fa-viacoin'),(498,1,'缓存设置','set',497,0,1,'system.cache.set',0,'fa-list-ul'),(499,1,'清除缓存','clear',497,0,2,'system.cache.clear',0,'fa-refresh'),(500,0,'缓存设置操作','config',498,2,1,'system.cache',0,NULL),(501,0,'清除缓存操作','clear',499,2,2,'system.cache',0,NULL),(502,0,'评论详情','detail',131,0,1,'operate.replay.detail',0,NULL),(503,1,'功能绑定','search',136,0,4,'system.module.config',1,'lock'),(504,0,'拒绝作者审核','refuse_author_apply',95,0,0,'system.apply.refuse',0,NULL),(505,0,'模块设置操作','edit',106,2,0,'author.config',0,NULL),(506,0,'审核作者操作','status',95,2,1,'author.author',2,NULL),(507,0,'删除作者操作','del',95,2,2,'author.author',2,NULL),(508,0,'拒绝作者操作','refuse_author_apply',95,2,3,'system.apply',0,NULL),(509,0,'添加作者操作','add',94,2,1,'author.author',0,NULL),(510,0,'修改作者操作','edit',94,2,2,'author.author',0,NULL),(511,0,'修改作者','edit',95,0,3,'author.author.edit',0,NULL),(512,1,'待审封面','list',96,0,0,'author.novel.cover.list',0,NULL),(513,0,'审核封面操作','status',512,2,1,'author.novel.cover',0,NULL),(514,0,'删除封面操作','del',512,2,2,'author.novel.cover',0,NULL),(515,0,'拒绝封面审核','refuse_author_novel_cover',512,0,1,'system.apply.refuse',0,NULL),(516,0,'拒绝封面审核操作','refuse_author_novel_cover',515,2,1,'system.apply',0,NULL),(517,1,'修改原创申请','update',96,0,1,'author.novel.novelapply.list',0,NULL),(518,0,'删除原创申请操作','del',517,2,1,'author.novel.apply',0,NULL),(519,0,'审核原创申请操作','status',517,2,2,'author.novel.apply',0,NULL),(520,0,'查看变更数据','refuse_author_novel_edit',517,0,1,'system.apply.detail',0,NULL),(521,1,'拒绝小说修改','refuse_author_novel_editnovel',517,0,1,'system.apply.refuse',0,NULL),(522,1,'拒绝小说修改操作','refuse_author_novel_editnovel',521,2,1,'system.apply',0,NULL),(523,0,'查看变更数据','refuse_author_chapter_edit',98,0,1,'system.apply.detail',0,NULL),(524,1,'拒绝章节修改','refuse_author_novel_editchapter',98,0,1,'system.apply.refuse',0,NULL),(525,1,'拒绝章节修改操作','refuse_author_novel_editchapter',98,2,2,'system.apply',0,NULL),(526,1,'审核章节申请操作','status',98,2,3,'author.chapter.apply',0,NULL),(527,1,'清空章节申请操作','clear',98,2,4,'author.chapter.apply',0,NULL),(528,1,'清空小说申请操作','clear',517,2,3,'author.novel.apply',0,NULL),(529,1,'文章投稿','aritcle',92,1,3,'',0,NULL),(530,1,'稿件审核','aritcle_list',529,0,1,'author.article.list',0,NULL),(531,1,'删除章节申请操作','del',530,2,5,'author.chapter.apply',0,NULL),(532,1,'删除文章申请操作','del',530,2,1,'author.article.apply',0,NULL),(533,1,'清空文章申请操作','clear',530,2,2,'author.article.apply',0,NULL),(534,1,'审核文章申请操作','status',530,2,3,'author.article.apply',0,NULL),(535,0,'拒绝文章修改','refuse_author_article_edit',530,0,4,'system.apply.refuse',0,NULL),(536,1,'拒绝文章修改操作','refuse_author_article_editarticle',530,2,10,'system.apply',0,NULL),(537,1,'生成首页html操作','index',15,2,1,'system.seo.html',0,NULL),(538,1,'获得模块分类操作','gettype',15,2,2,'system.seo.html',0,NULL),(539,1,'生成内容HTML操作','content',15,2,3,'system.seo.html',0,NULL),(540,1,'生成列表HTML操作','list',15,2,4,'system.seo.html',0,NULL),(541,1,'生成分类首页HTML操作','tindex',15,2,5,'system.seo.html',0,NULL),(542,1,'生成目录HTML操作','menu',15,2,6,'system.seo.html',0,NULL),(543,1,'网站地图生成','sitemap',12,0,4,'system.seo.sitemap',0,'fa-sitemap'),(544,1,'RSS订阅生成','rss',12,0,5,'system.seo.rss',0,'fa-rss '),(545,1,'网站地图生成操作','sitemap',543,2,1,'system.seo.sitemap',0,NULL),(546,1,'获得模块分类操作','gettype',544,2,1,'system.seo.sitemap',0,NULL),(547,1,'生成rss首页操作','rss',544,2,2,'system.seo.sitemap',0,NULL),(548,1,'生成rss列表操作','list',544,2,3,'system.seo.sitemap',0,NULL),(549,1,'更新配置','update',2,0,7,'system.config.update',0,'fa-refresh'),(550,1,'更新配置操作','update',549,2,1,'system.config.config',0,NULL),(551,1,'自动上传错误操作','autoupload',254,2,3,'system.safe.errlog',0,NULL),(552,1,'云服务','cloud',0,0,6,NULL,0,'fa-cloud'),(553,1,'版本管理','version',552,1,1,NULL,0,'fa-random'),(554,1,'BUG反馈','bug',552,1,2,NULL,0,'fa-bug'),(555,1,'错误日志','err',552,1,3,NULL,0,'fa-archive'),(556,1,'在线升级','update',553,0,1,'cloud.version.update',0,' fa-cloud-upload'),(557,1,'全网反馈','all',554,0,1,'cloud.bug.all',0,'fa-files-o '),(558,1,'我的反馈','my',554,0,2,'cloud.bug.my',0,'fa-sticky-note-o '),(559,1,'我的错误','my',555,0,1,'cloud.err.my',0,'fa-bug'),(560,1,'上传错误日志操作','upload',254,2,4,'system.safe.errlog',0,NULL),(561,1,'获得反馈分类操作','gettype',557,2,1,'cloud.bug',0,NULL),(562,1,'获得反馈列表操作','getlist',557,2,2,'cloud.bug',0,NULL),(563,1,'查看反馈详情','detail',557,0,1,'cloud.bug.detail',0,NULL),(564,1,'提交新的反馈','add',558,0,1,'cloud.bug.add',0,NULL),(565,1,'提交新的反馈操作','add',564,2,3,'cloud.bug',0,NULL),(566,1,'获得错误列表操作','getlist',559,2,1,'cloud.err',0,NULL),(567,1,'获得最新下一版本操作','getnext',559,2,1,'cloud.version',0,NULL),(568,1,'升级操作','update',559,2,2,'cloud.version',0,NULL),(569,1,'获得进度操作','getbarline',559,2,3,'cloud.version',0,NULL),(570,0,'修改作者等级操作','update',104,2,2,'author.level',0,NULL),(571,0,'删除作者等级操作','del',104,2,4,'author.level',0,NULL),(572,1,'添加作者等级','add',104,0,0,'author.level.author.edit',0,NULL),(573,1,'添加作者等级操作','add',104,2,1,'author.level',0,NULL),(574,1,'修改全部作者等级操作','upall',104,2,3,'author.level',0,NULL),(575,1,'添加签约等级','add',105,0,1,'author.level.sign.edit',0,NULL),(576,1,'修改签约等级操作','update',105,2,0,'author.sign',0,NULL),(577,1,'删除签约等级操作','del',105,2,2,'author.sign',0,NULL),(578,1,'添加签约等级操作','add',105,2,3,'author.sign',0,NULL),(579,0,'修改全部签约等级操作','upall',105,2,4,'author.sign',0,NULL),(589,0,'修改道具','propsedit',582,0,4,'props.props.edit',0,NULL),(580,1,'打赏道具','props',92,1,8,NULL,0,NULL),(581,1,'添加道具','propsadd',580,0,3,'props.props.edit',0,NULL),(582,1,'道具列表','propslist',580,0,4,'props.props.list',0,NULL),(583,1,'添加分类','typeadd',580,0,1,'props.type.edit',0,NULL),(584,1,'分类列表','typelist',580,0,2,'props.type.list',0,NULL),(585,0,'添加分类操作','add',583,2,1,'props.type',0,NULL),(586,0,'修改分类操作','edit',584,2,2,'props.type',0,NULL),(587,1,'删除分类操作','del',584,2,1,'props.type',0,NULL),(588,0,'修改分类','typeedit',584,0,3,'props.type.edit',0,NULL),(590,0,'添加道具操作','add',581,2,1,'props.props',0,NULL),(591,0,'修改道具操作','edit',589,2,1,'props.props',0,NULL),(592,0,'删除道具操作','del',582,2,1,'props.props',0,NULL),(593,0,'修改道具状态操作','status',582,2,2,'props.props',0,NULL),(594,1,'销售记录','selllist',580,0,5,'props.sell.list',0,NULL),(595,0,'删除销售记录操作','del',594,2,1,'props.sell',0,NULL),(596,0,'在线签约','add',80,0,5,'novel.sign.add',0,NULL),(597,0,'上架销售','add',80,0,6,'novel.sell.add',0,NULL),(598,0,'在线签约操作','add',596,2,1,'novel.sign',0,NULL),(599,0,'上架销售操作','add',597,2,1,'novel.sell',0,NULL),(600,0,'下架销售操作','remove',597,2,2,'novel.sell',0,NULL),(601,1,'卡密管理','card',107,1,7,'',0,'fa-credit-card'),(602,1,'添加卡密','cardcreate',601,0,1,'user.card.create',0,'fa-list-ol'),(603,1,'卡密列表','cardlist',601,0,2,'user.card.list',0,'fa-cc-mastercard'),(604,0,'创建卡密操作','create',602,2,1,'user.card',0,NULL),(605,1,'卡密下载','carddown',601,0,3,'data.file.list&path=files/card',0,'fa-download'),(606,0,'下载文件操作','down',366,2,5,'data.file',0,NULL),(607,0,'删除卡密操作','del',603,2,1,'user.card',0,NULL),(608,0,'清空卡密操作','clear',603,2,2,'user.card',0,NULL),(609,1,'粉丝互动','',74,1,5,NULL,0,NULL),(610,1,'打赏记录','fansreward',609,0,2,'novel.fans.reward',0,NULL),(611,1,'推荐记录','fansticket',609,0,3,'novel.fans.ticket',0,NULL),(612,1,'订阅记录','fanssub',609,0,4,'novel.fans.sub',0,NULL),(613,1,'粉丝等级','fanslevel',609,0,1,'novel.fans.level',0,NULL),(614,0,'删除打赏记录操作','delreward',610,2,1,'novel.fans',0,NULL),(615,0,'清空打赏记录操作','clearreward',610,2,2,'novel.fans',0,NULL),(616,0,'删除推荐记录操作','delticket',611,2,3,'novel.fans',0,NULL),(617,0,'清空推荐记录操作','clearticket',611,2,4,'novel.fans',0,NULL),(618,0,'删除订阅记录操作','delsub',612,2,5,'novel.fans',0,NULL),(619,0,'清空订阅记录操作','clearsub',612,2,6,'novel.fans',0,NULL),(620,0,'小说粉丝等级添加操作','leveladd',613,2,7,'novel.fans',0,NULL),(621,0,'小说粉丝等级修改操作','leveledit',613,2,8,'novel.fans',0,NULL),(622,0,'小说粉丝等级删除操作','leveldel',613,2,9,'novel.fans',0,NULL),(623,1,'财务设置','config',99,0,8,'finance.set.config',0,'fa-krw'),(624,1,'充值等级','level',99,0,5,'finance.level',0,'fa-align-center'),(626,0,'财务设置操作','edit',623,2,1,'finance.config',0,NULL),(627,0,'充值等级添加操作','leveladd',624,2,1,'finance.level',0,NULL),(628,0,'充值等级修改操作','leveledit',624,2,2,'finance.level',0,NULL),(629,0,'充值等级删除操作','leveldel',624,2,3,'finance.level',0,NULL),(630,0,'删除资金变更记录操作','del',101,2,1,'finance.finance',0,NULL),(631,0,'清空资金变更记录操作','clear',101,2,2,'finance.finance',0,NULL),(632,1,'使用记录','log',601,0,3,'user.card.log',0,'fa-slack'),(633,0,'删除卡号使用记录操作','del',632,2,1,'user.cardlog',0,NULL),(634,0,'清空卡号使用记录操作','clear',632,2,2,'user.cardlog',0,NULL),(635,0,'删除充值记录操作','del',102,2,1,'finance.order.charge',0,NULL),(636,0,'清空充值记录操作','clear',102,2,2,'finance.order.charge',0,NULL),(637,0,'删除提现申请操作','del',100,2,1,'finance.order.cash',0,NULL),(638,0,'清空提现申请操作','clear',100,2,2,'finance.order.cash',0,NULL),(639,0,'处理提现申请操作','status',100,2,3,'finance.order.cash',0,NULL),(640,0,'查看文章投稿详情','refuse_author_article_edit',530,0,1,'system.apply.detail',0,NULL),(641,1,'链接提交','urlpost',12,0,3,'system.seo.urlpost',0,'fa-compress'),(642,0,'链接提交操作','post',641,2,1,'system.seo.urlpost',0,NULL),(643,1,'表情管理','face',130,1,2,'',0,'fa-drupal'),(644,1,'表情安装','install',643,0,1,'operate.face.install',0,'fa-tripadvisor'),(645,0,'表情安装操作','install',644,2,1,'operate.face',0,NULL),(646,1,'登录验证','code',22,0,3,'system.safe.code',3,'codepen'),(647,0,'验证码设置操作','config',646,2,1,'system.safe.code',0,NULL),(648,1,'站群配置','config',662,0,12,'system.site.config',0,'fa-crop'),(649,1,'修改站群配置','edit',648,2,1,'system.site.config',0,NULL),(650,1,'模块安装','module_install',2,0,10,'system.module.install',0,'fa-th-large'),(651,0,'模块安装操作','install',650,2,1,'system.module.install',0,NULL),(652,1,'开发者设置','development',22,0,1,'system.dev.config',0,'fa-connectdevelop'),(653,0,'开发者设置操作','config',652,2,1,'system.dev.config',0,NULL),(654,1,'错误页面','errpage',12,0,14,'system.seo.errpage',0,'fa-pagelines'),(655,1,'蜘蛛记录','spider',12,0,15,'system.seo.spider',0,'fa-opencart'),(656,0,'删除错误页面操作','del',654,2,1,'system.seo.errpage',0,NULL),(657,0,'清空错误页面操作','clear',654,2,2,'system.seo.errpage',0,NULL),(658,0,'删除错误页面操作','del',655,2,1,'system.seo.spider',0,NULL),(659,0,'清空错误页面操作','clear',655,2,2,'system.seo.spider',0,NULL),(660,1,'错误页面图','errpage',162,0,3,'data.chart.errpage',0,NULL),(661,1,'蜘蛛日志图','spider',162,0,4,'data.chart.spider',0,NULL),(662,1,'站群管理','site',1,0,5,NULL,0,'fa-object-group'),(663,1,'站内站点管理','site',662,0,1,'system.site.site',0,'fa-indent'),(664,1,'站外站点管理','product',662,0,2,'system.site.product',0,'fa-dedent'),(665,0,'站外站点添加','add',664,0,1,'system.site.product.edit',0,NULL),(666,0,'站外站点编辑','edit',664,0,2,'system.site.product.edit',0,NULL),(667,0,'删除站外站点操作','del',664,2,1,'system.site.product',0,NULL),(668,0,'清空站外站点操作','clear',664,2,2,'system.site.product',0,NULL),(669,0,'站外站点添加操作','add',665,2,1,'system.site.product',0,NULL),(670,0,'站外站点编辑操作','edit',666,2,1,'system.site.product',0,NULL),(671,0,'站外站点测试操作','test',665,2,2,'system.site.product',0,NULL),(672,0,'站内站点添加','add',663,0,1,'system.site.site.edit',0,NULL),(673,0,'站内站点编辑','edit',663,0,2,'system.site.site.edit',0,NULL),(674,0,'删除站内站点操作','del',663,2,1,'system.site.site',0,NULL),(675,0,'清空站内站点操作','clear',663,2,2,'system.site.site',0,NULL),(676,0,'站内站点添加操作','add',672,2,1,'system.site.site',0,NULL),(677,0,'站内站点编辑操作','edit',673,2,1,'system.site.site',0,NULL),(678,0,'站内站点审核操作','status',663,2,3,'system.site.site',0,NULL),(679,0,'站外站点审核操作','status',664,2,3,'system.site.product',0,NULL),(680,1,'检索条件','novel_list',87,0,4,'system.retrieval.list',1,NULL),(681,0,'删除检索操作','novel_del',680,2,1,'system.retrieval',0,NULL),(682,0,'审核检索操作','novel_status',680,2,2,'system.retrieval',0,NULL),(683,0,'编辑检索','novel_edit',680,0,3,'system.retrieval.edit',0,NULL),(684,0,'添加检索','novel_add',680,0,4,'system.retrieval.edit',0,NULL),(685,0,'添加检索操作','novel_add',684,2,1,'system.retrieval',0,NULL),(686,0,'编辑检索操作','novel_edit',683,2,1,'system.retrieval',0,NULL),(687,1,'检索分类','novel_type',87,0,3,'system.retrieval.type',1,NULL),(688,0,'修改分类操作','novel_type',687,2,1,'system.retrieval',0,NULL),(689,0,'修改分类状态操作','novel_typestatus',687,2,2,'system.retrieval',0,NULL),(690,1,'添加邮件服务','add',173,0,1,'system.email.email.edit',0,NULL),(691,1,'编辑邮件服务','edit',173,0,2,'system.email.email.edit',0,NULL),(692,0,'添加邮件服务操作','add',690,2,11,'system.email.email',0,NULL),(693,0,'编辑邮件服务操作','edit',691,2,1,'system.email.email',0,NULL),(694,0,'邮件服务状态操作','status',173,2,3,'system.email.email',0,NULL),(695,0,'邮件服务删除操作','del',173,2,4,'system.email.email',0,NULL),(696,1,'添加邮件模版','add',173,0,7,'system.email.temp.edit',0,NULL),(697,1,'编辑邮件模版','edit',173,0,8,'system.email.temp.edit',0,NULL),(698,0,'添加邮件模版操作','add',696,2,1,'system.email.temp',0,NULL),(699,0,'编辑邮件模版操作','edit',697,2,1,'system.email.temp',0,NULL),(700,0,'邮件模版状态操作','status',173,2,9,'system.email.temp',0,NULL),(701,0,'邮件模版删除操作','del',173,2,10,'system.email.temp',0,NULL),(702,1,'邮件日志','emaillog',22,0,13,'system.safe.emaillog',0,'fa-university'),(703,1,'邮件日志详情','detail',702,0,1,'system.safe.emaillog.detail',0,NULL),(704,0,'删除邮件日志操作','del',702,2,1,'system.safe.emaillog',0,NULL),(705,0,'清空邮件日志操作','clear',702,2,2,'system.safe.emaillog',0,NULL),(706,1,'阅读记录','list',609,0,5,'novel.fans.read',0,NULL),(707,0,'删除阅读记录','novel_del',706,2,1,'user.read',0,NULL),(708,0,'清空阅读记录','novel_clear',706,2,2,'user.read',0,NULL),(709,0,'推荐关键词操作','rec',137,2,3,'operate.search',0,NULL),(710,1,'开发工具','development',1,0,10,NULL,0,'fa-joomla'),(711,1,'新增页面','addpage',710,0,1,'system.dev.addpage',0,'fa-bookmark'),(712,0,'新增页面操作','add',711,2,1,'system.dev.addpage',0,NULL),(713,1,'财务统计图','finance',162,0,5,'data.chart.finance',0,NULL),(714,1,'默认首页','default_index',9,0,4,'system.menu.default_index',0,'fa-list-ul'),(715,0,'默认首页操作','default_index',714,0,1,'system.menu.menu',0,NULL),(716,0,'销售记录','sell_log',609,0,10,'novel.sell.log',0,NULL),(717,1,'云应用','apps',552,1,0,NULL,0,'fa-cubes'),(718,1,'我的插件','plugin',717,0,2,'cloud.apps.plugin',0,'fa-plug'),(719,0,'安装插件','install',718,2,1,'cloud.apps.plugin',0,NULL),(720,0,'卸载插件','uninstall',718,2,2,'cloud.apps.plugin',0,NULL),(721,0,'插件管理','manager',718,0,1,'cloud.apps.plugin.manager',0,NULL),(722,0,'插件首页','index',721,0,1,'cloud.apps.plugin.index',0,NULL),(723,0,'插件业务管理','business',721,0,2,'cloud.apps.plugin.business',0,NULL),(724,0,'插件配置修改操作','config',723,2,1,'cloud.apps.plugin',0,NULL),(725,0,'水印生成测试','water_test',5,2,2,'system.set.water',0,NULL),(726,1,'新增分类','type_add',149,0,1,'operate.zt.typeedit',0,'fa-plus-circle'),(727,1,'分类列表','type_list',149,0,2,'operate.zt.typelist',0,'fa-indent'),(728,0,'增加分类操作','type_add',726,2,1,'operate.zt.type',0,NULL),(729,0,'修改分类操作','type_edit',731,2,1,'operate.zt.type',0,NULL),(730,0,'删除分类操作','type_del',727,2,2,'operate.zt.type',0,NULL),(731,0,'编辑分类','type_edit',727,0,2,'operate.zt.typeedit',0,NULL),(732,1,'修改配置','config',149,0,9,'operate.zt.config',0,'fa-wrench'),(733,0,'修改配置操作','config_edit',732,2,1,'operate.zt.config',0,NULL),(734,1,'新增分类','type_add',141,0,1,'operate.flash.typeedit',0,'fa-plus-circle'),(735,0,'编辑分类','type_edit',736,0,2,'operate.flash.typeedit',0,NULL),(736,1,'分类列表','type_list',141,0,2,'operate.flash.typelist',0,'fa-indent'),(737,0,'增加分类操作','type_add',734,2,1,'operate.flash.type',0,NULL),(738,0,'修改分类操作','type_edit',735,2,1,'operate.flash.type',0,NULL),(739,0,'删除分类操作','type_del',736,2,2,'operate.flash.type',0,NULL),(740,0,'添加限时免费','add',80,0,7,'novel.timelimit.edit',0,NULL),(741,0,'添加限时免费操作','add',740,2,1,'novel.timelimit',0,NULL),(742,1,'限时免费','timelimit',78,0,4,'novel.timelimit.list',0,NULL),(743,0,'编辑限时免费','edit',742,0,1,'novel.timelimit.edit',0,NULL),(744,0,'编辑限时免费操作','edit',743,2,1,'novel.timelimit',0,NULL),(745,0,'删除限时免费操作','del',742,2,2,'novel.timelimit',0,NULL),(746,0,'福利设置','welfare',80,0,7,'novel.sell.welfare',0,NULL),(747,0,'福利设置操作','edit',746,2,1,'novel.sell.welfare',0,NULL),(748,0,'结算统计','settlement',80,0,8,'novel.sell.settlement',0,NULL),(749,0,'结算申请操作','apply',748,2,1,'novel.sell.settlement',0,NULL),(750,1,'财务申请','apply_list',99,0,0,'finance.apply.list',0,'fa-gg'),(751,0,'财务申请详情','apply_detail',750,0,1,'finance.apply.detail',0,NULL),(752,0,'财务申请处理操作','status',751,2,1,'finance.apply',0,NULL),(753,0,'财务申请删除操作','del',750,2,1,'finance.apply',0,NULL),(754,0,'财务申请清空操作','clear',750,2,2,'finance.apply',0,NULL),(755,1,'新增API','addapi',710,0,2,'system.dev.addapi',0,'fa-random'),(756,0,'新增API操作','add',755,2,1,'system.dev.addapi',0,NULL),(757,1,'URL模式','urlmode',12,0,1,'system.seo.urlmode',0,'fa-link'),(758,0,'URL模式保存','config',757,2,1,'system.seo.urlmode',0,NULL),(759,1,'需求众研','list',554,0,3,'cloud.together.list',0,'fa-houzz'),(760,0,'获得需求列表操作','getlist',759,2,1,'cloud.together',0,NULL),(761,0,'需求操作','operation',759,2,2,'cloud.together',0,NULL),(762,1,'需求详情','detail',759,0,1,'cloud.together.detail',0,NULL),(763,0,'获得需求详情操作','detail',762,2,1,'cloud.together',0,NULL),(764,1,'提交需求','add',759,0,2,'cloud.together.add',0,NULL),(765,0,'提交需求操作','add',764,2,1,'cloud.together',0,NULL),(766,0,'上传txt','upload',84,0,10,'novel.txt.upload',0,NULL),(767,0,'初始化TXT操作','init',766,2,1,'novel.txt',0,NULL),(768,0,'删除上传的TXT操作','del',766,2,2,'novel.txt',0,NULL),(769,0,'导入TXT操作','import',766,2,3,'novel.txt',0,NULL),(770,1,'应用中心','apps',717,0,1,'cloud.apps.index',0,'fa-th'),(771,0,'安装应用','install',770,2,1,'cloud.apps',0,NULL),(772,1,'静态计划','html_plan',12,0,3,'system.seo.html_plan.list',0,'fa-file-text-o'),(773,0,'添加静态计划操作','add',772,2,1,'system.seo.html_plan',0,NULL),(774,0,'删除静态计划操作','del',772,2,2,'system.seo.html_plan',0,NULL),(775,0,'执行静态计划操作','run',772,2,3,'system.seo.html_plan',0,NULL),(776,1,'微信公众号','weixin',130,1,2,NULL,0,'fa-weixin'),(777,1,'公众号列表','account_list',776,0,1,'operate.weixin.account.list',0,'fa-list'),(778,0,'添加公众号','add',777,0,2,'operate.weixin.account.edit',0,NULL),(779,0,'编辑公众号','edit',777,0,2,'operate.weixin.account.edit',0,NULL),(780,0,'添加公众号操作','add',778,2,1,'operate.weixin.account',0,NULL),(781,0,'编辑公众号操作','edit',779,2,2,'operate.weixin.account',0,NULL),(782,0,'删除公众号操作','del',777,2,3,'operate.weixin.account',0,NULL),(783,0,'审核公众号操作','status',777,2,4,'operate.weixin.account',0,NULL),(784,0,'检查公众号操作','check',777,2,5,'operate.weixin.account',0,NULL),(785,0,'设为主号操作','main',777,2,6,'operate.weixin.account',0,NULL),(786,1,'自定义菜单','menu_list',776,0,2,'operate.weixin.menu.list',0,'fa-medium'),(787,0,'添加自定义菜单','add',786,0,2,'operate.weixin.menu.edit',0,NULL),(788,0,'编辑自定义菜单','edit',786,0,2,'operate.weixin.menu.edit',0,NULL),(789,0,'添加自定义菜单操作','add',787,2,1,'operate.weixin.menu',0,NULL),(790,0,'编辑自定义菜单操作','edit',788,2,2,'operate.weixin.menu',0,NULL),(791,0,'删除自定义菜单操作','del',786,2,3,'operate.weixin.menu',0,NULL),(792,0,'审核自定义菜单操作','status',786,2,4,'operate.weixin.menu',0,NULL),(793,0,'推送自定义菜单操作','push',786,2,5,'operate.weixin.menu',0,NULL),(794,0,'复制自定义菜单操作','copy',786,2,6,'operate.weixin.menu',0,NULL),(795,0,'复制自定义菜单','copy',786,0,3,'operate.weixin.menu.copy',0,NULL),(796,1,'自动回复','autoreply_list',776,0,3,'operate.weixin.autoreply.list',0,'fa-retweet'),(797,0,'添加自动回复','add',796,0,2,'operate.weixin.autoreply.edit',0,NULL),(798,0,'编辑自动回复','edit',796,0,2,'operate.weixin.autoreply.edit',0,NULL),(799,0,'复制自动回复','copy',796,0,3,'operate.weixin.autoreply.copy',0,NULL),(800,0,'添加自动回复操作','add',797,2,1,'operate.weixin.autoreply',0,NULL),(801,0,'编辑自动回复操作','edit',798,2,2,'operate.weixin.autoreply',0,NULL),(802,0,'删除自动回复操作','del',796,2,3,'operate.weixin.autoreply',0,NULL),(803,0,'审核自动回复操作','status',796,2,4,'operate.weixin.autoreply',0,NULL),(804,0,'复制自动回复操作','copy',799,2,5,'operate.weixin.autoreply',0,NULL),(805,1,'消息管理','msg_list',776,0,5,'operate.weixin.msg.list',0,'fa-comment-o'),(806,0,'删除消息操作','del',805,2,1,'operate.weixin.msg',0,NULL),(807,0,'查看消息详情','detail',805,0,2,'operate.weixin.msg.detail',0,NULL),(808,1,'素材管理','media_list',776,0,4,'operate.weixin.media.list',0,'fa-paperclip'),(809,0,'查看素材详情','detail',808,0,2,'operate.weixin.media.detail',0,NULL),(810,0,'删除素材操作','del',808,2,1,'operate.weixin.media',0,NULL),(811,0,'添加素材','add',808,0,1,'operate.weixin.media.edit',0,NULL),(812,0,'添加素材操作','add',811,2,2,'operate.weixin.media',0,NULL),(813,1,'粉丝管理','media_list',776,0,6,'operate.weixin.fans.list',0,'fa-street-view'),(814,0,'查看粉丝详情','detail',813,0,2,'operate.weixin.fans.detail',0,NULL),(815,0,'删除粉丝操作','del',813,2,1,'operate.weixin.fans',0,NULL),(816,0,'素材查找带回','lookup',808,0,1,'operate.weixin.media.lookup',0,NULL),(817,1,'检索条件','app_list',68,0,5,'system.retrieval.list',1,NULL),(818,0,'删除检索操作','app_del',817,2,1,'system.retrieval',0,NULL),(819,0,'审核检索操作','app_status',817,2,2,'system.retrieval',0,NULL),(820,0,'编辑检索','app_edit',817,0,3,'system.retrieval.edit',0,NULL),(821,0,'添加检索','app_add',817,0,4,'system.retrieval.edit',0,NULL),(822,0,'添加检索操作','app_add',821,2,1,'system.retrieval',0,NULL),(823,0,'编辑检索操作','app_edit',820,2,1,'system.retrieval',0,NULL),(824,1,'检索分类','app_type',68,0,4,'system.retrieval.type',1,NULL),(825,0,'修改分类操作','app_type',823,2,1,'system.retrieval',0,NULL),(826,0,'修改分类状态操作','app_typestatus',823,2,2,'system.retrieval',0,NULL),(827,0,'查看用户消息详情','detail',116,0,2,'user.msg.detail',0,NULL),(828,1,'新增插件','addplugin',710,0,3,'system.dev.addplugin',0,'fa-slack'),(829,0,'添加插件操作','plugin_add',828,2,1,'system.dev.addplugin',0,NULL),(830,0,'添加插件配置操作','config_add',828,2,2,'system.dev.addplugin',0,NULL),(831,0,'获取插件配置操作','getpluginconfig',828,2,3,'system.dev.addplugin',0,NULL),(832,0,'删除插件配置操作','config_del',828,2,4,'system.dev.addplugin',0,NULL),(833,0,'更新应用','update',718,2,3,'cloud.apps',0,NULL),(834,1,'敏感词库','shield',22,0,22,'system.safe.shield',0,'fa-font'),(835,0,'修改敏感词库操作','config',834,2,1,'system.safe.shield',0,NULL),(836,0,'用户处罚','punish',110,0,4,'user.punish.punish',0,NULL),(837,0,'用户处罚操作','punish',836,2,1,'user.punish',0,NULL),(838,0,'解除用户处罚操作','unpunish',836,2,2,'user.punish',0,NULL),(839,1,'处罚记录','punishlist',108,0,5,'user.punish.list',0,'fa-user-times'),(840,0,'删除用户处罚操作','del',839,2,1,'user.punish',0,NULL),(841,1,'消息模版','msglist',245,0,5,'system.config.msg.list',0,'fa-envelope-o'),(842,0,'新增消息模版','add',841,0,1,'system.config.msg.edit',0,NULL),(843,0,'编辑消息模版','edit',841,0,2,'system.config.msg.edit',0,NULL),(844,0,'新增消息模版操作','add',842,2,1,'system.config.msg',0,NULL),(845,0,'编辑消息模版操作','edit',843,2,1,'system.config.msg',0,NULL),(846,0,'删除消息模版操作','del',841,2,1,'system.config.msg',0,NULL),(870,0,'删除信息日志','del',867,2,1,'system.safe.msglog',0,NULL),(869,0,'清空信息日志','clear',867,2,2,'system.safe.msglog',0,NULL),(868,1,'信息日志','msglog',22,0,12,'system.safe.msglog',0,'fa-commenting'),(867,1,'短信日志','smslog',22,0,14,'system.set.smslog',0,'fa-envelope'),(853,1,'短信设置','sms',245,0,6,'system.set.sms',0,'fa-envelope'),(854,1,'添加短信模版','add',853,0,1,'system.set.sms.edit',0,NULL),(855,1,'编辑短信模版','edit',853,0,2,'system.set.sms.edit',0,NULL),(856,0,'添加短信模版操作','add',854,2,11,'system.sms',0,NULL),(857,0,'编辑短信模版操作','edit',855,2,1,'system.sms',0,NULL),(858,0,'短信模版状态操作','status',853,2,3,'system.sms',0,NULL),(859,0,'短信模版删除操作','del',853,2,4,'system.sms',0,NULL),(860,0,'短信日志删除操作','del',866,2,4,'system.smslog',0,NULL),(861,0,'短信日志清空操作','clear',866,2,4,'system.smslog',0,NULL),(862,1,'销售报表','report_list',99,0,0,'finance.report.list',0,'fa-bar-chart-o'),(863,0,'结算销售报表确认','confirm',862,2,1,'finance.report',0,NULL),(864,0,'结算销售报表','settlement',862,2,2,'finance.report',0,NULL),(865,1,'结算记录','report_order',99,0,0,'finance.report.order',0,'fa-align-justify'),(866,1,'章节批量操作','batch',86,2,4,'novel.chapter',0,NULL),(871,1,'标签管理','novel_tags',103,0,4,'system.tags.list',1,NULL),(872,0,'删除标签操作','novel_del',871,2,3,'system.tags',0,NULL),(873,0,'标签推荐操作','novel_author_rec',871,2,4,'system.tags',0,NULL),(874,0,'编辑标签','novel_edit',871,0,2,'system.tags.edit',0,NULL),(875,0,'添加标签','novel_add',871,0,1,'system.tags.edit',0,NULL),(876,0,'添加标签操作','novel_add',875,2,1,'system.tags',0,NULL),(877,0,'编辑标签操作','novel_edit',874,2,1,'system.tags',0,NULL),(878,0,'清空标签操作','novel_clear',871,2,5,'system.tags',0,NULL),(879,1,'标签分类管理','novel_tags_type',103,0,3,'system.tags.type.list',1,NULL),(880,0,'删除标签分类操作','novel_del',879,2,3,'system.tags.type',0,NULL),(881,0,'编辑标签分类','novel_edit',879,0,2,'system.tags.type.edit',0,NULL),(882,0,'添加标签分类','novel_add',879,0,1,'system.tags.type.edit',0,NULL),(883,0,'添加标签分类操作','novel_add',882,2,1,'system.tags.type',0,NULL),(884,0,'编辑标签分类操作','novel_edit',881,2,1,'system.tags.type',0,NULL),(885,1,'TTS发音人','tts_voicet_list',245,0,8,'system.api.ttsvoicet.list',0,'fa-music'),(886,0,'删除发音人操作','tts_voicet_del',885,2,1,'system.api.ttsvoicet',0,NULL),(887,0,'编辑发音人','tts_voicet_edit',885,0,2,'system.api.ttsvoicet.edit',0,NULL),(888,0,'添加发音人','tts_voicet_add',885,0,3,'system.api.ttsvoicet.add',0,NULL),(889,0,'添加发音人操作','tts_voicet_add',888,2,1,'system.api.ttsvoicet',0,NULL),(890,0,'编辑发音人操作','tts_voicet_edit',887,2,1,'system.api.ttsvoicet',0,NULL),(891,1,'编辑模块','editor',25,0,7,'',0,'fa-columns'),(892,1,'分组管理','group',891,1,1,'',0,NULL),(893,1,'分组列表','group_list',892,1,1,'editor.group.list',0,NULL),(894,1,'编辑管理','editor',891,1,1,'',0,NULL),(895,1,'编辑列表','editor_list',894,1,1,'editor.editor.list',0,NULL),(896,0,'添加编辑分组','add',893,0,1,'editor.group.edit',0,NULL),(897,0,'添加编辑分组操作','add',896,2,1,'editor.group',0,NULL),(898,0,'编辑编辑分组','edit',893,0,2,'editor.group.edit',0,NULL),(899,0,'编辑编辑分组操作','edit',898,2,1,'editor.group',0,NULL),(900,0,'删除编辑分组操作','del',893,2,3,'editor.group',0,NULL),(901,0,'添加编辑','add',895,0,1,'editor.editor.edit',0,NULL),(902,0,'添加编辑操作','add',901,2,1,'editor.editor',0,NULL),(903,0,'修改编辑','edit',895,0,1,'editor.editor.edit',0,NULL),(904,0,'修改编辑操作','edit',903,2,2,'editor.editor',0,NULL),(905,0,'快速审核编辑操作','status',895,2,3,'editor.editor',0,NULL),(906,1,'作品关联','works_list',894,1,1,'editor.works.list',0,NULL),(907,0,'添加作品关联','add',906,0,1,'editor.works.edit',0,NULL),(908,0,'添加作关联操作','add',907,2,1,'editor.works',0,NULL),(909,0,'修改作品关联','edit',906,0,1,'editor.works.edit',0,NULL),(910,0,'修改作品关联操作','edit',909,2,2,'editor.works',0,NULL),(911,0,'删除作品关联操作','del',906,2,3,'editor.works',0,NULL),(912,1,'模块设置','editor',891,1,1,'',0,NULL),(913,1,'功能绑定','editor',912,0,9,'system.module.config',1,NULL),(914,0,'添加编辑分组操作','add',896,2,1,'editor.group',0,NULL),(915,1,'分组成员','editor_bind',892,1,2,'editor.bind.list',0,NULL),(916,0,'添加成员','add',915,0,1,'editor.bind.edit',0,NULL),(917,0,'添加成员操作','add',916,2,1,'editor.bind',0,NULL),(918,0,'修改成员','edit',915,0,1,'editor.bind.edit',0,NULL),(919,0,'修改成员操作','edit',918,2,2,'editor.bind',0,NULL),(920,0,'删除成员操作','del',915,2,3,'editor.bind',0,NULL);

/*Table structure for table `wm_system_menu_default` */

DROP TABLE IF EXISTS `wm_system_menu_default`;

CREATE TABLE `wm_system_menu_default` (
  `default_id` int(4) NOT NULL AUTO_INCREMENT,
  `default_controller` varchar(30) NOT NULL COMMENT '控制器名字',
  `default_mid` int(4) NOT NULL COMMENT '管理员id',
  PRIMARY KEY (`default_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

/*Data for the table `wm_system_menu_default` */

/*Table structure for table `wm_system_menu_quick` */

DROP TABLE IF EXISTS `wm_system_menu_quick`;

CREATE TABLE `wm_system_menu_quick` (
  `quick_id` int(4) NOT NULL AUTO_INCREMENT,
  `quick_menu_id` int(4) DEFAULT NULL COMMENT '菜单的id',
  `quick_order` int(4) DEFAULT '9' COMMENT '快捷菜单的显示顺序',
  `quick_manager_id` int(4) DEFAULT NULL COMMENT '管理员的id',
  PRIMARY KEY (`quick_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='快捷菜单';

/*Data for the table `wm_system_menu_quick` */

/*Table structure for table `wm_system_msg_log` */

DROP TABLE IF EXISTS `wm_system_msg_log`;

CREATE TABLE `wm_system_msg_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `log_status` tinyint(1) DEFAULT '0' COMMENT '使用状态:0=未使用,1已使用,2=已过期',
  `log_channel` varchar(20) DEFAULT NULL COMMENT '消息渠道',
  `log_channel_id` int(11) DEFAULT NULL COMMENT '消息日志ID',
  `log_receive` varchar(50) DEFAULT NULL COMMENT '消息接收人',
  `log_params` varchar(500) DEFAULT NULL COMMENT '消息内容',
  `log_time` int(11) DEFAULT '0' COMMENT '发送时间',
  `log_exptime` int(11) DEFAULT '0' COMMENT '过期时间',
  PRIMARY KEY (`log_id`),
  KEY `index` (`log_channel`,`log_channel_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='信息发送记录索引';

/*Data for the table `wm_system_msg_log` */

/*Table structure for table `wm_system_msg_temp` */

DROP TABLE IF EXISTS `wm_system_msg_temp`;

CREATE TABLE `wm_system_msg_temp` (
  `temp_id` int(4) NOT NULL AUTO_INCREMENT COMMENT '模版的id',
  `temp_name` varchar(50) NOT NULL COMMENT '模版名字',
  `temp_module` varchar(20) DEFAULT NULL COMMENT '所属的模块',
  `temp_key` varchar(50) NOT NULL COMMENT '模版的标识',
  `temp_content` text NOT NULL COMMENT '发信内容',
  PRIMARY KEY (`temp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=7 DEFAULT CHARSET=utf8 COMMENT='消息模版表';

/*Data for the table `wm_system_msg_temp` */

insert  into `wm_system_msg_temp`(`temp_id`,`temp_name`,`temp_module`,`temp_key`,`temp_content`) values (1,'禁止登录','user','user_punish_login','<p>您已经被系统禁止登录，处罚原因：{原因}。</p>'),(2,'禁止发言','user','user_punish_talk','<p>您已经被系统禁止发言，处罚原因：{原因}。</p>'),(3,'解除禁止登录','user','user_punish_unlogin','<p>您已经被系统解除禁止登录，请牢记平台规则，以免再次被封禁。</p>'),(4,'解除禁止发言','user','user_punish_untalk','<p>您已经被系统解除禁言，请牢记平台规则，以免再次被禁言。</p>'),(5,'预警通知_后台登录','all','warning_admin_login','<p>尊敬的管理员：IP{后台登录ip}于{后台登录时间}登录账号{后台登录账号}，登录状态：{后台登录状态}！</p>'),(6,'预警通知_代码报错','all','warning_code_eroor','<p>尊敬的管理员：网站发生错误，{代码报错详情}！</p>');

/*Table structure for table `wm_system_retrieval` */

DROP TABLE IF EXISTS `wm_system_retrieval`;

CREATE TABLE `wm_system_retrieval` (
  `retrieval_id` int(4) NOT NULL AUTO_INCREMENT,
  `retrieval_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用状态，0为禁用，1为使用',
  `retrieval_module` varchar(20) NOT NULL COMMENT '所属模块',
  `retrieval_type_id` int(4) NOT NULL DEFAULT '0' COMMENT '检索条件类型ID',
  `retrieval_title` varchar(10) NOT NULL COMMENT '检索条件名字',
  `retrieval_field` varchar(20) NOT NULL COMMENT '检索条件的字段',
  `retrieval_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '检索类型，-1为倒序，0为顺序，1为等于，2为小于，3为大于，4为区间,5为首字母，6为相似，7为数字开头',
  `retrieval_value` varchar(20) NOT NULL COMMENT '检索的值',
  `retrieval_order` int(1) NOT NULL COMMENT '显示顺序',
  PRIMARY KEY (`retrieval_id`)
) ENGINE=MyISAM AUTO_INCREMENT=84 DEFAULT CHARSET=utf8 COMMENT='分类检索条件表';

/*Data for the table `wm_system_retrieval` */

insert  into `wm_system_retrieval`(`retrieval_id`,`retrieval_status`,`retrieval_module`,`retrieval_type_id`,`retrieval_title`,`retrieval_field`,`retrieval_type`,`retrieval_value`,`retrieval_order`) values (1,1,'novel',1,'全部','novel_process',1,'-1',1),(2,1,'novel',1,'连载','novel_process',1,'1',2),(3,1,'novel',1,'完结','novel_process',1,'2',3),(4,1,'novel',2,'30万以下','novel_wordnumber',2,'300000',2),(5,1,'novel',2,'30-100万','novel_wordnumber',4,'300000,1000000',3),(6,1,'novel',2,'100-200万','novel_wordnumber',4,'1000000,2000000',4),(7,1,'novel',2,'200万以上','novel_wordnumber',3,'2000000',5),(8,1,'novel',2,'全部','novel_wordnumber',1,'-1',1),(9,1,'novel',3,'全部','novel_chapter',1,'-1',1),(10,1,'novel',3,'低于1000章','novel_chapter',2,'1000',2),(11,1,'novel',3,'1000-2000章','novel_chapter',4,'1000,2000',3),(12,1,'novel',3,'2000-3000章','novel_chapter',4,'2000,3000',4),(13,1,'novel',3,'3000章以上','novel_chapter',3,'3000',5),(14,1,'novel',4,'全部','novel_type',1,'-1',1),(15,1,'novel',4,'原创','novel_type',1,'1',2),(16,1,'novel',4,'转载','novel_type',1,'2',3),(17,1,'novel',4,'签约','novel_sign_id',1,'1',4),(18,1,'novel',4,'上架','novel_sell',1,'1',5),(19,1,'novel',4,'买断','novel_copyright',1,'2',6),(20,1,'novel',5,'全部','novel_sell',1,'-1',1),(21,1,'novel',5,'免费','novel_sell',1,'0',2),(22,1,'novel',5,'付费','novel_sell',1,'1',3),(23,1,'novel',7,'更新','novel_uptime',-1,'-1',1),(24,1,'novel',7,'点击','novel_allclick',-1,'',2),(25,1,'novel',7,'推荐','novel_allrec',-1,'',3),(26,1,'novel',7,'收藏','novel_allcoll',-1,'',4),(27,1,'novel',7,'字数','novel_wordnumber',-1,'',5),(28,1,'novel',7,'章节','novel_chapter',-1,'',6),(29,1,'novel',6,'全部','novel_pinyin',5,'-1',1),(30,1,'novel',6,'A','novel_pinyin',5,'a',2),(31,1,'novel',6,'B','novel_pinyin',5,'b',3),(32,1,'novel',6,'C','novel_pinyin',5,'c',4),(33,1,'novel',6,'D','novel_pinyin',5,'d',5),(34,1,'novel',6,'E','novel_pinyin',5,'e',6),(35,1,'novel',6,'F','novel_pinyin',5,'f',7),(36,1,'novel',6,'G','novel_pinyin',5,'g',8),(37,1,'novel',6,'H','novel_pinyin',5,'h',9),(38,1,'novel',6,'I','novel_pinyin',5,'i',10),(39,1,'novel',6,'J','novel_pinyin',5,'j',11),(40,1,'novel',6,'K','novel_pinyin',5,'k',12),(41,1,'novel',6,'L','novel_pinyin',5,'l',13),(42,1,'novel',6,'M','novel_pinyin',5,'m',14),(43,1,'novel',6,'N','novel_pinyin',5,'n',15),(44,1,'novel',6,'O','novel_pinyin',5,'o',16),(45,1,'novel',6,'P','novel_pinyin',5,'p',17),(46,1,'novel',6,'Q','novel_pinyin',5,'q',18),(47,1,'novel',6,'R','novel_pinyin',5,'r',19),(48,1,'novel',6,'S','novel_pinyin',5,'s',20),(49,1,'novel',6,'T','novel_pinyin',5,'t',21),(50,1,'novel',6,'U','novel_pinyin',5,'u',22),(51,1,'novel',6,'V','novel_pinyin',5,'v',23),(52,1,'novel',6,'W','novel_pinyin',5,'w',24),(53,1,'novel',6,'X','novel_pinyin',5,'x',25),(54,1,'novel',6,'Y','novel_pinyin',5,'y',26),(55,1,'novel',6,'Z','novel_pinyin',5,'z',27),(56,1,'novel',6,'0-9','novel_pinyin',7,'0,9',28),(57,1,'app',8,'全部','app_rec',1,'-1',1),(58,1,'app',8,'已推荐','app_rec',1,'1',2),(59,1,'app',8,'未推荐','app_rec',1,'0',3),(60,1,'app',9,'全部','app_lid',1,'-1',1),(61,1,'app',9,'中文','app_lid',8,'9',2),(62,1,'app',9,'英文','app_lid',8,'7',3),(63,1,'app',10,'全部','app_cid',1,'-1',1),(64,1,'app',10,'免费','app_cid',8,'11',2),(65,1,'app',10,'内购','app_cid',8,'5',3),(66,1,'app',10,'破解','app_cid',8,'10',4),(67,1,'app',11,'小于100M','app_size',2,'100',1),(68,1,'app',11,'100-500M','app_size',4,'100,500',2),(69,1,'app',11,'500M-1G','app_size',4,'500,1000',3),(70,1,'app',11,'1-2G','app_size',4,'1000,2000',4),(71,1,'app',11,'2G以上','app_size',3,'2000',5),(72,1,'app',11,'全部','app_size',3,'-1',0),(73,1,'app',12,'全部','app_paid',1,'-1',1),(74,1,'app',12,'安卓','app_paid',8,'4',2),(75,1,'app',12,'苹果','app_paid',8,'8',3),(76,1,'app',12,'塞班','app_paid',8,'6',4),(77,1,'app',13,'默认','app_addtime',1,'-1',1),(78,1,'app',13,'热度','app_read',-1,'',2),(79,1,'app',13,'讨论','app_replay',-1,'',2),(80,1,'app',13,'喜欢','app_ding',-1,'',3),(81,1,'app',13,'讨厌','app_cai',-1,'',4),(82,1,'app',13,'星级','app_start',-1,'',6),(83,1,'app',13,'评分','app_score',-1,'',7);

/*Table structure for table `wm_system_retrieval_type` */

DROP TABLE IF EXISTS `wm_system_retrieval_type`;

CREATE TABLE `wm_system_retrieval_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '分类状态，0为隐藏，1为显示',
  `type_module` varchar(20) NOT NULL COMMENT '分类所属模块',
  `type_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为条件，2为排序',
  `type_name` varchar(20) NOT NULL COMMENT '分类名字',
  `type_par` varchar(20) NOT NULL COMMENT '分类的参数名字',
  `type_order` int(1) NOT NULL DEFAULT '99' COMMENT '分类排序',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 COMMENT='检索分类表';

/*Data for the table `wm_system_retrieval_type` */

insert  into `wm_system_retrieval_type`(`type_id`,`type_status`,`type_module`,`type_type`,`type_name`,`type_par`,`type_order`) values (1,1,'novel',1,'进程','process',1),(2,1,'novel',1,'字数','word',2),(3,1,'novel',1,'章节','chapter',3),(4,1,'novel',1,'版权','copy',4),(5,1,'novel',1,'费用','cost',5),(6,1,'novel',1,'字母','letter',6),(7,1,'novel',2,'排序','order',7),(8,1,'app',1,'推荐','rec',1),(9,1,'app',1,'语言','lang',2),(10,1,'app',1,'资费','cost',3),(11,1,'app',1,'大小','size',4),(12,1,'app',1,'平台','platform',5),(13,1,'app',2,'排序','order',6);

/*Table structure for table `wm_system_sms` */

DROP TABLE IF EXISTS `wm_system_sms`;

CREATE TABLE `wm_system_sms` (
  `sms_id` int(4) NOT NULL AUTO_INCREMENT,
  `sms_status` tinyint(1) DEFAULT '1' COMMENT '状态:1=使用,0=禁用',
  `sms_api_name` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '短信接口',
  `sms_type` varchar(30) COLLATE utf8_unicode_ci NOT NULL COMMENT '模版类型',
  `sms_sign` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '第三方模版签名',
  `sms_tempcode` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '第三方模版代码',
  `sms_params` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '替换参数',
  `sms_time` int(4) NOT NULL DEFAULT '0' COMMENT '创建时间',
  PRIMARY KEY (`sms_id`)
) ENGINE=MyISAM AUTO_INCREMENT=15 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='短信模版';

/*Data for the table `wm_system_sms` */

insert  into `wm_system_sms`(`sms_id`,`sms_status`,`sms_api_name`,`sms_type`,`sms_sign`,`sms_tempcode`,`sms_params`,`sms_time`) values (1,1,'aliyunsms','user_login','阿里云短信测试','SMS_154950909','code:{#123}code{#125}',1647915216),(2,1,'aliyunsms','user_reg','阿里云短信测试','SMS_154950909','code:{#123}code{#125}',1647915222),(3,1,'aliyunsms','user_veremail','阿里云短信测试','SMS_154950909','code:{#123}code{#125}',1647915226),(4,1,'aliyunsms','user_getpsw','阿里云短信测试','SMS_154950909','code:{#123}code{#125}',1647915231),(5,0,'aliyunsms','user_welcome','阿里云短信测试','SMS_154950909','code:{#123}code{#125}',1647915236),(6,1,'tencentsms','user_login','未梦之梦','1345770','1:{#123}code{#125}',1647915216),(7,1,'tencentsms','user_reg','未梦之梦','1345770','1:{#123}code{#125}',1647915222),(8,1,'tencentsms','user_veremail','未梦之梦','1345770','1:{#123}code{#125}',1647915226),(9,1,'tencentsms','user_getpsw','未梦之梦','1345770','1:{#123}code{#125}',1647915231),(10,0,'tencentsms','user_welcome','未梦之梦','1345770','1:{#123}code{#125}',1647915236),(11,1,'tencentsms','warning_code_eroor','未梦之梦','1345770','代码报错ip:{#123}代码报错ip{#125}\r\n代码报错详情:{#123}代码报错详情{#125}\r\n代码报错代码:{#123}代码报错代码{#125}\r\n代码报错时间:{#123}代码报错时间{#125}\r\n代码报错url:{#123}代码报错url{#125}\r\n代码报错sql:{#123}代码报错sql{#125}',1648901665),(12,1,'tencentsms','warning_admin_login','未梦之梦','1345770','后台登录ip:{#123}后台登录ip{#125}\r\n后台登录账号:{#123}后台登录账号{#125}\r\n后台登录时间:{#123}后台登录时间{#125}\r\n后台登录状态:{#123}后台登录状态{#125}',1648901665),(13,1,'aliyunsms','warning_code_eroor','阿里云短信测试','SMS_154950909','1:{#123}代码报错ip{#125}\r\n2:{#123}代码报错详情{#125}\r\n3:{#123}代码报错代码{#125}\r\n4:{#123}代码报错时间{#125}\r\n5:{#123}代码报错url{#125}\r\n6:{#123}代码报错sql{#125}',1648901665),(14,1,'aliyunsms','warning_admin_login','阿里云短信测试','SMS_154950909','1:{#123}后台登录ip{#125}\r\n2:{#123}后台登录账号{#125}\r\n3:{#123}后台登录时间{#125}\r\n4:{#123}后台登录状态{#125}',1648901665);

/*Table structure for table `wm_system_sms_log` */

DROP TABLE IF EXISTS `wm_system_sms_log`;

CREATE TABLE `wm_system_sms_log` (
  `log_id` int(4) NOT NULL AUTO_INCREMENT,
  `log_status` tinyint(1) DEFAULT '0' COMMENT '使用状态:0=未使用,1已使用,2=已过期',
  `log_send` tinyint(1) DEFAULT '1' COMMENT '发送状态:1=成功,0=失败',
  `log_type` varchar(20) COLLATE utf8_unicode_ci NOT NULL COMMENT '短信类型',
  `log_remark` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '失败消息',
  `log_sender` varchar(50) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '发信人',
  `log_receive` varchar(255) COLLATE utf8_unicode_ci NOT NULL COMMENT '收信人',
  `log_content` varchar(200) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '消息内容',
  `log_time` int(4) DEFAULT NULL COMMENT '发送时间',
  `log_exptime` int(4) DEFAULT NULL COMMENT '过期时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci COMMENT='信息发送日志表';

/*Data for the table `wm_system_sms_log` */

/*Table structure for table `wm_system_tags` */

DROP TABLE IF EXISTS `wm_system_tags`;

CREATE TABLE `wm_system_tags` (
  `tags_id` int(11) NOT NULL AUTO_INCREMENT,
  `tags_type_id` int(4) NOT NULL DEFAULT '1' COMMENT '所属分类',
  `tags_module` varchar(20) NOT NULL COMMENT '标签所属的模块',
  `tags_author_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐展示',
  `tags_name` varchar(20) NOT NULL COMMENT '标签名字',
  `tags_pinyin` varchar(50) DEFAULT NULL COMMENT '标签拼音',
  `tags_data` int(4) NOT NULL DEFAULT '1' COMMENT '标签的数据量',
  `tags_search` int(4) DEFAULT '0' COMMENT '标签的搜索次数',
  `tags_time` int(4) NOT NULL DEFAULT '0' COMMENT '标签创建的时间',
  PRIMARY KEY (`tags_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='系统标签表';

/*Data for the table `wm_system_tags` */

/*Table structure for table `wm_system_tags_type` */

DROP TABLE IF EXISTS `wm_system_tags_type`;

CREATE TABLE `wm_system_tags_type` (
  `type_id` int(11) NOT NULL AUTO_INCREMENT COMMENT 'id',
  `type_module` varchar(20) NOT NULL COMMENT '分类模块',
  `type_name` varchar(20) NOT NULL COMMENT '分类名字',
  `type_desc` varchar(100) DEFAULT NULL COMMENT '分类描述',
  `type_order` int(11) NOT NULL DEFAULT '9' COMMENT '分类排序,越小越靠前',
  PRIMARY KEY (`type_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 COMMENT='标签分类';

/*Data for the table `wm_system_tags_type` */

insert  into `wm_system_tags_type`(`type_id`,`type_module`,`type_name`,`type_desc`,`type_order`) values (1,'novel','情节','故事情节',1),(2,'novel','题材','情节题材',2),(3,'novel','人物','主要人物',3),(4,'novel','风格','内容风格',4);

/*Table structure for table `wm_system_templates` */

DROP TABLE IF EXISTS `wm_system_templates`;

CREATE TABLE `wm_system_templates` (
  `temp_id` int(4) NOT NULL AUTO_INCREMENT,
  `temp_module` varchar(20) DEFAULT NULL COMMENT '模版所属的模块',
  `temp_type` varchar(40) DEFAULT NULL COMMENT '模版的类型',
  `temp_name` varchar(20) DEFAULT NULL COMMENT '模版名字',
  `temp_temp4` varchar(100) NOT NULL COMMENT '电脑版的模版',
  `temp_temp3` varchar(100) NOT NULL COMMENT '触屏',
  `temp_temp2` varchar(100) NOT NULL COMMENT '3g',
  `temp_temp1` varchar(100) NOT NULL COMMENT 'wap',
  `temp_address` tinyint(1) DEFAULT '0' COMMENT '模版存在的路径0为当前，1为根目录计算。',
  PRIMARY KEY (`temp_id`)
) ENGINE=MyISAM AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 COMMENT='预设模块分类和diy、专题的默认模版';

/*Data for the table `wm_system_templates` */

insert  into `wm_system_templates`(`temp_id`,`temp_module`,`temp_type`,`temp_name`,`temp_temp4`,`temp_temp3`,`temp_temp2`,`temp_temp1`,`temp_address`) values (1,'article','list','test','test.html','test.html','test.html','test.html',0),(2,'article','tindex','eee','eee','eee','eee','eee',0),(3,'article','content','dddd','dddd','dddd','dddd','dddd',0);

/*Table structure for table `wm_templates_templates` */

DROP TABLE IF EXISTS `wm_templates_templates`;

CREATE TABLE `wm_templates_templates` (
  `templates_id` int(4) NOT NULL AUTO_INCREMENT,
  `templates_path` varchar(20) NOT NULL COMMENT '模版文件夹',
  `templates_name` varchar(20) NOT NULL COMMENT '模版名字',
  `templates_appid` varchar(40) NOT NULL COMMENT '模版appid',
  PRIMARY KEY (`templates_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='安装使用模板表';

/*Data for the table `wm_templates_templates` */

/*Table structure for table `wm_upload` */

DROP TABLE IF EXISTS `wm_upload`;

CREATE TABLE `wm_upload` (
  `upload_id` int(4) NOT NULL AUTO_INCREMENT,
  `upload_module` varchar(20) NOT NULL COMMENT '所属 模块',
  `upload_type` varchar(20) DEFAULT NULL COMMENT '模块处理的类型',
  `upload_ext` varchar(10) DEFAULT NULL COMMENT '附件类型',
  `upload_mid` int(4) NOT NULL DEFAULT '0' COMMENT '所属主要内容的id，如评论的主题',
  `upload_cid` int(4) NOT NULL DEFAULT '0' COMMENT '所属内容的id',
  `user_id` int(4) DEFAULT '0' COMMENT '上传用户的id',
  `upload_alt` varchar(100) DEFAULT NULL COMMENT '描述',
  `upload_simg` varchar(200) DEFAULT NULL COMMENT '缩略图路径',
  `upload_img` varchar(200) DEFAULT NULL COMMENT '文件路径',
  `upload_size` int(4) NOT NULL DEFAULT '0' COMMENT '文件大小',
  `upload_width` int(1) DEFAULT '0' COMMENT '图片宽度',
  `upload_height` int(1) DEFAULT '0' COMMENT '图片高度',
  `upload_time` int(4) NOT NULL DEFAULT '0' COMMENT '上传时间',
  PRIMARY KEY (`upload_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='上传记录表';

/*Data for the table `wm_upload` */

/*Table structure for table `wm_user_apilogin` */

DROP TABLE IF EXISTS `wm_user_apilogin`;

CREATE TABLE `wm_user_apilogin` (
  `api_id` int(4) NOT NULL AUTO_INCREMENT,
  `api_uid` int(4) NOT NULL DEFAULT '0',
  `api_type` varchar(30) NOT NULL COMMENT '第三方登录类型',
  `api_openid` varchar(120) NOT NULL COMMENT '第三方登录唯一ID',
  `api_unionid` varchar(120) DEFAULT NULL COMMENT '第三方登录联合ID',
  PRIMARY KEY (`api_id`),
  KEY `type_index` (`api_type`),
  KEY `openid_index` (`api_openid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='第三方登录记录表';

/*Data for the table `wm_user_apilogin` */

/*Table structure for table `wm_user_card` */

DROP TABLE IF EXISTS `wm_user_card`;

CREATE TABLE `wm_user_card` (
  `card_id` int(4) NOT NULL AUTO_INCREMENT,
  `card_type` tinyint(1) DEFAULT '1' COMMENT '卡号类型，1为充值卡',
  `card_use` tinyint(1) DEFAULT '0' COMMENT '是否已经使用',
  `card_channel` varchar(20) NOT NULL COMMENT '发布渠道',
  `card_key` varchar(50) DEFAULT NULL COMMENT '卡号',
  `card_money` decimal(10,2) DEFAULT '0.00' COMMENT '卡号金额',
  `card_give` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '使用充值卡可以获赠多少',
  `card_addtime` int(4) DEFAULT '0' COMMENT '添加时间',
  `card_user_id` int(4) NOT NULL DEFAULT '0' COMMENT '领取的用户',
  `card_get_time` int(4) NOT NULL DEFAULT '0' COMMENT '领取的时间',
  PRIMARY KEY (`card_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户充值卡号、邀请码等表';

/*Data for the table `wm_user_card` */

/*Table structure for table `wm_user_card_log` */

DROP TABLE IF EXISTS `wm_user_card_log`;

CREATE TABLE `wm_user_card_log` (
  `log_id` int(4) NOT NULL AUTO_INCREMENT,
  `log_card_id` int(4) NOT NULL COMMENT '卡号id',
  `log_user_id` int(4) NOT NULL COMMENT '使用的id',
  `log_use_time` int(4) NOT NULL DEFAULT '0' COMMENT '使用的时间',
  `log_touser_id` int(4) NOT NULL DEFAULT '0' COMMENT '对谁使用的',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='卡号使用记录表';

/*Data for the table `wm_user_card_log` */

/*Table structure for table `wm_user_coll` */

DROP TABLE IF EXISTS `wm_user_coll`;

CREATE TABLE `wm_user_coll` (
  `coll_id` int(4) NOT NULL AUTO_INCREMENT,
  `coll_module` varchar(20) NOT NULL COMMENT '操作的模块',
  `coll_type` varchar(20) NOT NULL COMMENT '操作类型，coll为收藏，rec为推荐，shelf为书架,sub为自动订阅',
  `user_id` int(4) NOT NULL DEFAULT '0' COMMENT '用户id',
  `coll_cid` int(4) NOT NULL DEFAULT '0' COMMENT '操作的内容id',
  `coll_time` int(4) NOT NULL DEFAULT '0' COMMENT '操作的时间',
  PRIMARY KEY (`coll_id`),
  KEY `uid` (`user_id`,`coll_cid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户收藏、书架、订阅等表';

/*Data for the table `wm_user_coll` */

/*Table structure for table `wm_user_finance` */

DROP TABLE IF EXISTS `wm_user_finance`;

CREATE TABLE `wm_user_finance` (
  `finance_id` int(11) NOT NULL AUTO_INCREMENT,
  `finance_user_id` int(11) DEFAULT NULL COMMENT '用户id',
  `finance_realname` varchar(20) DEFAULT NULL COMMENT '真实姓名',
  `finance_cardid` varchar(30) DEFAULT NULL COMMENT '身份证号码',
  `finance_address` varchar(50) DEFAULT NULL COMMENT '家庭住址',
  `finance_zipcode` varchar(10) DEFAULT NULL COMMENT '邮编',
  `finance_bank` varchar(20) DEFAULT NULL COMMENT '开户行',
  `finance_bankaddress` varchar(50) DEFAULT NULL COMMENT '开户行地址',
  `finance_bankcard` varchar(30) DEFAULT NULL COMMENT '开户行卡号',
  `finance_bankmaster` varchar(20) DEFAULT NULL COMMENT '持卡人姓名',
  `finance_alipay` varchar(50) DEFAULT NULL COMMENT '支付宝账号',
  PRIMARY KEY (`finance_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户财务信息表';

/*Data for the table `wm_user_finance` */

/*Table structure for table `wm_user_finance_log` */

DROP TABLE IF EXISTS `wm_user_finance_log`;

CREATE TABLE `wm_user_finance_log` (
  `log_id` int(4) NOT NULL AUTO_INCREMENT,
  `log_status` tinyint(1) DEFAULT '1' COMMENT '1为收入，2为消费',
  `log_module` varchar(20) DEFAULT NULL COMMENT '模块',
  `log_type` varchar(20) DEFAULT NULL COMMENT '类型',
  `log_user_id` int(4) NOT NULL DEFAULT '0' COMMENT '消费或者收入的用户id',
  `log_tuser_id` int(4) NOT NULL DEFAULT '0' COMMENT '对谁使用或者谁赠送的用户id',
  `log_cid` varchar(35) NOT NULL DEFAULT '0' COMMENT '购买的内容id或者来源id',
  `log_gold1_before` decimal(10,3) DEFAULT '0.000' COMMENT '交易前的金币1的数量',
  `log_gold1` decimal(10,3) DEFAULT '0.000' COMMENT '金币1的数量',
  `log_gold1_after` decimal(10,3) DEFAULT '0.000' COMMENT '交易后的金币1的数量',
  `log_gold2_before` decimal(10,3) DEFAULT '0.000' COMMENT '交易前的金币2的数量',
  `log_gold2` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '金币2的数量',
  `log_gold2_after` decimal(10,3) DEFAULT '0.000' COMMENT '交易后的金币2的数量',
  `log_remark` varchar(100) DEFAULT NULL COMMENT '备注信息',
  `log_time` int(4) NOT NULL DEFAULT '0' COMMENT '购买的时间',
  PRIMARY KEY (`log_id`),
  KEY `status_index` (`log_status`),
  KEY `module_index` (`log_module`),
  KEY `type_index` (`log_type`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户购买使用赠送等财务记录日志';

/*Data for the table `wm_user_finance_log` */

/*Table structure for table `wm_user_head` */

DROP TABLE IF EXISTS `wm_user_head`;

CREATE TABLE `wm_user_head` (
  `head_id` int(11) NOT NULL AUTO_INCREMENT,
  `head_src` varchar(200) NOT NULL COMMENT '头像地址',
  `head_order` int(4) NOT NULL DEFAULT '50' COMMENT '头像排序',
  PRIMARY KEY (`head_id`)
) ENGINE=MyISAM AUTO_INCREMENT=29 DEFAULT CHARSET=utf8 COMMENT='预设头像表';

/*Data for the table `wm_user_head` */

insert  into `wm_user_head`(`head_id`,`head_src`,`head_order`) values (1,'/upload/userhead/20141201125121.jpg',20),(2,'/upload/userhead/20141201125122.jpg',20),(5,'/upload/userhead/20141201125124.jpg',20),(6,'/upload/userhead/20141201125125.jpg',20),(7,'/upload/userhead/20141201125126.jpg',20),(8,'/upload/userhead/20141201125128.jpg',20),(9,'/upload/userhead/20141201125129.png',20),(10,'/upload/userhead/20141201125131.jpg',20),(11,'/upload/userhead/20141201125133.jpg',20),(12,'/upload/userhead/20141201125134.jpg',20),(13,'/upload/userhead/20141201125410.jpg',20),(14,'/upload/userhead/20141201125412.jpg',20),(15,'/upload/userhead/20141201125414.jpg',20),(16,'/upload/userhead/20141201125416.png',20),(17,'/upload/userhead/20141201125422.jpg',20),(18,'/upload/userhead/20141201125419.jpg',20),(19,'/upload/userhead/20141201125424.jpg',20),(20,'/upload/userhead/20141201125426.jpg',20),(21,'/upload/userhead/20141201125432.jpg',20),(22,'/upload/userhead/20141201125430.jpg',20),(23,'/upload/userhead/20141201125431.png',20),(24,'/upload/userhead/20141201125428.png',20),(25,'/upload/userhead/20141201125513.png',20),(26,'/upload/userhead/20141201125512.png',20),(27,'/upload/userhead/20141201125511.png',20),(28,'/upload/userhead/20141201125510.jpg',20);

/*Table structure for table `wm_user_level` */

DROP TABLE IF EXISTS `wm_user_level`;

CREATE TABLE `wm_user_level` (
  `level_id` int(4) NOT NULL AUTO_INCREMENT,
  `level_start` int(4) NOT NULL COMMENT '等级开始经验',
  `level_end` int(4) NOT NULL COMMENT '等级结束经验',
  `level_name` varchar(20) NOT NULL COMMENT '等级名字',
  `level_order` int(1) DEFAULT NULL COMMENT '等级排序',
  `level_coll` int(4) NOT NULL DEFAULT '0' COMMENT '等级收藏量',
  `level_shelf` int(4) NOT NULL DEFAULT '0' COMMENT '等级总书架量',
  `level_rec` int(4) NOT NULL DEFAULT '0' COMMENT '每日登录赠送推荐量',
  `level_month` int(4) NOT NULL DEFAULT '0' COMMENT '每日登录赠送月票',
  PRIMARY KEY (`level_id`)
) ENGINE=MyISAM AUTO_INCREMENT=33 DEFAULT CHARSET=utf8 COMMENT='经验等级';

/*Data for the table `wm_user_level` */

insert  into `wm_user_level`(`level_id`,`level_start`,`level_end`,`level_name`,`level_order`,`level_coll`,`level_shelf`,`level_rec`,`level_month`) values (23,0,100,'斗者',1,5,5,0,0),(24,100,300,'斗徒',2,6,6,0,0),(25,300,600,'斗师',3,7,7,0,0),(26,600,1000,'斗灵',4,8,8,0,0),(27,1000,2000,'斗王',5,9,9,0,0),(28,2000,5000,'斗皇',6,10,10,0,0),(29,5000,10000,'斗宗',7,15,15,0,0),(30,10000,20000,'斗尊',8,20,20,0,0),(31,20000,50000,'斗圣',9,25,25,0,0),(32,50000,100000,'斗帝',10,30,30,0,0);

/*Table structure for table `wm_user_login` */

DROP TABLE IF EXISTS `wm_user_login`;

CREATE TABLE `wm_user_login` (
  `login_id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL DEFAULT '0' COMMENT '用户id',
  `login_time` int(4) NOT NULL DEFAULT '0' COMMENT '登录时间',
  `login_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为账号',
  `login_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为登录失败，1为登录成功，2为密码错误',
  `login_remark` varchar(100) CHARACTER SET gbk DEFAULT '登录成功' COMMENT '备注详情',
  `login_ip` varchar(40) DEFAULT NULL COMMENT '登录IP',
  `login_ua` varchar(1000) DEFAULT NULL COMMENT '登录浏览器ua',
  `login_browser` varchar(250) DEFAULT NULL COMMENT '浏览器',
  PRIMARY KEY (`login_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户登录记录表';

/*Data for the table `wm_user_login` */

/*Table structure for table `wm_user_msg` */

DROP TABLE IF EXISTS `wm_user_msg`;

CREATE TABLE `wm_user_msg` (
  `msg_id` int(4) NOT NULL AUTO_INCREMENT,
  `msg_fuid` int(4) NOT NULL DEFAULT '0' COMMENT '发送用户id',
  `msg_tuid` int(4) NOT NULL COMMENT '接受用户id',
  `msg_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为未阅读，1为已阅读',
  `msg_content` varchar(1000) DEFAULT NULL COMMENT '消息内容',
  `msg_time` int(4) NOT NULL DEFAULT '0' COMMENT '发送时间',
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户消息记录表';

/*Data for the table `wm_user_msg` */

/*Table structure for table `wm_user_punish` */

DROP TABLE IF EXISTS `wm_user_punish`;

CREATE TABLE `wm_user_punish` (
  `punish_id` int(4) NOT NULL AUTO_INCREMENT,
  `punish_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '0为失效,1为正常',
  `punish_uid` int(4) NOT NULL DEFAULT '0' COMMENT '用户id',
  `punish_type` varchar(20) NOT NULL COMMENT 'login禁止登陆,talk禁言',
  `punish_remark` varchar(50) DEFAULT NULL COMMENT '备注',
  `punish_starttime` int(4) NOT NULL DEFAULT '0' COMMENT '处罚开始时间',
  `punish_endtime` int(4) NOT NULL DEFAULT '0' COMMENT '处罚结束时间',
  `punish_createtime` int(4) NOT NULL DEFAULT '0' COMMENT '处罚创建时间',
  PRIMARY KEY (`punish_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户处罚记录表';

/*Data for the table `wm_user_punish` */

/*Table structure for table `wm_user_read` */

DROP TABLE IF EXISTS `wm_user_read`;

CREATE TABLE `wm_user_read` (
  `read_id` int(4) NOT NULL AUTO_INCREMENT,
  `read_module` varchar(20) NOT NULL DEFAULT 'novel' COMMENT '阅读模块',
  `read_cid` int(4) NOT NULL DEFAULT '0' COMMENT '内容id',
  `read_nid` int(4) DEFAULT '0' COMMENT '内容的父id',
  `read_title` varchar(50) DEFAULT NULL COMMENT '标题',
  `read_uid` int(4) NOT NULL DEFAULT '0' COMMENT '用户id',
  `read_time` int(11) NOT NULL DEFAULT '0' COMMENT '首次阅读时间',
  `read_lasttime` int(11) NOT NULL DEFAULT '0' COMMENT '上次阅读时间',
  PRIMARY KEY (`read_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='阅读记录表，只存储最新的阅读记录';

/*Data for the table `wm_user_read` */

/*Table structure for table `wm_user_read_log` */

DROP TABLE IF EXISTS `wm_user_read_log`;

CREATE TABLE `wm_user_read_log` (
  `read_id` int(4) NOT NULL AUTO_INCREMENT,
  `read_module` varchar(20) NOT NULL DEFAULT 'novel' COMMENT '阅读模块',
  `read_cid` int(4) NOT NULL DEFAULT '0' COMMENT '内容id',
  `read_nid` int(4) DEFAULT '0' COMMENT '内容的父id',
  `read_title` varchar(50) DEFAULT NULL COMMENT '标题',
  `read_uid` int(4) NOT NULL DEFAULT '0' COMMENT '用户id',
  `read_time` int(4) NOT NULL DEFAULT '0' COMMENT '阅读时间',
  PRIMARY KEY (`read_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='阅读记录表，所有阅读记录';

/*Data for the table `wm_user_read_log` */

/*Table structure for table `wm_user_sign` */

DROP TABLE IF EXISTS `wm_user_sign`;

CREATE TABLE `wm_user_sign` (
  `sign_id` int(4) NOT NULL AUTO_INCREMENT,
  `user_id` int(4) NOT NULL DEFAULT '0' COMMENT '签到用户',
  `sign_sum` int(4) NOT NULL DEFAULT '0' COMMENT '总共签到天数',
  `sign_con` int(4) NOT NULL DEFAULT '0' COMMENT '连续签到天数',
  `sign_prerank` int(4) NOT NULL DEFAULT '0' COMMENT '上次签到排名',
  `sign_pretime` int(4) NOT NULL DEFAULT '0' COMMENT '上次签到时间',
  `sign_rank` int(4) NOT NULL DEFAULT '0' COMMENT '本次签到排名',
  `sign_time` int(4) NOT NULL DEFAULT '0' COMMENT '本次签到时间',
  PRIMARY KEY (`sign_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户签到表';

/*Data for the table `wm_user_sign` */

/*Table structure for table `wm_user_sign_log` */

DROP TABLE IF EXISTS `wm_user_sign_log`;

CREATE TABLE `wm_user_sign_log` (
  `log_id` int(11) NOT NULL AUTO_INCREMENT,
  `log_user_id` int(4) NOT NULL COMMENT '签到用户',
  `log_gold1` int(4) NOT NULL DEFAULT '0' COMMENT '赠送金币1',
  `log_gold2` int(4) NOT NULL DEFAULT '0' COMMENT '赠送金币2',
  `log_exp` int(4) NOT NULL DEFAULT '0' COMMENT '赠送经验',
  `log_rec` int(4) NOT NULL DEFAULT '0' COMMENT '赠送推荐票',
  `log_month` int(4) NOT NULL DEFAULT '0' COMMENT '赠送月票',
  `log_rank` int(4) NOT NULL COMMENT '签到排名',
  `log_date` date NOT NULL COMMENT '签到日期',
  `log_time` int(4) NOT NULL COMMENT '签到时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='签到日志表';

/*Data for the table `wm_user_sign_log` */

insert  into `wm_user_sign_log`(`log_id`,`log_user_id`,`log_gold1`,`log_gold2`,`log_exp`,`log_rec`,`log_month`,`log_rank`,`log_date`,`log_time`) values (1,1,0,0,1,0,0,1,'2021-01-30',1611983872);

/*Table structure for table `wm_user_ticket` */

DROP TABLE IF EXISTS `wm_user_ticket`;

CREATE TABLE `wm_user_ticket` (
  `ticket_id` int(4) NOT NULL AUTO_INCREMENT,
  `ticket_user_id` int(4) NOT NULL COMMENT '用户id',
  `ticket_module` varchar(20) CHARACTER SET latin1 NOT NULL DEFAULT 'novel' COMMENT '所属模块',
  `ticket_rec` int(4) NOT NULL DEFAULT '0' COMMENT '用户推荐票数量',
  `ticket_month` int(4) NOT NULL DEFAULT '0' COMMENT '用户月票数量',
  PRIMARY KEY (`ticket_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户票类集合表';

/*Data for the table `wm_user_ticket` */

/*Table structure for table `wm_user_ticket_log` */

DROP TABLE IF EXISTS `wm_user_ticket_log`;

CREATE TABLE `wm_user_ticket_log` (
  `log_id` int(4) NOT NULL AUTO_INCREMENT,
  `log_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为获得，2为使用',
  `log_module` varchar(20) DEFAULT NULL COMMENT '模块，all为全部模块',
  `log_cid` int(4) DEFAULT '0' COMMENT '内容id，',
  `log_user_id` int(4) NOT NULL DEFAULT '0' COMMENT '用户id',
  `log_rec` int(1) NOT NULL DEFAULT '0' COMMENT '推荐票数量',
  `log_month` int(1) NOT NULL DEFAULT '0' COMMENT '月票数量',
  `log_remark` varchar(500) DEFAULT NULL COMMENT '来源/使用说明',
  `log_time` int(4) NOT NULL COMMENT '来源/使用时间',
  PRIMARY KEY (`log_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='月票、推荐票等来源日志记录';

/*Data for the table `wm_user_ticket_log` */

/*Table structure for table `wm_user_user` */

DROP TABLE IF EXISTS `wm_user_user`;

CREATE TABLE `wm_user_user` (
  `user_id` int(4) NOT NULL AUTO_INCREMENT,
  `user_type` varchar(20) NOT NULL DEFAULT 'default' COMMENT '账号注册来源',
  `user_name` varchar(50) NOT NULL COMMENT '账号/第三方ID',
  `user_salt` varchar(50) DEFAULT '' COMMENT '密码盐',
  `user_psw` varchar(50) NOT NULL COMMENT '密码',
  `user_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为正常,0为审核中',
  `user_display` tinyint(1) DEFAULT '1' COMMENT '0为永久封禁，1为正常，2为定时封禁',
  `user_nickname` varchar(40) NOT NULL COMMENT '昵称',
  `user_email` varchar(50) DEFAULT NULL COMMENT '邮箱',
  `user_emailtrue` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为验证，0为未验证',
  `user_qq` varchar(15) DEFAULT NULL COMMENT '用户QQ号',
  `user_tel` varchar(18) DEFAULT NULL COMMENT '用户的手机号',
  `user_teltrue` tinyint(1) NOT NULL DEFAULT '0' COMMENT '1为验证,0为未验证',
  `user_sex` int(1) NOT NULL DEFAULT '1' COMMENT '性别',
  `user_birthday` date NOT NULL DEFAULT '1991-10-24' COMMENT '用户的出生年月日',
  `user_head` varchar(200) NOT NULL COMMENT '头像',
  `user_sign` varchar(100) NOT NULL COMMENT '签名',
  `user_money` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户余额',
  `user_money_freeze` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '账户被冻结的余额',
  `user_gold1` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '金币1',
  `user_gold2` decimal(10,3) NOT NULL DEFAULT '0.000' COMMENT '金币2',
  `user_ischarge` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否首冲过了',
  `user_exp` int(4) NOT NULL DEFAULT '0' COMMENT '经验',
  `user_browse` int(4) NOT NULL DEFAULT '0' COMMENT '空间浏览量',
  `user_topic` int(4) NOT NULL DEFAULT '0' COMMENT '主题量',
  `user_retopic` int(4) NOT NULL DEFAULT '0' COMMENT '回帖数',
  `user_replay` int(4) NOT NULL DEFAULT '0' COMMENT '评论数',
  `user_logintime` int(4) NOT NULL DEFAULT '0' COMMENT '上次登录时间',
  `user_regtime` int(4) NOT NULL DEFAULT '0' COMMENT '注册时间',
  `user_regip` varchar(15) NOT NULL DEFAULT '0.0.0.0' COMMENT '注册IP',
  `user_displaytime` int(4) NOT NULL DEFAULT '0' COMMENT '如果是时间段，那么就是封禁的时间段',
  PRIMARY KEY (`user_id`)
) ENGINE=MyISAM AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COMMENT='用户表';

/*Data for the table `wm_user_user` */

insert  into `wm_user_user`(`user_id`,`user_type`,`user_name`,`user_salt`,`user_psw`,`user_status`,`user_display`,`user_nickname`,`user_email`,`user_emailtrue`,`user_qq`,`user_tel`,`user_teltrue`,`user_sex`,`user_birthday`,`user_head`,`user_sign`,`user_money`,`user_money_freeze`,`user_gold1`,`user_gold2`,`user_ischarge`,`user_exp`,`user_browse`,`user_topic`,`user_retopic`,`user_replay`,`user_logintime`,`user_regtime`,`user_regip`,`user_displaytime`) values (1,'default','weimeng','','280a2b0b4a054aa53596a1b7106b4060f1f91aad',1,1,'我是大笨蛋','1747699213@qq.com',0,'1747699213','15123931801',1,1,'1991-10-24','/upload/userhead/20141201125513.png','你想要的不一定来，但是未来一定会来！','0.00','0.00','18.000','1115.000',1,554,122,10,3,33,1649479110,1452754424,'0.0.0.0',1038770);

/*Table structure for table `wm_user_vist` */

DROP TABLE IF EXISTS `wm_user_vist`;

CREATE TABLE `wm_user_vist` (
  `vist_id` int(4) NOT NULL AUTO_INCREMENT,
  `vist_fuid` int(4) NOT NULL DEFAULT '0' COMMENT '访客id',
  `vist_uid` int(4) NOT NULL DEFAULT '0' COMMENT '主人id',
  `vist_time` int(4) NOT NULL DEFAULT '0' COMMENT '浏览时间',
  PRIMARY KEY (`vist_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户参观记录表';

/*Data for the table `wm_user_vist` */

/*Table structure for table `wm_weixin_account` */

DROP TABLE IF EXISTS `wm_weixin_account`;

CREATE TABLE `wm_weixin_account` (
  `account_id` int(4) NOT NULL AUTO_INCREMENT,
  `account_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '布尔值，是否使用',
  `account_name` varchar(50) NOT NULL COMMENT '公众号名字',
  `account_account` varchar(50) NOT NULL COMMENT '公众号账号',
  `account_gid` varchar(32) NOT NULL COMMENT '公众号原始id',
  `account_type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1订阅号，2服务号',
  `account_auth` tinyint(1) NOT NULL DEFAULT '0' COMMENT '布尔值，是否认证',
  `account_appid` varchar(50) NOT NULL COMMENT '公众号appid',
  `account_secret` varchar(100) NOT NULL COMMENT '公众号secret',
  `account_access` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否接入',
  `account_main` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否是主公众号',
  `account_follow` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否需要关注后访问',
  `account_token` varchar(100) NOT NULL COMMENT '公众号的token',
  `account_aeskey` varchar(100) NOT NULL COMMENT '公众号的消息加密key',
  `account_welcome` varchar(200) DEFAULT NULL COMMENT '关注公众号的欢迎信息',
  `account_welcome_temp` varchar(500) DEFAULT NULL COMMENT '关注公众号的欢迎信息模版',
  `account_default` varchar(200) DEFAULT NULL COMMENT '没有匹配到消息的时候回复',
  `account_default_temp` varchar(500) DEFAULT NULL COMMENT '没有匹配到消息的时候回复模版',
  `account_time` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`account_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信公众号列表';

/*Data for the table `wm_weixin_account` */

/*Table structure for table `wm_weixin_autoreply` */

DROP TABLE IF EXISTS `wm_weixin_autoreply`;

CREATE TABLE `wm_weixin_autoreply` (
  `autoreply_id` int(11) NOT NULL AUTO_INCREMENT,
  `autoreply_status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '使用状态，布尔值',
  `autoreply_default` tinyint(1) NOT NULL DEFAULT '0' COMMENT '0为不设置，1为关注回复，2为默认回复',
  `autoreply_account_id` int(4) NOT NULL DEFAULT '0' COMMENT '使用的公众号',
  `autoreply_name` varchar(50) NOT NULL COMMENT '自动回复名字',
  `autoreply_key` varchar(50) NOT NULL COMMENT '自动回复接受的关键字',
  `autoreply_match` tinyint(1) NOT NULL DEFAULT '1' COMMENT '1为完全匹配，2为全文模糊匹配，3为开始匹配，4为最后匹配',
  `autoreply_content` varchar(500) NOT NULL COMMENT '回复内容',
  `autoreply_temp` text NOT NULL COMMENT '回复内容的模版',
  `autoreply_type` varchar(20) NOT NULL DEFAULT 'text' COMMENT 'text为文字，image为图片',
  `autoreply_media_id` varchar(100) DEFAULT NULL COMMENT '素材id',
  `autoreply_addtime` int(11) NOT NULL COMMENT '添加时间',
  PRIMARY KEY (`autoreply_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信公众号自动回复表';

/*Data for the table `wm_weixin_autoreply` */

/*Table structure for table `wm_weixin_fans` */

DROP TABLE IF EXISTS `wm_weixin_fans`;

CREATE TABLE `wm_weixin_fans` (
  `fans_id` int(4) NOT NULL AUTO_INCREMENT,
  `fans_account_id` int(4) NOT NULL COMMENT '所属公众号',
  `fans_openid` varchar(100) NOT NULL COMMENT '用户的标识，对当前公众号唯一',
  `fans_unionid` varchar(100) DEFAULT NULL COMMENT '只有在用户将公众号绑定到微信开放平台帐号后才会有这个字段',
  `fans_subscribe` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户是否订阅该公众号标识，值为0时，代表此用户没有关注该公众号，拉取不到其余信息。',
  `fans_nickname` varchar(50) NOT NULL COMMENT '用户的昵称',
  `fans_headimgurl` varchar(255) NOT NULL COMMENT '用户的头像',
  `fans_sex` tinyint(1) NOT NULL DEFAULT '1' COMMENT '用户的性别，值为1时是男性，值为2时是女性，值为0时是未知',
  `fans_country` varchar(20) NOT NULL COMMENT '	用户所在国家',
  `fans_province` varchar(10) NOT NULL COMMENT '用户所在省份',
  `fans_city` varchar(30) NOT NULL COMMENT '用户所在城市',
  `fans_remark` varchar(200) DEFAULT NULL COMMENT '公众号运营者对粉丝的备注，公众号运营者可在微信公众平台用户管理界面对粉丝添加备注',
  `fans_subscribe_time` int(4) NOT NULL DEFAULT '0' COMMENT '用户关注时间，为时间戳。如果用户曾多次关注，则取最后关注时间',
  `fans_unsubtime` int(4) NOT NULL DEFAULT '0' COMMENT '用户取消关注时间',
  `fans_json` varchar(500) NOT NULL COMMENT '用户数据json',
  `fans_time` int(4) NOT NULL DEFAULT '0' COMMENT '数据入库时间',
  PRIMARY KEY (`fans_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信粉丝信息表';

/*Data for the table `wm_weixin_fans` */

/*Table structure for table `wm_weixin_media` */

DROP TABLE IF EXISTS `wm_weixin_media`;

CREATE TABLE `wm_weixin_media` (
  `media_id` int(4) NOT NULL AUTO_INCREMENT,
  `media_account_id` int(4) NOT NULL DEFAULT '0' COMMENT '公众号id',
  `media_filename` varchar(255) NOT NULL COMMENT '素材名字',
  `media_filepath` varchar(500) NOT NULL COMMENT '素材路径',
  `media_media_id` varchar(255) NOT NULL COMMENT '微信素材id',
  `media_width` int(1) NOT NULL DEFAULT '0' COMMENT '素材宽',
  `media_height` int(1) NOT NULL DEFAULT '0' COMMENT '素材高',
  `media_type` varchar(20) NOT NULL DEFAULT 'image' COMMENT '素材类型，图片image、语音voice、视频video和缩略图thumb',
  `media_islong` tinyint(1) NOT NULL DEFAULT '1' COMMENT '是否是永久素材，布尔值',
  `media_time` int(4) NOT NULL DEFAULT '0' COMMENT '上传时间',
  PRIMARY KEY (`media_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信素材表';

/*Data for the table `wm_weixin_media` */

/*Table structure for table `wm_weixin_menu` */

DROP TABLE IF EXISTS `wm_weixin_menu`;

CREATE TABLE `wm_weixin_menu` (
  `menu_id` int(4) NOT NULL AUTO_INCREMENT,
  `menu_status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '布尔值，使用状态',
  `menu_account_id` int(4) NOT NULL COMMENT '所属公众号id',
  `menu_name` varchar(20) NOT NULL COMMENT '菜单备注名',
  `menu_data` text NOT NULL COMMENT '菜单json数据',
  `menu_addtime` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间',
  `menu_updatetime` int(4) NOT NULL DEFAULT '0' COMMENT '最后更新时间',
  `menu_pushtime` int(4) NOT NULL DEFAULT '0' COMMENT '最后推送时间',
  PRIMARY KEY (`menu_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信公众号自定义菜单表';

/*Data for the table `wm_weixin_menu` */

/*Table structure for table `wm_weixin_msg` */

DROP TABLE IF EXISTS `wm_weixin_msg`;

CREATE TABLE `wm_weixin_msg` (
  `msg_id` int(4) NOT NULL AUTO_INCREMENT,
  `msg_account_id` int(4) NOT NULL DEFAULT '0' COMMENT '所属公众号的id',
  `msg_from` varchar(100) NOT NULL COMMENT '微信用户openid',
  `msg_type` varchar(20) NOT NULL DEFAULT 'text' COMMENT '消息类型',
  `msg_content` varchar(2000) DEFAULT NULL COMMENT '消息内容',
  `msg_attr` varchar(500) DEFAULT NULL COMMENT '附加消息内容',
  `msg_picurl` varchar(255) DEFAULT NULL COMMENT '图片消息的图片地址',
  `msg_url` varchar(255) DEFAULT NULL COMMENT '超链接消息的url',
  `msg_media_id` varchar(255) DEFAULT NULL COMMENT '微信临时素材媒体资源id',
  `msg_recognition` varchar(255) DEFAULT NULL COMMENT '语音消息识别结果',
  `msg_get` varchar(2000) NOT NULL COMMENT '网站接受到的用户消息内容',
  `msg_send` varchar(2000) NOT NULL COMMENT '网站回复消息内容',
  `msg_time` int(4) NOT NULL DEFAULT '0' COMMENT '用户发送消息时间',
  `msg_sendtime` int(4) NOT NULL DEFAULT '0' COMMENT '网站回复消息时间',
  PRIMARY KEY (`msg_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='微信对话消息记录表';

/*Data for the table `wm_weixin_msg` */

/*Table structure for table `wm_zt_node` */

DROP TABLE IF EXISTS `wm_zt_node`;

CREATE TABLE `wm_zt_node` (
  `node_id` int(4) NOT NULL AUTO_INCREMENT,
  `node_zt_id` int(4) NOT NULL DEFAULT '0' COMMENT '所属的专题id',
  `node_name` varchar(50) DEFAULT NULL COMMENT '节点名字',
  `node_pinyin` varchar(20) NOT NULL COMMENT '专题标识',
  `node_type` tinyint(1) DEFAULT '2' COMMENT '1为图片，2为普通内容输出，3为循环标签',
  `node_img` varchar(200) DEFAULT NULL COMMENT '图片地址',
  `node_content` text COMMENT '内容',
  `node_label` varchar(1000) DEFAULT NULL COMMENT '节点标签',
  `node_time` int(4) DEFAULT NULL COMMENT '节点创建时间',
  PRIMARY KEY (`node_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专题节点表';

/*Data for the table `wm_zt_node` */

/*Table structure for table `wm_zt_type` */

DROP TABLE IF EXISTS `wm_zt_type`;

CREATE TABLE `wm_zt_type` (
  `type_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_topid` int(4) NOT NULL DEFAULT '0' COMMENT '上级id',
  `type_pid` varchar(20) NOT NULL DEFAULT '0' COMMENT '子栏目id',
  `type_rec` tinyint(1) NOT NULL DEFAULT '0' COMMENT '是否推荐分类',
  `type_name` varchar(10) NOT NULL COMMENT '分类名',
  `type_cname` varchar(10) DEFAULT NULL COMMENT '类型简称',
  `type_pinyin` varchar(50) DEFAULT NULL COMMENT '类型拼音',
  `type_order` int(2) NOT NULL COMMENT '排序',
  `type_ico` varchar(200) DEFAULT NULL COMMENT '分类图标',
  `type_info` varchar(100) DEFAULT NULL COMMENT '分类信息',
  `type_tempid` int(4) NOT NULL DEFAULT '0' COMMENT '分类页模版',
  `type_ctempid` int(4) NOT NULL DEFAULT '0' COMMENT '内容页模版',
  `type_title` varchar(80) DEFAULT NULL COMMENT '页面标题',
  `type_key` varchar(100) DEFAULT NULL COMMENT '页面关键字',
  `type_desc` varchar(120) DEFAULT NULL COMMENT '页面描述',
  PRIMARY KEY (`type_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专题分类表';

/*Data for the table `wm_zt_type` */

/*Table structure for table `wm_zt_zt` */

DROP TABLE IF EXISTS `wm_zt_zt`;

CREATE TABLE `wm_zt_zt` (
  `zt_id` int(4) NOT NULL AUTO_INCREMENT,
  `type_id` int(4) NOT NULL DEFAULT '0' COMMENT '专题分类id',
  `zt_status` tinyint(1) DEFAULT '1' COMMENT '审核状态',
  `zt_name` varchar(20) NOT NULL COMMENT '专题名字',
  `zt_pinyin` varchar(20) DEFAULT NULL COMMENT '专题拼音',
  `zt_info` varchar(200) DEFAULT NULL COMMENT '导读',
  `zt_banner` varchar(200) DEFAULT NULL COMMENT '专题横幅',
  `zt_simg` varchar(200) DEFAULT NULL COMMENT '专题图片',
  `zt_read` int(4) DEFAULT '0' COMMENT '阅读量',
  `zt_replay` int(4) NOT NULL DEFAULT '0' COMMENT '评论量',
  `zt_content` varchar(2000) NOT NULL COMMENT '内容',
  `zt_time` int(4) NOT NULL DEFAULT '0' COMMENT '添加时间',
  PRIMARY KEY (`zt_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='专题表';
