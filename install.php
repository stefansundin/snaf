<?php
/*
SNAF — Install
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
This file installs an SQL database.
*/
	#Be sure this file is the one who start execution
	if (defined('SNAF')) {
	 	echo __FILE__.' must be the entry point';
		exit(1);
	}
	define('SNAF',true);
	define('SNAF_ENTRYPOINT',__FILE__);
	#Initializing
	require_once('init.php');
	require_once('includes/functions.php');
	require_once('includes/variables.php');
	#Done
	
	#Output is in plain text
	header('Content-Type: text/plain; charset=utf-8');
	#Check if install is locked
	if (file_exists(SNAF_ROOT.'/install.lock')) {
		exit('Lock present, install aborted');
	}
	
	#Issue SQL Queries
	$keys=array_keys($sqlqueries);
	for ($i=0; $i < count($sqlqueries); $i++) {
		mysql_query($sqlqueries[$i]) or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error());
	}
	unset($i);
	
	#Try to lock install
	#Can I create an install.lock file, i.e. do I have permission?
	if (is_writable('.')) { #Yes
		file_put_contents('install.lock','');
		exit("Install complete");
	}
	else { #No — output notice
		exit("Install complete\n".
			"However, I did not have access to lock the installer.\n".
			"To lock the installer manually, create the file \"install.lock\".\n".
			"Alternatively you can delete \"".SNAF_ENTRYPOINT."\".");
	}
	
?>
