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

#Are our user logged in?
if (!isset($user['login'])) { #No
	$xml_result='not logged in';
} else { #Yes
	#Delete cookie
	$cookie=session_get_cookie_params();
	if (!isset($cookie['domain'])
	 && !isset($cookie['secure'])) {
		setcookie(session_name(),'',time()-3600,$cookie['path']); }
	else if (empty($cookie['secure'])) {
		setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain']); }
	else {
		setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain'],$cookie['secure']); }
	session_destroy(); #Destroy session
	
	$xml_result='success';
}

header('Content-Type: text/xml');

echo '<?xml version="1.0"?'.'>
<!DOCTYPE spec PUBLIC
	"-//W3C//DTD Specification V2.10//EN"
	"http://www.w3.org/2002/xmlspec/dtd/2.10/xmlspec.dtd">
<everything>
	<action result="'.$xml_result.'" />
</everything>';

?>
