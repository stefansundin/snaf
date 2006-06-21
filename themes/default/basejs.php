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
	b.appendChild(document.createElement('br'));
	
	d=document.createElement('div');
	d.id='fat';
	b.appendChild(d);
	forum(null);
	//setInterval('forum(null)',1000);
	
	b.appendChild(document.createElement('br'));
	
	d=document.createElement('div');
	d.id='thread';
	b.appendChild(d);
	//thread(1);
	//setInterval('thread()',1000);
	
	//Done loading
	document.title='SNAF';
}

function forum(forum_id) {
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	if (forum_id != null) {
		http_request.open('GET','hooks/forum.php?id='+forum_id,false);
	} else {
		http_request.open('GET','hooks/forum.php',false);
	}
	http_request.send(null);
	
	if (http_request.status == 200) {
		//alert(http_request.responseText);
		xml=http_request.responseXML;
		everything=xml.childNodes[1];
		if (everything.hasChildNodes()) {
			//Grab fat
			c=document.getElementById('fat');
			//Clear fat
			while (c.hasChildNodes()) { c.removeChild(c.firstChild); }
			
			//List forums
			xml_forums=everything.getElementsByTagName('forum');
			for (i=0; i < xml_forums.length; i++) {
				//Assign variables
				forum_id=xml_forums[i].getAttribute('forum_id');
				num_threads=xml_forums[i].getAttribute('num_threads');
				num_posts=xml_forums[i].getAttribute('num_posts');
				subject=xml_forums[i].getElementsByTagName('subject')[0].textContent;
				body=xml_forums[i].getElementsByTagName('body')[0].textContent;
				
				//Create forum
				f=document.createElement('div');
				f.id='forum'+forum_id;
				f.className='forum';
				c.appendChild(f);
				
				//Num
				d=document.createElement('div');
				d.className='num';
				f.appendChild(d);
				// Num_threads
				n=document.createElement('div');
				n.className='num_threads';
				n.appendChild(document.createTextNode(num_threads));
				d.appendChild(n);
				// Num_posts
				n=document.createElement('div');
				n.className='num_posts';
				n.appendChild(document.createTextNode(num_posts));
				d.appendChild(n);
				
				//Body
				d=document.createElement('div');
				d.className='body';
				// Subject
				s=document.createElement('a');
				s.onclick=function() { forum(forum_id); }
				s.appendChild(document.createTextNode(subject));
				d.appendChild(s);
				d.appendChild(document.createElement('br'));
				d.appendChild(document.createTextNode(body));
				f.appendChild(d);
			}
			
			//List threads
			xml_threads=everything.getElementsByTagName('thread');
			for (i=0; i < xml_threads.length; i++) {
				//Assign variables
				thread_id=xml_threads[i].getAttribute('thread_id');
				subject=xml_threads[i].getElementsByTagName('subject')[0].textContent;
				author=xml_threads[i].getElementsByTagName('author')[0].textContent;
				
				//Create thread
				f=document.createElement('div');
				f.id='thread'+thread_id;
				f.className='thread';
				c.appendChild(f);
				
				//Author
				d=document.createElement('div');
				d.className='author';
				s=document.createElement('a');
				//s.onclick=function() { user(thread_id); }
				s.appendChild(document.createTextNode(author));
				d.appendChild(s);
				f.appendChild(d);
				
				//Subject
				d=document.createElement('div');
				d.className='subject';
				s=document.createElement('a');
				s.onclick=function() { thread(thread_id); }
				s.appendChild(document.createTextNode(subject));
				d.appendChild(s);
				f.appendChild(d);
			}
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}

function thread(thread_id) {
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	http_request.open('GET','hooks/thread.php?id='+thread_id,false);
	http_request.send(null);
	
	if (http_request.status == 200) {
		xml=http_request.responseXML;
		everything=xml.childNodes[1];
		if (everything.hasChildNodes()) {
			//Grab thread
			c=document.getElementById('thread');
			//Clear thread
			while (c.hasChildNodes()) { c.removeChild(c.firstChild); }
			
			posts=everything.childNodes;
			for (i=0; i < posts.length; i++) {
				if (posts[i].nodeType != 3 && posts[i].nodeType != 8) { 
					//Assign variables
					post_id=posts[i].getAttribute('post_id');
					author=posts[i].getElementsByTagName('author')[0].textContent;
					date=posts[i].getElementsByTagName('date')[0].textContent;
					subject=posts[i].getElementsByTagName('subject')[0].textContent;
					body=posts[i].getElementsByTagName('body')[0].textContent;
					
					//Create post
					p=document.createElement('div');
					p.id='thread'+thread_id+'post'+post_id;
					p.className='post';
					c.appendChild(p);
					
					//Author
					d=document.createElement('div');
					d.className='author';
					a=document.createElement('a');
					a.onclick=function(){ user(author); }
					a.appendChild(document.createTextNode(author));
					d.appendChild(a);
					p.appendChild(d);
					
					//Body
					d=document.createElement('div');
					d.className='body';
					// Topinfo
					o=document.createElement('div');
					o.className='topinfo';
					o2=document.createElement('div');
					o2.className='reply';
					o2.appendChild(document.createTextNode('Reply #'+post_id+' on '+date));
					o.appendChild(o2);
					o2=document.createElement('div');
					o2.className='subject';
					o2.appendChild(document.createTextNode(subject));
					o.appendChild(o2);
					// Body continued
					d.appendChild(o);
					d.appendChild(document.createTextNode(body)); //.replace(/\n/g,document.createElement('br'))
					p.appendChild(d);
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
	
	http_request.open('POST','hooks/login.php',false);
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
	
	http_request.open('GET','hooks/logout.php',false);
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
