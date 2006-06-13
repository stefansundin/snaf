<?php
/*
SNAF — Session
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
This file handles the session, i.e. it declares a $user variable which other
scripts can refer to to get a users username, permissions and other details
(like email and such).
The choice of $user instead of $_SESSION was decided because even users who
hasn't loggedin should be able to have their permissions and set options.
If a session cookie is detected this script checks if the session is valid,
if it is and contains an id, the script queries the database for user details,
which it then puts in $user.
If the user however isn't loggedin, the script put default values into $user.
*/

#Be sure this file is not the one who started execution
if (!defined('SNAF')) {
 	echo __FILE__.' is not a valid entry point';
	exit(1);
}

if (extension_loaded('session')) {
	#Restrict session to cookies to help prevent session fixation by url
	ini_set('session.use_only_cookies',true);
	#Set session_name
	session_name(SNAF_SESSIONPREFIX.'PHPSESSID');
	
	#Has our user loggedin?
	if (isset($_COOKIE[session_name()])) { #He might have
		session_start(); #Start session
		#If the session
		# have an id
		# have an IP associated with the session
		#then we proceed
		
		if (isset($_SESSION['id']) && isset($_SESSION['ip'])) {
			#We assume a session fixation if the IP associated with the session doesn't match our user's IP
			
			if ($_SESSION['ip'] == SNAF_IP) { #IP's match
				#Check if account still exists and update variables
				$result=mysql_query('SELECT permission '.
									'FROM '.SNAF_TABLEPREFIX.'accounts '.
									'WHERE id="'.mysql_real_escape_string($_SESSION['id']).'" '.
									'LIMIT 1')
									or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
				#Do the account still exists?
				if (mysql_numrows($result) !== 0) { #Yes
					if (unserialize(mysql_result($result,0,'permission')) == array('root')) {
						#root permissions
						$user['permission']=unserialize(mysql_result($result,0,'permission'));
					}
					else {
						$user['permission']=unserialize(mysql_result($result,0,'permission'));
					}
					if (!is_array($user['permission'])) { #This really shouldn't happen
						echo "Permission isn't an array, exiting";
						exit(2);
					}
					if (!in_array('login',$user['permission'])) {
						$user['permission'][]='login';
					}
				}
				mysql_free_result($result);
			}
		}
		
		#Delete the cookie if
		# the session it referred to didn't exist
		# not enough variables present in session
		# our users IP differ to our session's IP
		# the account no longer exists
		if (!isset($user)) {
			#Delete the cookie
			$cookie=session_get_cookie_params();
			if (!isset($cookie['domain']) && !isset($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path']); }
			else if (empty($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain']); }
			else { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain'],$cookie['secure']); }
			unset($cookie);
			session_destroy(); #Destroy the session
		}
	}
}

#User is not loggedin, set $user to guest permissions
if (!isset($user)) {
	$user['permission']=array('kaknoob');
}
	
?>
