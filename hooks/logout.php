<?php
/*
SNAF — Hooks — Logout
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
This file log out the user.
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

#Is our user logged in?
if (isset($user['login'])) { #Yes
	#Delete the cookie
	$cookie=session_get_cookie_params();
	if (!isset($cookie['domain']) && !isset($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path']); }
	else if (empty($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain']); }
	else { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain'],$cookie['secure']); }
	unset($cookie);
	session_destroy(); #Destroy the session
	echo 'success';
	exit(19);
} else { #No
	echo 'not logged in';
	exit(11);
}

?>
