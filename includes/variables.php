<?php
/*
SNAF — Variables
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
This file do some stuff to some variables.
*/

#Be sure this file is not the one who started execution
if (!defined('SNAF')) {
 	echo __FILE__.' is not a valid entry point';
	exit(1);
}

#If magic quotes is...
# ...off — stripslashes from input data
if (get_magic_quotes_runtime() == 0) {
	$_GET=array_map_recursive('stripslashes',$_GET);
	$_POST=array_map_recursive('stripslashes',$_POST);
	$_COOKIE=array_map_recursive('stripslashes',$_COOKIE);
	$_REQUEST=array_map_recursive('stripslashes',$_REQUEST);
}
#...on — addslashes on input data, if needed
else if (get_magic_quotes_runtime() == 1 && get_magic_quotes_gpc() == 0) {
	$_GET=array_map_recursive('addslashes',$_GET);
	$_POST=array_map_recursive('addslashes',$_POST);
	$_COOKIE=array_map_recursive('addslashes',$_COOKIE);
	$_REQUEST=array_map_recursive('addslashes',$_REQUEST);
}

?>
