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

http_request.readyState:
0 Uninitialized
    The initial value.
1 Open
    The open() method has been successfully called.
2 Sent
    The UA successfully completed the request, but no data has yet been received.
3 Receiving
    Immediately before receiving the message body (if any). All HTTP headers have been received.
4 Loaded
    The data transfer has been completed.
http_request.status:
If the status attribute is not available it MUST raise an exception. It MUST be available when readyState is 3 (Receiving) or 4 (Loaded). When available, it MUST represent the HTTP status code (typically 200 for a successful connection).
while(parent.hasChildNodes) { parent.removeChild(parent.firstChild)); }
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

window.onload=function() {
<?php
if (in_array('login',$user['permission'])) {
	echo "dologin('success');\n";
} else {
	echo "dologout('success');\n";
}
?>
}

function login() {
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n\
		       You are most likely using an old browser.');
		return false;
	}
	
	http_request.onreadystatechange=function() { readylistener(http_request,dologin); };
	http_request.open('POST','login',true);
	http_request.setRequestHeader('Content-Type','application/x-www-form-urlencoded; charset=utf-8');
	http_request.send('username='+encodeURI(document.getElementById('username').value)+'&password='+encodeURI(document.getElementById('password').value));
}

function dologin(state) {
	d=document.getElementById('login');
	if (state == 'success') {
		while (d.hasChildNodes()) { d.removeChild(d.firstChild); }
		a=document.createElement('a');
		a.onclick=function(){ logout(); }
		a.appendChild(document.createTextNode('Logout'));
		d.appendChild(a);
	}
	else if (state == 'no credentials' || state == 'one credential') {
		if (!(e=document.getElementById('loginerror'))) {
			e=document.createElement('span');
			e.id='loginerror';
			d.appendChild(e);
		} else {
			e.removeChild(e.firstChild);
		}
		e.appendChild(document.createTextNode('Supply all credentials'));
	}
	else if (state == 'invalid credentials') {
		if (!(e=document.getElementById('loginerror'))) {
			e=document.createElement('span');
			e.id='loginerror';
			d.appendChild(e);
		} else {
			e.removeChild(e.firstChild);
		}
		d.appendChild(document.createTextNode('Invalid credentials'));
	}
}

function logout() {
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n\
		       You are most likely using an old browser.');
		return false;
	}
	
	http_request.onreadystatechange=function() { readylistener(http_request,dologout); };
	http_request.open('GET','logout',true);
	http_request.send(null);
}

function dologout(state) {
	d=document.getElementById('login');
	if (state == 'success') {
		while (d.hasChildNodes()) { d.removeChild(d.firstChild); }
		
		f=document.createElement('form');
		f.method='post'; f.onsubmit=function(){ login(); return false; }
		
		f.appendChild(document.createTextNode('Username: '));
		i=document.createElement('input');
		i.type='text'; i.name='username'; i.id='username';
		f.appendChild(i);
		f.appendChild(document.createElement('br'));
		
		f.appendChild(document.createTextNode('Password: '));
		i=document.createElement('input');
		i.type='password'; i.name='password'; i.id='password';
		f.appendChild(i);
		f.appendChild(document.createElement('br'));
		
		i=document.createElement('input');
		i.type='submit'; i.value='Login';
		f.appendChild(i);
		
		d.appendChild(f);
	}
}

function readylistener(http_request,callbackfunction) {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			callbackfunction(http_request.responseText);
			//if (http_request.responseText == 'success') {
				//document.getElementById('login').innerHTML='logged in';
			//} else if (http_request.responseText == 'invalid credentials') {
				//document.getElementById('loginerror').innerHTML='invalid credentials';
			//}
			//alert(http_request.responseText);
		} else { //Error
			alert('There was a problem with the request:\n\
			     '+http_request.statusText);
		}
	}
}
