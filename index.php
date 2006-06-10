<?php
/*
SNAF - Spanking Nice AJAX Forum
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
	if (defined('SNAF') || defined('SNAF_WHERE')) {
		exit('Constants already set, exiting');
	}
	define('SNAF',true);
	define('SNAF_WHERE',__FILE__);
	#Initialize core
	require_once('init.php');
	require_once(SNAF_LIB.'/functions.php');
	require_once(SNAF_LIB.'/variables.php');
	require_once(SNAF_LIB.'/session.php');
	#Done
	
	echo '<?xml version="1.0" encoding="utf-8"?'.">\n";
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title>SNAF - Loading...</title>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<link rel="stylesheet" href="themes/<?php echo $theme; ?>/style.css" title="Default" type="text/css" charset="utf-8" media="all" />
</head>
<body>
	Loading...
</body>
</html>
