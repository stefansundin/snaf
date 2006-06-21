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

if (isset($_GET['id'])) { #forum.php?id=2
	$id=$_GET['id']; }
else if (isset($_SERVER['PATH_INFO'])) { #forum.php/2
	$id=basename($_SERVER['PATH_INFO']); }

if (!isset($id)) {
	#Request for top-level forum
	$result=mysql_query('SELECT forum_id,thread_id,post_id,subject,author,body '.
	 'FROM '.SNAF_TABLEPREFIX.'fat '.
	 'WHERE forum_id=0 AND post_id=0')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
} else {
	#Request for a specific forum
	$result=mysql_query('SELECT forum_id,thread_id,post_id,subject,author,body '.
	 'FROM '.SNAF_TABLEPREFIX.'fat '.
	 'WHERE forum_id="'.mysql_real_escape_string($id).'" AND post_id>=0 AND post_id<=1')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
}

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
			echo "\t<forum forum_id=\"".mysql_result($result,$i,'thread_id')."\" num_threads=\"todo\" num_posts=\"todo\">\n";
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

/*
	<thread thread_id="1">
		<subject>SNAF</subject>
		<author>recover</author>
	</forum>
*/

echo '</everything>';

?>
