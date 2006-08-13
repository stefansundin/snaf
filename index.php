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
	exit();
}
define('SNAF',true);
define('SNAF_ENTRYPOINT',__FILE__);
#Include
require_once('config.php');
require_once('includes/functions.php');
require_once('includes/variables.php');
require_once('includes/session.php');

#Content-Type
header('Content-Type: text/html; charset=utf-8');

echo '<?xml version="1.0" encoding="utf-8"?'.">\n";
?>
<!DOCTYPE html PUBLIC
	"-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
	<title><?php echo str_replace(array('<','>'),array('&lt;','&gt;'),SNAF_SITENAME); ?> — Loading...</title>
	<link rel="icon" href="themes/<?php echo SNAF_THEME; ?>/img/icon.png" type="image/png" />
	<link rel="stylesheet" href="themes/<?php echo SNAF_THEME; ?>/style.css" title="Default" type="text/css" charset="utf-8" media="all" />
	<link rel="search" href="mozsearch.php" title="<?php echo str_replace(array('"','<','>'),array('&quot;','&lt;','&gt;'),SNAF_SITENAME); ?>" type="application/opensearchdescription+xml" />
	<script type="application/javascript" src="themes/<?php echo SNAF_THEME; ?>/base.js" charset="utf-8"></script>
	<script type="application/javascript">
window.onload=function() {
	//Create global variable
	window.snaf={
	 loading:true,
	 sitename:'<?php echo str_replace(array('<','>',"'"),array('&lt;','&gt;',"\\'"),SNAF_SITENAME); ?>',
	 version:'<?php echo SNAF_VERSION; ?>',
	 user:{
<?php
if ($user['login']) {
	echo <<<END
	  login:true,
	  user_id:{$_SESSION['user_id']},
	  username:'{$_SESSION['username']}' },\n
END;
} else {
	echo "\t  login:false },\n";
}
?>
	 current:{
	  location:<?php echo isset($_GET['location'])?$_GET['location']:'forum'; ?>,
	  locationid:<?php echo isset($_GET['locationid'])?$_GET['locationid']:'0'; ?> },
	 support:{
	  httpauth:<?php echo SNAF_SUPPORT_HTTPAUTH?'true':'false'; ?>,
	  ssl:<?php echo SNAF_SUPPORT_SSL?'true':'false'; ?> },
	 back:[],
	 backindex:false,
	 display:{},
	 focus:null };
	load();
}
	</script>
</head>
<body>
	<h1><?php echo str_replace(array('<','>'),array('&lt;','&gt;'),SNAF_SITENAME); ?></h1>
	<p>Your browser does not support JavaScript (Or your internet connection is very slow, still downloading JavaScript).</p>
	<hr />
	<p><i>SNAF version <?php echo SNAF_VERSION; ?></i></p>
</body>
</html>
