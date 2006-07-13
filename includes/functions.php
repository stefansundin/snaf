<?php
/*
SNAF — Functions
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
This file contains functions used throughout SNAF.
*/

#Be sure this file is not the one who started execution
if (!defined('SNAF')) {
 	echo __FILE__.' is not a valid entry point';
	exit();
}

# array_map_recursize — Same as array_map, but recursize
if (!function_exists('array_map_recursive')) {
	function array_map_recursive($function,$data) {
		foreach ($data as $i => $item) {
			$data[$i]=is_array($item)
				?array_map_recursive($function,$item)
				:$function($item);
		}
		return $data;
	}
}

#Session
# md5raw — Returns an md5 in a raw binary format with a 16 bytes length
if (!function_exists('md5raw')) {
	function md5raw($string) {
		return pack('H*',md5($string));
	}
}

#Files
# formatsize — Returns a human-readable string from $size bytes
if (!function_exists('formatsize')) {
	function formatsize($size,$precision = 2) {
		if ($size > 1024*1024) {
			return round(($size / 1024*1024),$precision).' MiB';
		}
		else if ($size > 1024) {
			return round(($size / 1024),$precision).' KiB';
		}
		else {
			return $size.' Byte'.($size!=1?'s':'');
		}
	}
}
# ls — Returns an array with the contents of $dir
if (!function_exists('ls')) {
	function ls($dir) {
		$filelist=array();
		$handle=opendir($dir);
		while (false !== ($file = readdir($handle))) {
			if ($file != '.' && $file != '..') {
				$filelist[] = $file;
			}
		}
		closedir($handle);
		return $filelist;
	}
}

#URLs
# translate_uri — Maintain slashes, in contrary to translate_url
if (!function_exists('translate_uri')) {  
	function translate_uri($uri) {
		$parts=explode('/', $uri);
		for ($i=0; $i < count($parts); $i++) {
			$parts[$i]=rawurlencode($parts[$i]);
		}
		return implode('/', $parts);
	}
}

?>
