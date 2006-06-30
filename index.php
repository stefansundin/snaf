<?php
/*
SNAF — Main
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
This file initializes an environment well suited for general usage.
*/

#Be sure this file is the one who start execution
if (defined('SNAF')) {
 	echo __FILE__.' must be the entry point';
	exit(1);
}
define('SNAF',true);
define('SNAF_ENTRYPOINT',__FILE__);
#Initialize
require_once('config.php');
require_once('includes/functions.php');
require_once('includes/variables.php');
require_once('includes/session.php');
#Done

echo '<?xml version="1.0" encoding="utf-8"?'.">\n";
?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>SNAF — Loading...</title>
	<link rel="stylesheet" href="themes/<?php echo SNAF_THEME; ?>/style.css" title="Default" type="text/css" charset="utf-8" media="all" />
	<script type="application/javascript" src="themes/<?php echo SNAF_THEME; ?>/base.js" charset="utf-8"></script>
	<script type="application/javascript">
window.onload=function() {
	//Create global variable
	window.snaf={
	 'loading':true,
	 'login':<?php echo isset($user['login'])?'true':'false'; ?>,
	 'current':{
	  'location':<?php echo isset($_GET['location'])?$_GET['location']:'forum'; ?>,
	  'locationid':<?php echo isset($_GET['locationid'])?$_GET['locationid']:'0'; ?> },
	 'back':[{
	  'location':forum,
	  'locationid':0 }],
	 'dontindex':true };
	load();
}
	</script>
</head>
<body>
	Your browser does not support JavaScript.
</body>
</html>
