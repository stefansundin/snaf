<?php
/*
SNAF — Default — Basejs
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
This file controlls the JavaScript which the entire site depends on.
*/

#Be sure this file is the one who start execution
if (defined('SNAF')) {
 	echo __FILE__.' must be the entry point';
	exit(1);
}
define('SNAF',true);
define('SNAF_ENTRYPOINT',__FILE__);
#Initialize
require_once('../../config.php');
require_once('../../includes/functions.php');
require_once('../../includes/variables.php');
require_once('../../includes/session.php');
#Done
?>

window.onload=function(){
	//alert('test');
}

function login()
{
	var file='login';
	var query='username='+encodeURI(document.getElementById('username').value)+'&password='+encodeURI(document.getElementById('password').value);
	var xmlReq=getXML(file,query);
}

function getXML(file,str)
{
	var http_request=false;
	if (window.XMLHttpRequest) { //Standard
		http_request=new XMLHttpRequest();
	} else if (window.ActiveXObject) { //IE
		try {
			http_request=new ActiveXObject("Msxml2.XMLHTTP");
		} catch (e) {
			try {
				http_request=new ActiveXObject("Microsoft.XMLHTTP");
			} catch (e) {}
		}
	}
	if (!http_request) {
		alert('Unable to create an XMLHttp instance.\n\
		       You are probably using an obsolete browser.');
		return false;
	}
	
	http_request.onreadystatechange=function() { alertContents(http_request); };
	http_request.open('POST',file,true);
	http_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(str);
}

function alertContents(http_request) {
	if (http_request.readyState == 4) { //Request done
		if (http_request.status == 200) { //OK
			alert(http_request.responseText);
		} else { //Error
			alert('There was a problem with the request:\n\
			     '+http_request.statusText);
		}
	}
}
