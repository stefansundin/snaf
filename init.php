<?php
/*
SNAF - Spanking Nice AJAX Forum
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
This file defines some important constants, set up error handling, and connect to a SQL database.
*/
	if (!defined('SNAF') || defined('SNAF_ROOT') || defined('SNAF_LIB') || defined('SNAF_DATABASE') || defined('SNAF_TABLEPREFIX') || defined('SNAF_SESSIONPREFIX')) { #The 'SNAF' constant must have been set while the other constants can't be set
		exit('Constants already set, exiting');
	}
	#Define constants
	define('SNAF_ROOT',realpath('.'));
	define('SNAF_LIB',realpath('../lib'));
	define('SNAF_DATABASE','yapas');
	define('SNAF_TABLEPREFIX','yapas_'); #If you have more than one installation of SNAF, or if SNAF is conflicting with any of your existing tables, this constant is available so you can make your tables unique
	define('SNAF_SESSIONPREFIX','yapas_'); #If you are not alone on a domain, it would be a good idea to set this to something unique to prevent unwanted session exchanges.
	
	#Error handling
	#error_reporting(E_ALL); #SNAF should be able to handle E_ALL
	error_reporting(E_ALL|E_STRICT); #This makes PHP report things that might have compability issues if you move the code to another server with possible different configuration
	ini_set('display_errors','on'); #For production web sites, you're encouraged to turn this off, see php.ini for more information
	$ERROR=array();
	
	#Connect to SQL
	#Use mysql_pconnect() (instead of mysql_connect()) for a persistent connection.
	mysql_pconnect('localhost','snaf') or exit('SQL connect error');
	mysql_select_db(SNAF_DATABASE) or exit('SQL select database error');
?>
