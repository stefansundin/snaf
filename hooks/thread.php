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

if (isset($_GET['id'])) {
	$id=$_GET['id']; }
else if (isset($_SERVER['PATH_INFO'])) {
	$id=urldecode($_SERVER['PATH_INFO']); }
else if (isset($_SERVER['REQUEST_URI'])) {
	$id=substr(urldecode($_SERVER['REQUEST_URI']),
	 -(strlen(urldecode($_SERVER['REQUEST_URI']))
	 -(strrpos(urldecode($_SERVER['REQUEST_URI']),':'))
	 -1
	)); }

if (!isset($id)) {
	echo 'Supply a thread id.';
	exit();
}

$result=mysql_query('SELECT id,thread_id,author,date,subject,body '.
 'FROM '.SNAF_TABLEPREFIX.'threads '.
 'WHERE thread_id="'.mysql_real_escape_string($id).'"')
 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

header('Content-Type: text/xml');
echo '<?xml version="1.0"?'.">\n";
echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<thread id="$id">\n
END;

if (mysql_numrows($result) !== 0) {
	for ($i=0; $i < mysql_numrows($result); $i++) {
		echo "\t\t<post id=\"".mysql_result($result,$i,'id')."\">\n";
		echo "\t\t\t<author>".mysql_result($result,$i,'author')."</author>\n";
		echo "\t\t\t<date>".date('Y-m-d H:i:s',mysql_result($result,$i,'date'))."</date>\n";
		echo "\t\t\t<subject>".mysql_result($result,$i,'subject')."</subject>\n";
		echo "\t\t\t<body>".mysql_result($result,$i,'body')."</body>\n";
		echo "\t\t</post>\n";
	}
}

echo <<<END
	</thread>
</everything>
END;

?>
