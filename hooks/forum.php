<?php
/*
SNAF — Hooks — Forum
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
This file query the SQL database for things in a forum and return it in XML
format.
Getting num:
SELECT COUNT(DISTINCT thread_id),COUNT(*) FROM snaf_fat WHERE forum_id=1 AND post_id>0
num_threads = COUNT(DISTINCT thread_id)
num_posts = COUNT(*)
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
if (!isset($_GET['forum_id'])) {
	echo 'not enough input';
	exit();
}
#Make sure there is something in my input
if (!is_numeric($_GET['forum_id'])) {
	echo 'forum_id not valid';
	exit();
}

#Query for forum
$result=mysql_query('SELECT forum_id,thread_id,post_id,subject,author,body '.
 'FROM '.SNAF_TABLEPREFIX.'fat '.
 'WHERE forum_id="'.mysql_real_escape_string($_GET['forum_id']).'" '.
 'AND (post_id=0 OR post_id=1)')
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
		if (mysql_result($result,$i,'post_id') == 0) {
			#Query for num_threads and num_posts
			$result_num=mysql_query('SELECT '.
			 'COUNT(DISTINCT thread_id),COUNT(*) '.
			 'FROM '.SNAF_TABLEPREFIX.'fat WHERE '.
			 'forum_id='.mysql_result($result,$i,'thread_id').' '.
			 'AND post_id>0')
			 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
			echo "\t<forum forum_id=\"".mysql_result($result,$i,'thread_id')."\"".
			 " num_threads=\"".mysql_result($result_num,0,'COUNT(DISTINCT thread_id)')."\"".
			 " num_posts=\"".mysql_result($result_num,0,'COUNT(*)')."\">\n";
			echo "\t\t<subject>".mysql_result($result,$i,'subject')."</subject>\n";
			echo "\t\t<body>".mysql_result($result,$i,'body')."</body>\n";
			echo "\t</forum>\n";
		} else {
			echo "\t<thread thread_id=\"".mysql_result($result,$i,'thread_id')."\">\n";
			echo "\t\t<subject>".mysql_result($result,$i,'subject')."</subject>\n";
			echo "\t\t<author>".mysql_result($result,$i,'author')."</author>\n";
			echo "\t</thread>\n";
		}
	}
}

echo '</everything>';

?>
