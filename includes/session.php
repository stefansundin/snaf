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
This file handles the session, i.e. login, logout, $user, and $_SESSION.
The session should NOT be set unless the user successfully login, this way SNAF
will work even if PHP doesn't have session support.
If the username is composed only of numbers a login will be attempted using id
instead of username.
If no username is supplied, a login with id 1 is tried, which is the first account to be created (probably root).
*/
	#Be sure this file is not the one who started execution
	if (!defined('SNAF')) {
	 	echo __FILE__.' is not a valid entry point';
		exit(1);
	}
	
	if (extension_loaded('session')) {
		#Restrict session to cookies to help prevent session fixation
		ini_set('session.use_only_cookies',true);
		#Set session_name
		session_name(SNAF_SESSIONPREFIX.'PHPSESSID');
		
		if (isset($_COOKIE[session_name()])) { #User might already be loggedin
			session_start(); #Start session
			#If the session
			# have an id
			# have an IP associated with the session
			# and our user isn't logging out
			#then we proceed, otherwise we logout the user (delete the cookie)
			
			if (isset($_SESSION['id']) && !isset($_GET['logout']) && isset($_SESSION['ip'])) {
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
							$permission=$settings['availablepermission'];
							$permission[]='root';
						}
						else {
							$permission=unserialize(mysql_result($result,0,'permission'));
						}
						if (!is_array($permission)) { #This really shouldn't happen
							echo "$permission isn't an array, exiting";
							exit(2);
						}
						if (!in_array('login',$permission)) {
							$permission[]='login';
						}
					}
					else { #No
						#Delete the cookie
						$cookie=session_get_cookie_params();
						if (!isset($cookie['domain']) && !isset($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path']); }
						else if (empty($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain']); }
						else { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain'],$cookie['secure']); }
						unset($cookie);
						session_destroy(); #Destroy the session
					}
					mysql_free_result($result);
				}
				else { #Yes
					#Delete the cookie
					$cookie=session_get_cookie_params();
					if (!isset($cookie['domain']) && !isset($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path']); }
					else if (empty($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain']); }
					else { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain'],$cookie['secure']); }
					unset($cookie);
					session_destroy(); #Destroy the session
				}
			}
			else {
				#Delete the cookie
				$cookie=session_get_cookie_params();
				if (!isset($cookie['domain']) && !isset($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path']); }
				else if (empty($cookie['secure'])) { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain']); }
				else { setcookie(session_name(),'',time()-3600,$cookie['path'],$cookie['domain'],$cookie['secure']); }
				unset($cookie);
				session_destroy(); #Destroy the session
			}
		}
		else {
			#First; get login credentials, second; try to login, third; unset variables
			$login=array();
			#First; get login credentials:
			#Has the user supplied anything with POST?
			if (isset($_POST['username'])) { #username
				if ($_POST['username'] !== '') {
					$login['username']=$_POST['username'];
				}
			}
			if (isset($_POST['password'])) { #password
				if ($_POST['password'] !== '') {
					$login['password']=md5raw($_POST['password']);
				}
			}
			else if (SNAF_HTTPAUTH && isset($_GET['httpauth'])) { #Don't retrieve HTTP-Authentication data unless needed
				#Has the user supplied anything with HTTP-Authentication?
				if (isset($_SERVER['PHP_AUTH_USER'])) { #username
					if ($_SERVER['PHP_AUTH_USER'] !== '') {
						$login['username']=$_SERVER['PHP_AUTH_USER'];
					}
				}
				if (isset($_SERVER['PHP_AUTH_PW'])) { #password
					if ($_SERVER['PHP_AUTH_PW'] !== '') {
						$login['password']=md5raw($_SERVER['PHP_AUTH_PW']);
					}
				}
				else {
					header('WWW-Authenticate: Basic realm="YAPAS"');
					header('HTTP/1.0 401 Unauthorized');
					#exit('Cancel button pressed');
				}
			}
			if (!isset($login['username']) && isset($login['password'])) { #If no username is provided, presume id 1
				$login['username']=1;
			}
			#Second; try to login:
    		if (isset($login['username']) && isset($login['password'])) { #If credentials are available
				if (is_numeric($login['username'])) { #Login using id
					$result=mysql_query('SELECT id,username,permission '.
										'FROM '.SNAF_TABLEPREFIX.'accounts '.
										'WHERE id='.mysql_real_escape_string($login['username']).' '.
										'AND password="'.mysql_real_escape_string($login['password']).'" '.
										'LIMIT 1')
										or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
				}
				else { #Login using username
					$result=mysql_query('SELECT id,username,permission '.
										'FROM '.YAPAS_TABLEPREFIX.'accounts '.
										'WHERE username="'.mysql_real_escape_string($login['username']).'" '.
										'AND password="'.mysql_real_escape_string($login['password']).'" '.
										'LIMIT 1')
										or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
				}
				#Valid credentials?
				if (mysql_numrows($result) !== 0) { #Yes
					session_start();
					#To prevent session fixation, use if users can specify session ids in url
					#session_regenerate_id();
					$_SESSION['id']=(int)mysql_result($result,0,'id');
					$_SESSION['username']=mysql_result($result,0,'username');
					#IP check against session takeovers
					$_SESSION['ip']=SNAF_IP;
					$permission=unserialize(mysql_result($result,0,'permission'));
					if (!is_array($permission)) {
						echo "$permission isn't an array, exiting";
						exit(2);
					}
					if (!in_array('login',$permission)) {
						$permission[]='login';
					}
				}
				else { #No
					#$ERROR[]='login';
				}
			}
			#If user tried to login but only supplied one credential, either username or password
			else if (isset($login['username']) xor isset($login['password'])) {
				#$ERROR[]='login';
			}
			else { #The user didn't try to login
				#$permission=$settings['guestpermission'];
			}
			#Third; unset variables:
			unset($login);
		}
	}
	else { #Session is not enabled
		#$permission=$settings['guestpermission'];
	}
	
?>
