<?php
/*
SNAF — Hooks — Thread
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
This file query the SQL database for threads and return it in XML format.
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

#Make sure we got all the needed input
if (!isset($_GET['thread_id'])) {
	$xml_result='not enough input';
}
#Make sure there is something in my input
else if (!is_numeric($_GET['thread_id'])) {
	$xml_result='thread_id not valid';
}
else {
	$result=mysql_query('SELECT forum_id,post_id,author,date,subject,body '.
	 'FROM '.SNAF_TABLEPREFIX.'fat '.
	 'WHERE thread_id="'.mysql_real_escape_string($_GET['thread_id']).'" and post_id>0')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
	if (mysql_numrows($result) == 0) {
		$xml_result='thread not found';
	}
	else {
		$xml_result='success';
	}
}


header('Content-Type: text/xml; charset=utf-8');

echo '<?xml version="1.0"?'.'>
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="'.$xml_result.'" />'."\n";
if ($xml_result=='success') {
	$search=array('<','>','"');
	$replace=array('&lt;','&gt;','&quot;');
	for ($i=0; $i < mysql_numrows($result); $i++) {
		echo "\t<post post_id=\"".mysql_result($result,$i,'post_id')."\">\n";
		echo "\t\t<author>".str_replace($search,$replace,mysql_result($result,$i,'author'))."</author>\n";
		echo "\t\t<date>".date('Y-m-d H:i:s',mysql_result($result,$i,'date'))."</date>\n";
		echo "\t\t<subject>".str_replace($search,$replace,mysql_result($result,$i,'subject'))."</subject>\n";
		echo "\t\t<body>".str_replace($search,$replace,mysql_result($result,$i,'body'))."</body>\n";
		echo "\t</post>\n";
	}
}
echo '</everything>';

?>
