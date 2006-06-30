<?php
/*
SNAF — Hooks — Submit reply
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
This file submits a reply to a thread.
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
	echo 'Not logged in';
	exit();
}

#Make sure we got all the needed input
if (!isset($_GET['thread_id'])
 || !isset($_POST['subject'])
 || !isset($_POST['body'])) {
	echo 'not enough input';
	exit();
}
#Make sure there is something in my input
if (!is_numeric($_GET['thread_id'])) {
	echo 'thread_id not valid';
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

#Query for forum_id
$result_forum_id=mysql_query('SELECT forum_id '.
 'FROM '.SNAF_TABLEPREFIX.'fat '.
 'WHERE thread_id="'.mysql_real_escape_string($_GET['thread_id']).'" '.
 'AND post_id=1 '.
 'LIMIT 1')
 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

if (mysql_numrows($result_forum_id) == 0) {
	echo 'forum_id not found';
	exit();
}

#Submit
mysql_query('INSERT INTO '.SNAF_TABLEPREFIX.'fat VALUES ('.
 mysql_result($result_forum_id,0,'forum_id').',
 "'.mysql_real_escape_string($_GET['thread_id']).'",
 NULL,
 "'.mysql_real_escape_string($_SESSION['username']).'",
 '.time().',
 "'.mysql_real_escape_string($_POST['subject']).'",
 "'.mysql_real_escape_string($_POST['body']).'")') or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

echo 'success';

?>
