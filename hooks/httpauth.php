<?php
/*
SNAF — Hooks — Login through HTTP-Authentication
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
This file validates the credentials when a user try to login through HTTP-Auth.
$_SESSION is NOT set unless our user successfully logins.
If the username is composed of numbers the username is assumed to be a users id.
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

#Is HTTP-Authentication enabled?
if (!SNAF_HTTPAUTH) { #No
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
<action result="http-auth disabled" />
</everything>
END;
	exit();
}

#Are our user already logged in?
if (isset($user['login'])) { #Yes
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
<action result="already logged in" />
</everything>
END;
	exit();
}

#Make sure we got all the needed input
if (!isset($_SERVER['PHP_AUTH_USER'])
 || !isset($_SERVER['PHP_AUTH_PW'])) {
	#This might just as well mean that the user hasn't been prompted
	header('WWW-Authenticate: Basic realm="'.SNAF_SITENAME.'"');
	header('HTTP/1.0 401 Unauthorized');
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
if ($_SERVER['PHP_AUTH_USER'] == '') {
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
<action result="empty username" />
</everything>
END;
	exit();
}
if ($_SERVER['PHP_AUTH_PW'] == '') {
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
<action result="empty password" />
</everything>
END;
	exit();
}

#Query if login was successful using user_id if username is composed of numbers
if (is_numeric($_SERVER['PHP_AUTH_USER'])) {
	$result=mysql_query('SELECT user_id,username,permission '.
	 'FROM '.SNAF_TABLEPREFIX.'accounts '.
	 'WHERE user_id='.mysql_real_escape_string($_SERVER['PHP_AUTH_USER']).' '.
	 'AND password="'.mysql_real_escape_string(md5raw($_SERVER['PHP_AUTH_PW'])).'" '.
	 'LIMIT 1')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
}
#Or else with username
else {
	$result=mysql_query('SELECT user_id,username,permission '.
	 'FROM '.SNAF_TABLEPREFIX.'accounts '.
	 'WHERE username="'.mysql_real_escape_string($_SERVER['PHP_AUTH_USER']).'" '.
	 'AND password="'.mysql_real_escape_string(md5raw($_SERVER['PHP_AUTH_PW'])).'" '.
	 'LIMIT 1')
	 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
}

#Valid credentials?
if (mysql_numrows($result) !== 0) { #Yes
	session_start();
	$_SESSION['user_id']=(int)mysql_result($result,0,'user_id');
	$_SESSION['username']=mysql_result($result,0,'username');
	#IP check against session takeovers
	$_SESSION['ip']=SNAF_IP;
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>\n
END;
	echo '<action result="success" user_id="'.mysql_result($result,0,'user_id').'" username="'.mysql_result($result,0,'username').'" />'."\n";
	echo '</everything>';
	exit();
}
else { #No
	echo '<?xml version="1.0"?'.">\n";
	echo <<<END
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
<action result="invalid credentials" />
</everything>
END;
	exit();
}

?>
