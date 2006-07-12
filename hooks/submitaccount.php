<?php
/*
SNAF — Hooks — Submit account
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
This file submits an account.
*/

#Be sure this file is the one who start execution
if (defined('SNAF')) {
 	echo __FILE__.' must be the entry point';
	exit();
}
define('SNAF',true);
define('SNAF_ENTRYPOINT',__FILE__);
#Initialize
require_once('../config.php');
require_once('../includes/functions.php');
require_once('../includes/variables.php');
require_once('../includes/session.php');
#Done

#Make sure we got all the needed input
if (!isset($_POST['username'])
 || !isset($_POST['password'])
 || !isset($_POST['email'])) {
	echo 'not enough input';
	exit();
}
#Make sure there is something in my input
if ($_POST['username'] == '') {
	echo 'empty username';
	exit();
}
if ($_POST['password'] == '') {
	echo 'empty password';
	exit();
}

#Query to check if username already exists
$result=mysql_query('SELECT COUNT(*) '.
 'FROM '.mysql_real_escape_string(SNAF_TABLEPREFIX).'accounts '.
 'WHERE username="'.$_POST['username'].'" LIMIT 1')
 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

if (mysql_result($result,0,'COUNT(*)') !== '0') {
	echo 'username already exists';
	exit();
}

#Submit
mysql_query('INSERT INTO '.SNAF_TABLEPREFIX.'accounts VALUES ('.
 'NULL,'.
 '"'.$_POST['username'].'",'.
 '"'.mysql_real_escape_string(md5raw($_POST['password'])).'",'.
 '"'.mysql_real_escape_string(serialize(array('registred user'))).'",'.
 '"'.mysql_real_escape_string(serialize(array('email'=>$_POST['email']))).'")')
  or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

echo 'success';

?>
