<?php
/*
SNAF — Hooks — Submit forum
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
This file submits a (sub/top)-level forum forum.
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

#Must be logged in
if (!isset($user['login'])) {
	echo 'not logged in';
	exit();
}

#Make sure we got all the needed input
if (!isset($_GET['forum_id'])
 || !isset($_POST['subject'])
 || !isset($_POST['body'])) {
	echo 'not enough input';
	exit();
}
#Make sure there is something in my input
if (!is_numeric($_GET['forum_id'])) {
	echo 'forum_id not valid';
	exit();
}
if ($_POST['subject'] == '') {
	echo 'empty subject';
	exit();
}
if ($_POST['body'] == '') {
	echo 'empty body';
	exit();
}

#Query for thread_id
$result=mysql_query('SELECT MAX(thread_id) '.
 'FROM '.SNAF_TABLEPREFIX.'fat '.
 'WHERE post_id=0 LIMIT 1')
 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

//MAX(thread_id) will return NULL if there is no rows
if (mysql_result($result,0,'MAX(thread_id)') == 'NULL') {
	$thread_id=1;
} else {
	$thread_id=mysql_result($result,0,'MAX(thread_id)')+1;
}

#Submit
mysql_query('INSERT INTO '.SNAF_TABLEPREFIX.'fat VALUES ('.
 mysql_real_escape_string($_GET['forum_id']).','.
 $thread_id.','.
 '0,'.
 '"'.mysql_real_escape_string($_SESSION['username']).'",'.
 time().','.
 '"'.mysql_real_escape_string($_POST['subject']).'",'.
 '"'.mysql_real_escape_string($_POST['body']).'")')
 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

echo 'success';

?>
