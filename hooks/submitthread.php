<?php
/*
SNAF — Hooks — Submit thread
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
This file submits a thread.
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
if (!in_array('login',$user['permission'])) {
	echo 'user not logged in';
	exit();
}

#Make sure we got all the stuff
if (!isset($_GET['thread_id']) || !isset($_POST['subject']) || !isset($_POST['body'])) {
	echo 'not enough stuff';
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
	echo 'no forum_id found';
	exit();
}

#Submit
mysql_query('INSERT INTO '.SNAF_TABLEPREFIX.'fat VALUES ('.
 mysql_result($result_forum_id,0,'forum_id').',
 "'.mysql_real_escape_string($_GET['thread_id']).'",
 4,
 "'.$_SESSION['username'].'",
 '.time().',
 "'.$_POST['subject'].'",
 "'.$_POST['body'].'")') or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

echo 'success';

/*INSERT INTO snaf_fat VALUES (
	1,
	1,
	1,
	"recover",
	1150736367,
	"SNAF forum",
	"Woohooo! SNAF är på väg! :P"
);*/

?>
