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
This file installs SQL stuff.
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
#Done

#Output is in plain text
header('Content-Type: text/plain; charset=utf-8');
#Check if install is locked
if (file_exists('install.lock')) {
	exit('Lock present, install aborted');
}

#Parse mysql.sql
$sql=file_get_contents('mysql.sql');
$sql=str_replace(array('snaf_',"\t"),array(SNAF_TABLEPREFIX,''),$sql);
$sql=preg_replace('/#[^\\n]*/','',$sql);
$sql=str_replace("\n",'',$sql);
$sql=explode(';',$sql);

#Issue SQL Queries
$keys=array_keys($sql);
for ($i=0; $i < count($sql); $i++) {
	if (!empty($sql[$i])) {
		mysql_query($sql[$i])
		 or exit('SQL error, file '.__FILE__.' line '.__LINE__.': '.mysql_error()."\nStatement:\n".$sql[$i]);
	}
}

#Try to lock install
#Can I create an install.lock file, i.e. do I have permission?
if (is_writable('.')) { #Yes
	file_put_contents('install.lock','');
	echo 'Install complete.';
}
else { #No — output notice
	$dirname=dirname(__FILE__);
	echo <<<END
Install complete.
However, I did not have access to lock the installer.
To lock the installer manually create the file "$dirname/install.lock"
Alternatively you can delete the install directory, "$dirname".
END;
}

?>
