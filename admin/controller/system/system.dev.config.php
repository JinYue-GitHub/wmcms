<?php
/**
* 开发者设置控制器文件
*
* @version        $Id: system.dev.config.php 2022年04月02日 12:43  weimeng
* @package        WMCMS
* @copyright      Copyright (c) 2015 WeiMengCMS, Inc.
* @link           http://www.weimengcms.com
*
*/
$manager = AdminNewClass('manager');
$configMod = NewModel('system.config');
$warningOpen = $configMod->GetByName('warning_open')['config_value'];
$warningChannel = $configMod->GetByName('warning_channel')['config_value'];
?>