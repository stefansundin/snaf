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
#Initialize
require_once('../config.php');
require_once('../includes/functions.php');
require_once('../includes/variables.php');
require_once('../includes/session.php');
#Done

#Make sure we got all the needed input
if (!isset($_GET['thread_id'])) {
	echo 'not enough input';
	exit();
}
#Make sure there is something in my input
if (!is_numeric($_GET['thread_id'])) {
	echo 'thread_id not valid';
	exit();
}

$result=mysql_query('SELECT forum_id,post_id,author,date,subject,body '.
 'FROM '.SNAF_TABLEPREFIX.'fat '.
 'WHERE thread_id="'.mysql_real_escape_string($_GET['thread_id']).'" and post_id>0')
 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

header('Content-Type: text/xml');
echo '<?xml version="1.0"?'.">\n";
echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>\n
END;

if (mysql_numrows($result) !== 0) {
	for ($i=0; $i < mysql_numrows($result); $i++) {
		echo "\t<post post_id=\"".mysql_result($result,$i,'post_id')."\">\n";
		echo "\t\t<author>".mysql_result($result,$i,'author')."</author>\n";
		echo "\t\t<date>".date('Y-m-d H:i:s',mysql_result($result,$i,'date'))."</date>\n";
		echo "\t\t<subject>".mysql_result($result,$i,'subject')."</subject>\n";
		echo "\t\t<body>".mysql_result($result,$i,'body')."</body>\n";
		echo "\t</post>\n";
	}
}

echo '</everything>';

?>
