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
define('SNAF_SITENAME','The SNAF forum, no really, *the* SNAF forum');
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
define('SNAF_SERVER_FORCESSL',false);

#You shouldn't need to edit the constants below
define('SNAF_VERSION','0.0000000038');
define('SNAF_SUPPORT_HTTPAUTH',((bool)ini_get('safe_mode'))?false:true);
define('SNAF_SUPPORT_SSL',true);

#Error handling
# SNAF should be able to handle E_ALL
error_reporting(E_ALL);
# This makes PHP report things that might have compability issues if you move
# the code to another server with possible different configuration
#error_reporting(E_ALL|E_STRICT);
# For production web sites, you're encouraged to turn this off, see php.ini for
# more information
ini_set('display_errors','on');

#Magic quotes
set_magic_quotes_runtime(0);

#SNAF_SERVER
# _PROTOCOL
define('SNAF_SERVER_PROTOCOL',isset($_SERVER['HTTPS'])?'https':'http');
# _NAME
if (isset($_SERVER['SERVER_NAME'])) {
	define('SNAF_SERVER_NAME',$_SERVER['SERVER_NAME']); }
else if (isset($_SERVER['HOSTNAME'])) {
	define('SNAF_SERVER_NAME',$_SERVER['HOSTNAME']); }
else if (isset($_SERVER['HTTP_HOST'])) {
	define('SNAF_SERVER_NAME',$_SERVER['HTTP_HOST']); }
else if (isset($_SERVER['SERVER_ADDR'])) {
	define('SNAF_SERVER_NAME',$_SERVER['SERVER_ADDR']); }
else { define('SNAF_SERVER_NAME','localhost'); }
# _PORT
define('SNAF_SERVER_PORT',$_SERVER['SERVER_PORT']);
# _REMOTEPATH
#  Should be generated using dirname($_SERVER['SCRIPT_NAME']); in install.php
define('SNAF_SERVER_REMOTEPATH','/snaf');
# _URL
if ((SNAF_SERVER_PROTOCOL == 'http' && SNAF_SERVER_PORT != 80 )
 || (SNAF_SERVER_PROTOCOL == 'https' && SNAF_SERVER_PORT != 443)) {
	#Add port if not standard
	define('SNAF_SERVER_URL',SNAF_SERVER_PROTOCOL.'://'.SNAF_SERVER_NAME.':'.SNAF_SERVER_PORT);
} else {
	#Using standard port
	define('SNAF_SERVER_URL',SNAF_SERVER_PROTOCOL.'://'.SNAF_SERVER_NAME);
}

#Force SSL?
if (SNAF_SERVER_FORCESSL && SNAF_SERVER_PROTOCOL == 'http') {
	header('Location: https://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
	exit();
}

#Get users "real" ip — works if the user is browsing through a proxy
if (isset($_SERVER['HTTP_CLIENT_IP'])) {
	define('SNAF_IP',$_SERVER['HTTP_CLIENT_IP']); }
else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	define('SNAF_IP',$_SERVER['HTTP_X_FORWARDED_FOR']); }
else if (isset($_SERVER['REMOTE_ADDR'])) {
	define('SNAF_IP',$_SERVER['REMOTE_ADDR']); }
else { define('SNAF_IP',NULL); } #This really shouldn't happen

#Theme
if (isset($_SESSION['theme'])) {
	if (basename($_SESSION['theme']) != ''
	 && basename($_SESSION['theme']) != '.'
	 && basename($_SESSION['theme']) != '..') {
		define('SNAF_THEME',basename($_SESSION['theme']));
	}
}
if (!defined('SNAF_THEME')) {
	define('SNAF_THEME','default');
}

#Set mbstring settings
mb_internal_encoding('utf-8');
mb_detect_order('iso-8859-1');

#Connect to SQL database
#Use mysql_connect() for a non-persistent connection
mysql_pconnect(SNAF_DBSERVER,SNAF_DBUSER) or exit('Failed to connect to SQL server');
mysql_select_db(SNAF_DBNAME) or exit('Failed to select SQL database');
#0 should not AUTO_INCREMENT, now only NULL does the trick
mysql_query('SET sql_mode="NO_AUTO_VALUE_ON_ZERO"')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
mysql_query('SET NAMES utf8')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
mysql_query('SET character_set_server=utf8')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

?>
