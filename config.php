<?php
/*
SNAF — Config
Copyright (C) 2006  Stefan Sundin (recover89@gmail.com)

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA

Description:
This file contains the config for the forum, should in the future be generated
from install.php.
*/

#Be sure this file is not the one who started execution, and the one who set some constants
if (!defined('SNAF')
 || defined('SNAF_DBSERVER')
 || defined('SNAF_DBNAME')
 || defined('SNAF_DBUSER')
 || defined('SNAF_TABLEPREFIX')
 || defined('SNAF_SESSIONPREFIX')
 || defined('SNAF_VERSION')) {
 	echo __FILE__.' is not a valid entry point';
	exit();
}

#Define constants
define('SNAF_SITENAME','*The* SNAF forum');
# Database settings
define('SNAF_DBSERVER','localhost');
define('SNAF_DBNAME','snaf');
define('SNAF_DBUSER','snaf');
# If you have more than one installation of SNAF, or if SNAF is conflicting with
# any of your existing tables, this constant is available so you can make your
# tables unique:
define('SNAF_TABLEPREFIX','snaf_');
# If you are not alone on a domain, it would be a good idea to set this to
# something unique to prevent unwanted session exchanges:
define('SNAF_SESSIONPREFIX','snaf_');
#You shouldn't need to edit the constants below
define('SNAF_VERSION','0.0000000008');
define('SNAF_HTTPAUTH',((bool)ini_get('safe_mode'))?false:true);

#Error handling
# SNAF should be able to handle E_ALL
error_reporting(E_ALL);
# This makes PHP report things that might have compability issues if you move
# the code to another server with possible different configuration
#error_reporting(E_ALL|E_STRICT);
# For production web sites, you're encouraged to turn this off, see php.ini for
# more information
ini_set('display_errors','on');

#Connect to SQL database
#Use mysql_connect() for a non-persistent connection
mysql_pconnect(SNAF_DBSERVER,SNAF_DBUSER) or exit('Failed to connect to SQL server');
mysql_select_db(SNAF_DBNAME) or exit('Failed to select SQL database');
#0 should not AUTO_INCREMENT, now only NULL does the trick
mysql_query('SET sql_mode="NO_AUTO_VALUE_ON_ZERO"')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

#Magic quotes
set_magic_quotes_runtime(0);

#Get URL to the server
# SERVER_NAME
if (isset($_SERVER['SERVER_NAME'])) {
	define('SNAF_SERVER_NAME',$_SERVER['SERVER_NAME']); }
else if (isset($_SERVER['HOSTNAME'])) {
	define('SNAF_SERVER_NAME',$_SERVER['HOSTNAME']); }
else if (isset($_SERVER['HTTP_HOST'])) {
	define('SNAF_SERVER_NAME',$_SERVER['HTTP_HOST']); }
else if (isset($_SERVER['SERVER_ADDR'])) {
	define('SNAF_SERVER_NAME',$_SERVER['SERVER_ADDR']); }
else { define('SNAF_SERVER_NAME','localhost'); }
# SERVER_PROTOCOL
define('SNAF_SERVER_PROTOCOL',
 (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == 'on')?'https':'http');
# SERVER
if (isset($_SERVER['SERVER_PORT']) && (
 (SNAF_SERVER_PROTOCOL == 'http' && $_SERVER['SERVER_PORT'] != 80 ) ||
 (SNAF_SERVER_PROTOCOL == 'https' && $_SERVER['SERVER_PORT'] != 443))) {
	#If the port isn't standard, add to SERVER
	define('SNAF_SERVER',SNAF_SERVER_PROTOCOL.'://'.SNAF_SERVER_NAME.':'.
		$_SERVER['SERVER_PORT']);
} else { #Else without port
	define('SNAF_SERVER',SNAF_SERVER_PROTOCOL.'://'.SNAF_SERVER_NAME);
}
# SERVER_URL
define('SNAF_REMOTEPATH','/snaf');
#Should be generated using dirname($_SERVER['SCRIPT_NAME']); in install.php

#Get users "real" ip — works if the user is browsing through a proxy
if (isset($_SERVER['HTTP_CLIENT_IP'])) {
	define('SNAF_IP',$_SERVER['HTTP_CLIENT_IP']); }
else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	define('SNAF_IP',$_SERVER['HTTP_X_FORWARDED_FOR']); }
else if (isset($_SERVER['REMOTE_ADDR'])) {
	define('SNAF_IP',$_SERVER['REMOTE_ADDR']); }
else { define('SNAF_IP',NULL); } #This really shouldn't happen

#Enable Microsoft Internet Explorer compatibility?
if (strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')
 || strpos($_SERVER['HTTP_USER_AGENT'],'Internet Explorer')) {
	define('SNAF_IECOMPAT',true);
}

#Theme
if (isset($_SESSION['theme'])) {
	if (in_array(basename($_SESSION['theme']),ls('../themes'))) {
		define('SNAF_THEME',basename($_SESSION['theme']));
	}
}
if (!defined('SNAF_THEME')) {
	define('SNAF_THEME','default');
}

#Headers
header('Content-Type: text/html; charset=utf-8');

?>
