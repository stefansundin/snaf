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
	exit();
}
define('SNAF',true);
define('SNAF_ENTRYPOINT',__FILE__);
#Include
require_once('../config.php');
require_once('../includes/functions.php');
require_once('../includes/variables.php');
require_once('../includes/session.php');

#Must be logged in
if (!$user['login']) {
	$xml_result='not logged in';
}
#Make sure we got all the needed input
else if (!isset($_GET['thread_id'])
 || !isset($_POST['subject'])
 || !isset($_POST['body'])) {
	$xml_result='not enough input';
}
#Make sure there is something in my input
else if (!is_numeric($_GET['thread_id'])) {
	$xml_result='thread_id not valid';
}
else if ($_POST['subject'] == '') {
	$xml_result='empty subject';
}
else if ($_POST['body'] == '') {
	$xml_result='empty body';
}
else if (mb_strlen($_POST['subject']) > 50) {
	$xml_result='too long subject';
}
else if (strlen($_POST['body']) > 65535) {
	$xml_result='too long body';
}
#Input seems good so far
else {
	#Query for forum_id
	$result=mysql_query('SELECT forum_id '.
	 'FROM '.SNAF_TABLEPREFIX.'fat '.
	 'WHERE thread_id="'.mysql_real_escape_string($_GET['thread_id']).'" '.
	 'AND post_id=1 '.
	 'LIMIT 1')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
	
	if (mysql_numrows($result) == 0) {
		$xml_result='forum_id not found';
	}
	else {
		#Submit
		mysql_query('INSERT INTO '.SNAF_TABLEPREFIX.'fat VALUES ('.
		 mysql_result($result,0,'forum_id').','.
		 '"'.mysql_real_escape_string($_GET['thread_id']).'",'.
		 'NULL,'.
		 '"'.mysql_real_escape_string($_SESSION['username']).'",'.
		 time().','.
		 '"'.mysql_real_escape_string($_POST['subject']).'",'.
		 '"'.mysql_real_escape_string($_POST['body']).'")')
		  or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
		
		$xml_result='success';
	}
}


header('Content-Type: text/xml; charset=utf-8');

echo '<?xml version="1.0"?'.'>
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="'.$xml_result.'" />
</everything>';

?>
