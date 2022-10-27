<?php
$old = '# Helicon ISAPI_Rewrite configuration file
# Version 3.1.0.102

RewriteEngine On
RewriteBase /

#####此行代码是为了防止扒窃模板#######
RewriteRule ^templates/(.*?).html$ /404.php
RewriteRule ^plugin/(.*?)templates/(.*?).html$ /404.php
#####此行代码是为了防止扒窃模板#######

RewriteCond %{REQUEST_FILENAME} !-f
RewriteRule ^index.html$ /index.php
RewriteRule ^novel/index_(.*?).html$ /module/novel/index.php?tp=$1
RewriteRule ^(\d*)/list/(\d*).html$ /module/novel/type.php?tid=$1&page=$2
RewriteRule ^(\d*)/(\d*)/info.html$ /module/novel/info.php?tid=$1&nid=$2
RewriteRule ^(\d*)/(\d*)/menu/(\d*).html$ /module/novel/menu.php?tid=$1&nid=$2&page=$3
RewriteRule ^(\d*)/(\d*)/read/(\d*).html$ /module/novel/read.php?tid=$1&nid=$2&cid=$3
RewriteRule ^(\d*)/(\d*)/replay/(\d*).html$ /module/novel/replay.php?pt=$4&tid=$1&nid=$2&page=$3
RewriteRule ^top/index.html$ /module/novel/topindex.php
RewriteRule ^top/list/(\d*)/(.*?)/(\d*).html$ /module/novel/toplist.php?tid=$1&type=$2&page=$3
RewriteRule ^search/(.*?)/(.*?)/(\d*).html$ /module/novel/search.php?type=$1&key=$2&page=$3
RewriteRule ^(\d*)/list/(\d*)_(\d*)_(\d*)_(\d*)_(\d*)_(\d*)_(\d*)_(\d*).html$ /module/novel/type.php?tid=$1&page=$9&process=$2&word=$3&chapter=$4&copy=$5&cost=$6&letter=$7&order=$8
';

$new = '<IfModule mod_rewrite.c>
 RewriteEngine on
 RewriteCond %{REQUEST_FILENAME} !-d
 RewriteCond %{REQUEST_FILENAME} !-f
 RewriteRule ^(.*)$ index.php?/$1 [QSA,PT,L]
</IfModule>
';
?>