<?php
/*
SNAF — MozSearch plugin
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
This file supplies MozSearch compatible User-Agents with search information.
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

#Content-Type
header('Content-Type: text/xml; charset=utf-8');
?>
<SearchPlugin xmlns="http://www.mozilla.org/2006/browser/search/">
	<ShortName><?php echo str_replace(array('<','>'),array('&lt;','&gt;'),SNAF_SITENAME); ?></ShortName>
	<Description><?php echo str_replace(array('<','>'),array('&lt;','&gt;'),SNAF_SITENAME); ?> Search</Description>
	<InputEncoding>utf-8</InputEncoding>
	<Image width="16" height="16">data:image/png;base64,<?php echo base64_encode(file_get_contents('themes/'.SNAF_THEME.'/img/icon.png')); ?></Image>
	<Url type="text/html" method="GET" template="<?php echo SNAF_SERVER.SNAF_REMOTEPATH; ?>/search:{searchTerms}" />
	<SearchForm><?php echo SNAF_SERVER.SNAF_REMOTEPATH; ?>/search</SearchForm>
</SearchPlugin>
