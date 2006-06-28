<?php
/*
SNAF — Hooks — Login
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
This file validates the credentials when a user try to login.
$_SESSION is NOT set unless our user successfully logins.
If the username is composed of numbers the username is assumed to be a users id.
If no username is supplied, a login with id 1 is tried, which is the first
account to be created.
*/

#Be sure this file is the one who start execution
if (defined('SNAF')) {
 	echo __FILE__.' must be the entry point';
	exit(1);
}
define('SNAF',true);
define('SNAF_ENTRYPOINT',__FILE__);
#Initialize
require_once('../config.php');
require_once('../includes/functions.php');
require_once('../includes/variables.php');
require_once('../includes/session.php');
#Done

#Have our user already logged in?
if (isset($user['login'])) { #Yes
	echo 'already logged in';
	exit(5);
}

#Get login credentials
$login=array();
# POST?
if (isset($_POST['username'])) { #username
	if ($_POST['username'] !== '') {
		$login['username']=$_POST['username'];
	}
}
if (isset($_POST['password'])) { #password
	if ($_POST['password'] !== '') {
		$login['password']=md5raw($_POST['password']);
	}
}
# HTTP-Authentication?
else if (SNAF_HTTPAUTH && isset($_GET['httpauth'])) {
	#Has the user supplied anything with HTTP-Authentication?
	if (isset($_SERVER['PHP_AUTH_USER'])) { #username
		if ($_SERVER['PHP_AUTH_USER'] !== '') {
			$login['username']=$_SERVER['PHP_AUTH_USER'];
		}
	}
	if (isset($_SERVER['PHP_AUTH_PW'])) { #password
		if ($_SERVER['PHP_AUTH_PW'] !== '') {
			$login['password']=md5raw($_SERVER['PHP_AUTH_PW']);
		}
	}
	else { #No password provided, query for HTTP Authentication
		header('WWW-Authenticate: Basic realm="SNAF"');
		header('HTTP/1.0 401 Unauthorized');
		#exit('Cancel button pressed');
	}
}
/*# If no username has been provided, presume id 1
if (!isset($login['username']) && isset($login['password'])) {
	$login['username']=1;
}*/

#Try to login if credentials were supplied
if (isset($login['username']) && isset($login['password'])) {
	#Login using id if username is composed of numbers
	if (is_numeric($login['username'])) {
		$result=mysql_query('SELECT id,username,permission '.
		 'FROM '.SNAF_TABLEPREFIX.'accounts '.
		 'WHERE id='.mysql_real_escape_string($login['username']).' '.
		 'AND password="'.mysql_real_escape_string($login['password']).'" '.
		 'LIMIT 1')
		 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
	}
	#Or else with username
	else {
		$result=mysql_query('SELECT id,username,permission '.
		 'FROM '.SNAF_TABLEPREFIX.'accounts '.
		 'WHERE username="'.mysql_real_escape_string($login['username']).'" '.
		 'AND password="'.mysql_real_escape_string($login['password']).'" '.
		 'LIMIT 1')
		 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
	}
	#Valid credentials?
	if (mysql_numrows($result) !== 0) { #Yes
		session_start();
		#Generate a new session id, use if users can specify session ids in url
		#session_regenerate_id();
		$_SESSION['id']=(int)mysql_result($result,0,'id');
		$_SESSION['username']=mysql_result($result,0,'username');
		#IP check against session takeovers
		$_SESSION['ip']=SNAF_IP;
		echo 'success';
		exit(9);
	}
	else { #No
		echo 'invalid credentials';
		exit(8);
	}
}

#Did our user supply any credentials at all?
if (!isset($login['username']) and !isset($login['password'])) {
	echo 'no credentials';
	exit(7);
}

#Did our user try to login but supplied only one credential?
else if (isset($login['username']) xor isset($login['password'])) {
	echo 'one credential';
	exit(6);
}

?>
