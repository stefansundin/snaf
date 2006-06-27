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

nodeType:
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
 So we'll better ignore nodeType 3 (tabs, spaces and newlines) and 8 (comments).

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
	var obj_body=document.body;
	//Remove "Your browser does not support JavaScript."
	obj_body.removeChild(obj_body.firstChild);
	
	//Create stuff
	// login
	var obj_login=document.createElement('div');
	obj_login.id='login';
	obj_body.appendChild(obj_login);
	obj_body.appendChild(document.createElement('br'));
	// fat
	var obj_fat=document.createElement('div');
	obj_fat.id='fat';
	obj_body.appendChild(obj_fat);
	// thread
	var obj_thread=document.createElement('div');
	obj_thread.id='thread';
	obj_body.appendChild(obj_thread);
	
	//Do stuff
	// login
<?php
if (in_array('login',$user['permission'])) {
	echo "\tdologin('success');\n";
} else {
	echo "\tdologout('success');\n";
}
?>
	// forum
	forum(null);
	// done
	document.title='SNAF';
}

function closurecallback(callbackFunc,callbackParam) {
	return function() {
		callbackFunc(callbackParam);
	}
}

function user(username) { }

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
		var xml=http_request.responseXML;
		
		//Grab and clear thread
		var obj_thread=document.getElementById('thread');
		while (obj_thread.hasChildNodes()) { obj_thread.removeChild(obj_thread.firstChild); }
		
		//Grab fat
		var obj_fat=document.getElementById('fat');
		//Clear fat
		while (obj_fat.hasChildNodes()) { obj_fat.removeChild(obj_fat.firstChild); }
		
		//Back link
		if (forum_id != null) {
			obj_back=document.createElement('a');
			obj_back.onclick=function() { forum(null); }
			obj_back.appendChild(document.createTextNode('« back'));
			obj_fat.appendChild(obj_back);
		}
		
		//List forums
		var xml_forums=xml.getElementsByTagName('forum');
		for (var i=0; i < xml_forums.length; i++) {
			//Assign variables
			var xml_forum_id=xml_forums[i].getAttribute('forum_id');
			var xml_num_threads=xml_forums[i].getAttribute('num_threads');
			var xml_num_posts=xml_forums[i].getAttribute('num_posts');
			var xml_subject=xml_forums[i].getElementsByTagName('subject')[0].textContent;
			var xml_body=xml_forums[i].getElementsByTagName('body')[0].textContent;
			
			//Create forum
			var obj_forum=document.createElement('div');
			obj_forum.id='forum'+xml_forum_id;
			obj_forum.className='forum';
			obj_fat.appendChild(obj_forum);
			
			//Num
			var obj_num=document.createElement('div');
			obj_num.className='num';
			obj_forum.appendChild(obj_num);
			// Num_threads
			var obj_num_threads=document.createElement('div');
			obj_num_threads.className='num_threads';
			obj_num_threads.appendChild(document.createTextNode(xml_num_threads));
			obj_num.appendChild(obj_num_threads);
			// Num_posts
			var obj_num_posts=document.createElement('div');
			obj_num_posts.className='num_posts';
			obj_num_posts.appendChild(document.createTextNode(xml_num_posts));
			obj_num.appendChild(obj_num_posts);
			
			//Body
			var obj_body=document.createElement('div');
			obj_body.className='body';
			// Subject
			var obj_subject=document.createElement('a');
			obj_subject.onclick=closurecallback(forum,xml_forum_id);
			obj_subject.appendChild(document.createTextNode(xml_subject));
			obj_body.appendChild(obj_subject);
			obj_body.appendChild(document.createElement('br'));
			obj_body.appendChild(document.createTextNode(xml_body));
			obj_forum.appendChild(obj_body);
		}
		
		//List threads
		var xml_threads=xml.getElementsByTagName('thread');
		for (var i=0; i < xml_threads.length; i++) {
			//Assign variables
			var xml_thread_id=xml_threads[i].getAttribute('thread_id');
			var xml_subject=xml_threads[i].getElementsByTagName('subject')[0].textContent;
			var xml_author=xml_threads[i].getElementsByTagName('author')[0].textContent;
			
			//Create thread
			var obj_thread=document.createElement('div');
			obj_thread.id='thread'+xml_thread_id;
			obj_thread.className='thread';
			obj_fat.appendChild(obj_thread);
			
			//Author
			var obj_author=document.createElement('a');
			obj_author.className='author';
			obj_author.appendChild(document.createTextNode(xml_author));
			obj_thread.appendChild(obj_author);
			
			//Subject
			var obj_subject=document.createElement('a');
			obj_subject.className='subject';
			obj_subject.onclick=closurecallback(thread,xml_thread_id);
			obj_subject.appendChild(document.createTextNode(xml_subject));
			obj_thread.appendChild(obj_subject);
		}
		
		//If no forums or threads was reported, print message
		if (xml_forums.length == 0 && xml_threads.length == 0) {
			obj_fat.appendChild(document.createElement('br'));
			obj_fat.appendChild(document.createTextNode('No forums or threads found (and yes, I checked the closet).'));
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
		var xml=http_request.responseXML;
		
		//Grab and clear fat
		var obj_fat=document.getElementById('fat');
		while (obj_fat.hasChildNodes()) { obj_fat.removeChild(obj_fat.firstChild); }
		
		//Grab thread
		var obj_thread=document.getElementById('thread');
		//Clear thread
		while (obj_thread.hasChildNodes()) { obj_thread.removeChild(obj_thread.firstChild); }
		
		//Back link
		var obj_back=document.createElement('a');
		obj_back.onclick=function() { forum(1); }
		obj_back.appendChild(document.createTextNode('« back'));
		obj_thread.appendChild(obj_back);
		obj_thread.appendChild(document.createTextNode(' | '));
		//Refresh link
		var obj_refresh=document.createElement('a');
		obj_refresh.onclick=function() { thread(thread_id); }
		obj_refresh.appendChild(document.createTextNode('Refresh'));
		obj_thread.appendChild(obj_refresh);
		var xml_posts=xml.getElementsByTagName('post');
		for (var i=0; i < xml_posts.length; i++) {
			//Assign variables
			var xml_post_id=xml_posts[i].getAttribute('post_id');
			var xml_author=xml_posts[i].getElementsByTagName('author')[0].textContent;
			var xml_date=xml_posts[i].getElementsByTagName('date')[0].textContent;
			var xml_subject=xml_posts[i].getElementsByTagName('subject')[0].textContent;
			var xml_body=xml_posts[i].getElementsByTagName('body')[0].textContent;
			
			//Create post
			var obj_post=document.createElement('div');
			obj_post.id='thread'+thread_id+'post'+xml_post_id;
			obj_post.className='post';
			obj_thread.appendChild(obj_post);
			
			//Author
			var obj_author=document.createElement('div');
			obj_author.className='author';
			obj_author_link=document.createElement('a');
			obj_author_link.onclick=closurecallback(user,xml_author);
			obj_author_link.appendChild(document.createTextNode(xml_author));
			obj_author.appendChild(obj_author_link);
			obj_post.appendChild(obj_author);
			
			//Body
			var obj_body=document.createElement('div');
			obj_body.className='body';
			obj_post.appendChild(obj_body);
			// Topinfo
			var obj_topinfo=document.createElement('div');
			obj_topinfo.className='topinfo';
			//  Reply
			var obj_topinfo_reply=document.createElement('div');
			obj_topinfo_reply.className='reply';
			obj_topinfo_reply.appendChild(document.createTextNode('Reply #'+xml_post_id+' on '+xml_date));
			obj_topinfo.appendChild(obj_topinfo_reply);
			//  Subject
			var obj_topinfo_subject=document.createElement('div');
			obj_topinfo_subject.className='subject';
			obj_topinfo_subject.appendChild(document.createTextNode(xml_subject));
			obj_topinfo.appendChild(obj_topinfo_subject);
			// Body continued
			obj_body.appendChild(obj_topinfo);
			obj_body.appendChild(document.createTextNode(xml_body)); //.replace(/\n/g,document.createElement('br'))
		}
		
		//Create reply form
		var obj_reply=document.createElement('form');
		obj_reply.onsubmit=function() { submitthread(); return false; }
		obj_reply.id='reply';
		obj_thread.appendChild(obj_reply);
		// thread_id
		var obj_thread_id=document.createElement('input');
		obj_thread_id.type='hidden';
		obj_thread_id.name='thread_id';
		obj_thread_id.id='thread_id';
		obj_thread_id.value=thread_id;
		obj_reply.appendChild(obj_thread_id);
		// Subject
		obj_reply.appendChild(document.createTextNode('Subject:'));
		obj_reply.appendChild(document.createElement('br'));
		var obj_subject=document.createElement('input');
		obj_subject.type='text';
		obj_subject.name='subject';
		obj_subject.id='subject';
		obj_subject.value='Re: '+xml_posts[0].getElementsByTagName('subject')[0].textContent;
		obj_reply.appendChild(obj_subject);
		obj_reply.appendChild(document.createElement('br'));
		// Body
		obj_reply.appendChild(document.createTextNode('Body:'));
		obj_reply.appendChild(document.createElement('br'));
		var obj_body=document.createElement('textarea');
		obj_body.name='body';
		obj_body.id='body';
		obj_reply.appendChild(obj_body);
		obj_reply.appendChild(document.createElement('br'));
		// Submit
		var obj_submit=document.createElement('input');
		obj_submit.type='submit';
		obj_submit.id='replysubmit';
		obj_submit.value='Reply';
		obj_reply.appendChild(obj_submit);
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}

