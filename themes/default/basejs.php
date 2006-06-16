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
This file have the JavaScript which the entire site depends on.

http_request.readyState:
 * 0 — Uninitialized — The initial value.
 * 1 — Open — The open() method has been successfully called.
 * 2 — Sent — The UA successfully completed the request,
              but no data has yet been received.
 * 3 — Receiving — Immediately before receiving the message body (if any).
                   All HTTP headers have been received.
 * 4 — Loaded — The data transfer has been completed.

http_request.status:
 200 = successful connection.

noteType:
 * Node.ELEMENT_NODE == 1
 * Node.ATTRIBUTE_NODE == 2
 * Node.TEXT_NODE == 3
 * Node.CDATA_SECTION_NODE == 4
 * Node.ENTITY_REFERENCE_NODE == 5
 * Node.ENTITY_NODE == 6
 * Node.PROCESSING_INSTRUCTION_NODE == 7
 * Node.COMMENT_NODE == 8
 * Node.DOCUMENT_NODE == 9
 * Node.DOCUMENT_TYPE_NODE == 10
 * Node.DOCUMENT_FRAGMENT_NODE == 11
 * Node.NOTATION_NODE == 12 
 So we'll better ignore noteType 3 (tabs, spaces and newlines) and 8 (comments).

Clear all childs inside an element:
while (parent.hasChildNodes()) { parent.removeChild(parent.firstChild); }
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
	b=document.body;
	b.removeChild(b.firstChild);
	
	d=document.createElement('div');
	d.id='login';
	b.appendChild(d);
<?php
if (in_array('login',$user['permission'])) {
	echo "\tdologin('success');\n";
} else {
	echo "\tdologout('success');\n";
}
?>
	
	d=document.createElement('div');
	d.id='threads';
	b.appendChild(d);
	thread();
	//setInterval('thread()',1000);
}

function thread() {
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//We can't use : here, the http_request will believe we are trying to access
	//another site (thread://), we must use the hex format instead (%3a)
	http_request.open('GET','thread%3a1',false);
	http_request.send(null);
	
	if (http_request.status == 200) {
		xml=http_request.responseXML;
		everything=xml.childNodes[1];
		if (everything.hasChildNodes()) {
			c=document.getElementById('threads');
			while (c.hasChildNodes()) { c.removeChild(c.firstChild); }
			threads=everything.childNodes;
			for (i=0; i < threads.length; i++) {
				if (threads[i].nodeType != 3 && threads[i].nodeType != 8) { 
					t=document.createElement('div');
					t.id='thread'+threads[i].getAttribute('id');
					t.className='thread';
					
					posts=threads[i].childNodes;
					for (i2=0; i2 < posts.length; i2++) {
						if (posts[i2].nodeType != 3 && posts[i2].nodeType != 8) { 
							p=document.createElement('div');
							p.id='thread'+threads[i].getAttribute('id')+
							     'post'+posts[i2].getAttribute('id');
							p.className='post';
							
							id=posts[i2].getAttribute('id');
							author=posts[i2].getElementsByTagName('author')[0].textContent;
							date=posts[i2].getElementsByTagName('date')[0].textContent;
							subject=posts[i2].getElementsByTagName('subject')[0].textContent;
							body=posts[i2].getElementsByTagName('body')[0].textContent;
							
							d=document.createElement('div');
							d.className='author';
							a=document.createElement('a');
							a.onclick=function(){ user(author); }
							a.appendChild(document.createTextNode(author));
							d.appendChild(a);
							p.appendChild(d);
							
							d=document.createElement('div');
							d.className='body';
							o=document.createElement('div');
							o.className='topinfo';
							o2=document.createElement('div');
							o2.className='reply';
							o2.appendChild(document.createTextNode('Reply #'+id+' on '+date));
							o.appendChild(o2);
							o2=document.createElement('div');
							o2.className='subject';
							o2.appendChild(document.createTextNode(subject));
							o.appendChild(o2);
							d.appendChild(o);
							d.appendChild(document.createTextNode(body)); //.replace(/\n/g,document.createElement('br'))
							p.appendChild(d);
							
							t.appendChild(p);
						}
					}
					c.appendChild(t);
				}
			}
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}

function login() {
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	http_request.open('POST','login',false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'username='+encodeURI(document.getElementById('username').value)+
		'&password='+encodeURI(document.getElementById('password').value));
	
	if (http_request.status == 200) {
		dologin(http_request.responseText);
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}

function dologin(state) {
	d=document.getElementById('login');
	if (state == 'success') {
		while (d.hasChildNodes()) { d.removeChild(d.firstChild); }
		a=document.createElement('a');
		a.onclick=function(){ logout(); }
		a.appendChild(document.createTextNode('Logout'));
		d.appendChild(a);
		d.appendChild(document.createElement('br'));
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
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	http_request.open('GET','logout',false);
	http_request.send(null);
	
	if (http_request.status == 200) {
		dologout(http_request.responseText);
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
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
	else if (state == 'not logged in') { //Session timeout?
		if (!(e=document.getElementById('loginerror'))) {
			e=document.createElement('span');
			e.id='loginerror';
			d.appendChild(e);
		} else {
			e.removeChild(e.firstChild);
		}
		d.appendChild(document.createElement('br'));
		d.appendChild(document.createTextNode('You are not logged in'));
	}
}

/*Not used anymore*/
function readylistener(http_request,callbackfunction) {
	if (http_request.readyState == 4) {
		if (http_request.status == 200) {
			callbackfunction(http_request.responseText);
		} else {
			alert('There was a problem with the request:\n'+
			       http_request.statusText);
		}
	}
}
