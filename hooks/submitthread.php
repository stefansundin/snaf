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
This file submits a thread to a forum.
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

header('Content-Type: text/xml');

#Must be logged in
if (!isset($user['login'])) {
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="not logged in" />
</everything>
END;
	exit();
}

#Make sure we got all the needed input
if (!isset($_GET['forum_id'])
 || !isset($_POST['subject'])
 || !isset($_POST['body'])) {
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="not enough input" />
</everything>
END;
	exit();
}
#Make sure there is something in my input
if (!is_numeric($_GET['forum_id'])) {
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="forum_id not valid" />
</everything>
END;
	exit();
}
if ($_POST['subject'] == '') {
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="empty subject" />
</everything>
END;
	exit();
}
if ($_POST['body'] == '') {
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="empty body" />
</everything>
END;
	exit();
}

#Query for thread_id
$result=mysql_query('SELECT MAX(thread_id) '.
 'FROM '.SNAF_TABLEPREFIX.'fat LIMIT 1')
 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

if (mysql_numrows($result) == 0) {
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="thread_id not found" />
</everything>
END;
	exit();
} else {
	$thread_id=mysql_result($result,0,'MAX(thread_id)')+1;
}

#Submit
mysql_query('INSERT INTO '.SNAF_TABLEPREFIX.'fat VALUES ('.
 mysql_real_escape_string($_GET['forum_id']).','.
 $thread_id.','.
 'NULL,'.
 '"'.mysql_real_escape_string($_SESSION['username']).'",'.
 time().','.
 '"'.mysql_real_escape_string($_POST['subject']).'",'.
 '"'.mysql_real_escape_string($_POST['body']).'")')
 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());

echo '<?xml version="1.0"?'.">\n";
echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="success" thread_id="${thread_id}" />
</everything>
END;

?>
