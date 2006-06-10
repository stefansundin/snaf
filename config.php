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
This file should (in the future) be generated from install.php.
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
		exit(1);
	}
	
	#Define constants
	define('SNAF_SITENAME','Stiief');
	# Database settings
	define('SNAF_DBSERVER','localhost');
	define('SNAF_DBNAME','snaf');
	define('SNAF_DBUSER','snaf');
	# If you have more than one installation of SNAF, or if SNAF is conflicting with any of your existing tables, this constant is available so you can make your tables unique:
	define('SNAF_TABLEPREFIX','snaf_');
	# If you are not alone on a domain, it would be a good idea to set this to something unique to prevent unwanted session exchanges:
	define('SNAF_SESSIONPREFIX','snaf_');
	#You shouldn't need to edit the constants below
	define('SNAF_VERSION','0.00000000000000001 beta');
	define('SNAF_HTTPAUTH',((bool)ini_get('safe_mode'))?false:true);
	
	#Error handling
	# SNAF should be able to handle E_ALL
	#error_reporting(E_ALL);
	# This makes PHP report things that might have compability issues if you move the code to another server with possible different configuration
	error_reporting(E_ALL|E_STRICT);
	# For production web sites, you're encouraged to turn this off, see php.ini for more information
	ini_set('display_errors','on');
	
	#Connect to SQL database
	#Use mysql_connect() for a non-persistent connection
	mysql_pconnect(SNAF_DBSERVER,SNAF_DBUSER) or exit('SQL connect error');
	mysql_select_db(SNAF_DBNAME) or exit('SQL database select error');
	
	#Magic quotes
	set_magic_quotes_runtime(0);
	
	#Get users "real" ip — works if the user is browsing through a proxy
	if (isset($_SERVER['HTTP_CLIENT_IP'])) {
		define('SNAF_IP',$_SERVER['HTTP_CLIENT_IP']); }
	else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
		define('SNAF_IP',$_SERVER['HTTP_X_FORWARDED_FOR']); }
	else if (isset($_SERVER['REMOTE_ADDR'])) {
		define('SNAF_IP',$_SERVER['REMOTE_ADDR']); }
	else { define('SNAF_IP',NULL); } #This really shouldn't happen
	
	#Enable Microsoft Internet Explorer compatibility?
	if (eregi("MSIE",$_SERVER['HTTP_USER_AGENT']) || eregi("Internet Explorer",$_SERVER['HTTP_USER_AGENT'])) {
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
	
?>