function submitthread() {
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//Grab objects
	var obj_thread_id=document.getElementById('thread_id');
	var obj_subject=document.getElementById('subject');
	var obj_body=document.getElementById('body');
	var obj_replysubmit=document.getElementById('replysubmit');
	
	//Disable input boxes
	obj_subject.disabled=true;
	obj_body.disabled=true;
	obj_replysubmit.disabled=true;
	
	//Make the request
	http_request.open('POST','hooks/submitthread.php?thread_id='+obj_thread_id.value,false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'subject='+encodeURI(obj_subject.value)+
		'&body='+encodeURI(obj_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			//Refresh thread
			thread(obj_thread_id.value);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
		//Enable input boxes
		obj_subject.disabled=false;
		obj_body.disabled=false;
		obj_replysubmit.disabled=false;
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
	
	//Grab objects
	var obj_username=document.getElementById('username');
	var obj_password=document.getElementById('password');
	var obj_loginsubmit=document.getElementById('loginsubmit');
	
	//Disable input boxes
	obj_username.disabled=true;
	obj_password.disabled=true;
	obj_loginsubmit.disabled=true;
	
	//Make the request
	http_request.open('POST','hooks/login.php',false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'username='+encodeURI(obj_username.value)+
		'&password='+encodeURI(obj_password.value));
	
	//What happened?
	if (http_request.status == 200) {
		dologin(http_request.responseText);
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
		//Enable input boxes
		obj_username.disabled=false;
		obj_password.disabled=false;
		obj_loginsubmit.disabled=false;
	}
}

function dologin(state) {
	var obj_login=document.getElementById('login');
	if (state == 'success') {
		//Clear obj_login
		while (obj_login.hasChildNodes()) { obj_login.removeChild(obj_login.firstChild); }
		//Apply a logout link
		var obj_logout=document.createElement('a');
		obj_logout.id='logout';
		obj_logout.onclick=function(){ logout(); }
		obj_logout.appendChild(document.createTextNode('Logout'));
		obj_login.appendChild(obj_logout);
		obj_login.appendChild(document.createElement('br'));
	}
	else if (state == 'no credentials'
	      || state == 'one credential'
	      || state == 'invalid credentials') {
		//Enable input boxes
		// Grab objects
		var obj_username=document.getElementById('username');
		var obj_password=document.getElementById('password');
		var obj_loginsubmit=document.getElementById('loginsubmit');
		// Enable
		obj_username.disabled=false;
		obj_password.disabled=false;
		obj_loginsubmit.disabled=false;
		//Print error message
		var obj_loginerror=document.getElementById('loginerror');
		if (!obj_loginerror) {
			var obj_loginerror=document.createElement('span');
			obj_loginerror.id='loginerror';
			obj_login.appendChild(obj_loginerror);
		} else {
			obj_loginerror.removeChild(obj_loginerror.firstChild);
		}
		if (state == 'invalid credentials') {
			obj_loginerror.appendChild(document.createTextNode('Invalid credentials'));
		} else {
			obj_loginerror.appendChild(document.createTextNode('Supply all credentials'));
		}
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
	var obj_login=document.getElementById('login');
	if (state == 'success') {
		//Clear obj_login
		while (obj_login.hasChildNodes()) { obj_login.removeChild(obj_login.firstChild); }
		//Create form
		var obj_loginform=document.createElement('form');
		obj_loginform.method='post';
		obj_loginform.onsubmit=function(){ login(); return false; }
		
		// Username
		obj_loginform.appendChild(document.createTextNode('Username: '));
		var obj_username=document.createElement('input');
		obj_username.type='text';
		obj_username.name='username';
		obj_username.id='username';
		obj_loginform.appendChild(obj_username);
		obj_loginform.appendChild(document.createElement('br'));
		
		// Password
		obj_loginform.appendChild(document.createTextNode('Password: '));
		var obj_password=document.createElement('input');
		obj_password.type='password';
		obj_password.name='password';
		obj_password.id='password';
		obj_loginform.appendChild(obj_password);
		obj_loginform.appendChild(document.createElement('br'));
		
		// Submit
		var obj_loginsubmit=document.createElement('input');
		obj_loginsubmit.type='submit';
		obj_loginsubmit.id='loginsubmit';
		obj_loginsubmit.value='Login';
		obj_loginform.appendChild(obj_loginsubmit);
		
		obj_login.appendChild(obj_loginform);
	}
	else if (state == 'not logged in') { //Session timeout?
		//Print error message
		var obj_loginerror=document.getElementById('loginerror');
		if (!obj_loginerror) {
			var obj_loginerror=document.createElement('span');
			obj_loginerror.id='loginerror';
			obj_login.appendChild(obj_loginerror);
		} else {
			obj_loginerror.removeChild(obj_loginerror.firstChild);
		}
		obj_loginerror.appendChild(document.createTextNode('You are not logged in'));
	}
}
