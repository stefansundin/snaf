/*
SNAF — Default — Base JavaScript
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
This file contain the JavaScript which the entire site depends on.

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
 So we'll better ignore nodeType 3 (text, whitespaces) and 8 (comments).

Clear all childs inside an element:
while (element.hasChildNodes()) { element.removeChild(element.firstChild); }
*/

function load() {
	//Grab body
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
	
	//Do stuff
	// login
	if (snaf.login) {
		dologin();
	} else {
		dologout();
	}
	// location
	snaf.current.location(snaf.current.locationid);
	// done
	document.title='SNAF';
	snaf.loading=false;
}

function closurecallback(callbackFunc,callbackParam) {
	return function() {
		callbackFunc(callbackParam);
	}
}

function back() {
	if (snaf.back.length > 0) {
		var nextback=snaf.back.pop();
		snaf.current=null;
		nextback.location(nextback.locationid);
	} else {
		forum(0);
	}
}

function refreshuser() {
	if (snaf.current.location == forum) {
		if (snaf.login) {
			//Enable submit forum form
			var obj_subject=document.getElementById('submitforum_subject');
			var obj_body=document.getElementById('submitforum_body');
			var obj_submit=document.getElementById('submitforum_submit');
			obj_subject.disabled=false;
			obj_body.disabled=false;
			obj_submit.disabled=false;
			
			//Enable submit thread form
			var obj_subject=document.getElementById('submitthread_subject');
			var obj_body=document.getElementById('submitthread_body');
			var obj_submit=document.getElementById('submitthread_submit');
			obj_subject.disabled=false;
			obj_body.disabled=false;
			obj_submit.disabled=false;
		} else {
			//Disable submit forum form
			var obj_subject=document.getElementById('submitforum_subject');
			var obj_body=document.getElementById('submitforum_body');
			var obj_submit=document.getElementById('submitforum_submit');
			obj_subject.disabled=true;
			obj_body.disabled=true;
			obj_submit.disabled=true;
			
			//Disable submit thread form
			var obj_subject=document.getElementById('submitthread_subject');
			var obj_body=document.getElementById('submitthread_body');
			var obj_submit=document.getElementById('submitthread_submit');
			obj_subject.disabled=true;
			obj_body.disabled=true;
			obj_submit.disabled=true;
		}
	}
	else if (snaf.current.location == thread) {
		if (snaf.login) {
			//Enable submit reply form
			var obj_subject=document.getElementById('submitreply_subject');
			var obj_body=document.getElementById('submitreply_body');
			var obj_submit=document.getElementById('submitreply_submit');
			obj_subject.disabled=false;
			obj_body.disabled=false;
			obj_submit.disabled=false;
		} else {
			//Disable submit reply form
			var obj_subject=document.getElementById('submitreply_subject');
			var obj_body=document.getElementById('submitreply_body');
			var obj_submit=document.getElementById('submitreply_submit');
			obj_subject.disabled=true;
			obj_body.disabled=true;
			obj_submit.disabled=true;
		}
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
	
	http_request.open('GET','hooks/forum.php?forum_id='+forum_id,false);
	http_request.send(null);
	
	if (http_request.status == 200) {
		//Update global variables
		if (snaf.current != null //invoked through back()
		 && !snaf.loading) { //or loading
			snaf.back.push(snaf.current);
		}
		snaf.current={
		 'location':forum,
		 'locationid':forum_id };
		
		//alert(http_request.responseText);
		var xml=http_request.responseXML;
		
		//Grab fat
		var obj_fat=document.getElementById('fat');
		//Clear fat
		while (obj_fat.hasChildNodes()) {
			obj_fat.removeChild(obj_fat.firstChild); }
		
		//Back link
		if (forum_id != 0) {
			obj_back=document.createElement('a');
			obj_back.onclick=function() { back(); }
			obj_back.appendChild(document.createTextNode('« back'));
			obj_fat.appendChild(obj_back);
			obj_fat.appendChild(document.createTextNode(' | '));
		}
		
		//Refresh link
		var obj_refresh=document.createElement('a');
		obj_refresh.onclick=function() {
			snaf.current=null; //Do not index in back history
			forum(forum_id); //Refresh thread
		}
		obj_refresh.appendChild(document.createTextNode('Refresh'));
		obj_fat.appendChild(obj_refresh);
		
		
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
			obj_num_threads.appendChild(document.createTextNode(xml_num_threads+' thread'+(xml_num_threads!=1?'s':'')));
			obj_num.appendChild(obj_num_threads);
			// Num_posts
			var obj_num_posts=document.createElement('div');
			obj_num_posts.className='num_posts';
			obj_num_posts.appendChild(document.createTextNode(xml_num_posts+' post'+(xml_num_posts!=1?'s':'')));
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
			obj_fat.appendChild(document.createTextNode('No forums or threads found.'));
		}
		
		//Create submit forum form
		var obj_reply=document.createElement('form');
		obj_reply.onsubmit=function() { submitforum(); return false; }
		obj_reply.id='submitforum_form';
		obj_reply.acceptCharset='utf-8';
		obj_fat.appendChild(obj_reply);
		// thread_id
		var obj_forum_id=document.createElement('input');
		obj_forum_id.type='hidden';
		obj_forum_id.id='submitforum_forum_id';
		obj_forum_id.value=forum_id;
		obj_fat.appendChild(obj_forum_id);
		// Subject
		obj_reply.appendChild(document.createTextNode('Subject:'));
		obj_reply.appendChild(document.createElement('br'));
		var obj_subject=document.createElement('input');
		obj_subject.type='text';
		obj_subject.id='submitforum_subject';
		if (!snaf.login) {
			obj_subject.disabled=true; }
		obj_reply.appendChild(obj_subject);
		obj_reply.appendChild(document.createElement('br'));
		// Body
		obj_reply.appendChild(document.createTextNode('Body:'));
		obj_reply.appendChild(document.createElement('br'));
		var obj_body=document.createElement('textarea');
		obj_body.id='submitforum_body';
		if (!snaf.login) {
			obj_body.disabled=true; }
		obj_reply.appendChild(obj_body);
		obj_reply.appendChild(document.createElement('br'));
		// Submit
		var obj_submit=document.createElement('input');
		obj_submit.type='submit';
		obj_submit.id='submitforum_submit';
		obj_submit.value='New forum';
		if (!snaf.login) {
			obj_submit.disabled=true; }
		obj_reply.appendChild(obj_submit);
		
		//Create submit thread form
		var obj_reply=document.createElement('form');
		obj_reply.onsubmit=function() { submitthread(); return false; }
		obj_reply.id='submitthread_form';
		obj_reply.acceptCharset='utf-8';
		obj_fat.appendChild(obj_reply);
		// thread_id
		var obj_forum_id=document.createElement('input');
		obj_forum_id.type='hidden';
		obj_forum_id.id='submitthread_forum_id';
		obj_forum_id.value=forum_id;
		obj_fat.appendChild(obj_forum_id);
		// Subject
		obj_reply.appendChild(document.createTextNode('Subject:'));
		obj_reply.appendChild(document.createElement('br'));
		var obj_subject=document.createElement('input');
		obj_subject.type='text';
		obj_subject.id='submitthread_subject';
		if (!snaf.login) {
			obj_subject.disabled=true; }
		obj_reply.appendChild(obj_subject);
		obj_reply.appendChild(document.createElement('br'));
		// Body
		obj_reply.appendChild(document.createTextNode('Body:'));
		obj_reply.appendChild(document.createElement('br'));
		var obj_body=document.createElement('textarea');
		obj_body.id='submitthread_body';
		if (!snaf.login) {
			obj_body.disabled=true; }
		obj_reply.appendChild(obj_body);
		obj_reply.appendChild(document.createElement('br'));
		// Submit
		var obj_submit=document.createElement('input');
		obj_submit.type='submit';
		obj_submit.id='submitthread_submit';
		obj_submit.value='New thread';
		if (!snaf.login) {
			obj_submit.disabled=true; }
		obj_reply.appendChild(obj_submit);
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
	
	http_request.open('GET','hooks/thread.php?thread_id='+thread_id,false);
	http_request.send(null);
	
	if (http_request.status == 200) {
		//Update global variables
		if (snaf.current != null //if not invoked through back()
		 && !snaf.loading) { //and not loading
			snaf.back.push(snaf.current);
		}
		snaf.current={
		 'location':thread,
		 'locationid':thread_id };
		
		var xml=http_request.responseXML;
		
		//Grab fat
		var obj_fat=document.getElementById('fat');
		//Clear fat
		while (obj_fat.hasChildNodes()) { obj_fat.removeChild(obj_fat.firstChild); }
		
		//Back link
		var obj_back=document.createElement('a');
		obj_back.onclick=function() { back(); }
		obj_back.appendChild(document.createTextNode('« back'));
		obj_fat.appendChild(obj_back);
		obj_fat.appendChild(document.createTextNode(' | '));
		
		//Refresh link
		var obj_refresh=document.createElement('a');
		obj_refresh.onclick=function() {
			snaf.current=null; //Do not index in back history
			thread(thread_id); //Refresh thread
		}
		obj_refresh.appendChild(document.createTextNode('Refresh'));
		obj_fat.appendChild(obj_refresh);
		
		var xml_posts=xml.getElementsByTagName('post');
		if (xml_posts.length != 0) {
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
				obj_fat.appendChild(obj_post);
				
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
			obj_reply.onsubmit=function() { submitreply(); return false; }
			obj_reply.id='submitreply_form';
			obj_reply.acceptCharset='utf-8';
			obj_fat.appendChild(obj_reply);
			// thread_id
			var obj_thread_id=document.createElement('input');
			obj_thread_id.type='hidden';
			obj_thread_id.id='submitreply_thread_id';
			obj_thread_id.value=thread_id;
			obj_reply.appendChild(obj_thread_id);
			// Subject
			obj_reply.appendChild(document.createTextNode('Subject:'));
			obj_reply.appendChild(document.createElement('br'));
			var obj_subject=document.createElement('input');
			obj_subject.type='text';
			obj_subject.id='submitreply_subject';
			obj_subject.value='Re: '+xml_posts[0].getElementsByTagName('subject')[0].textContent;
			obj_reply.appendChild(obj_subject);
			obj_reply.appendChild(document.createElement('br'));
			// Body
			obj_reply.appendChild(document.createTextNode('Body:'));
			obj_reply.appendChild(document.createElement('br'));
			var obj_body=document.createElement('textarea');
			obj_body.id='submitreply_body';
			obj_reply.appendChild(obj_body);
			obj_reply.appendChild(document.createElement('br'));
			// Submit
			var obj_submit=document.createElement('input');
			obj_submit.type='submit';
			obj_submit.id='submitreply_submit';
			obj_submit.value='Reply';
			obj_reply.appendChild(obj_submit);
		} else {
			obj_fat.appendChild(document.createElement('br'));
			obj_fat.appendChild(document.createTextNode('Thread not found'));
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}

function submitforum() {
	if (!snaf.login) {
		alert('Noob! Login required!');
		return false;
	}
	
	//Grab objects
	var obj_forum_id=document.getElementById('submitforum_forum_id');
	var obj_subject=document.getElementById('submitforum_subject');
	var obj_body=document.getElementById('submitforum_body');
	var obj_submit=document.getElementById('submitforum_submit');
	
	//Disable input boxes
	obj_subject.disabled=true;
	obj_body.disabled=true;
	obj_submit.disabled=true;

	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//Make the request
	http_request.open('POST','hooks/submitforum.php?forum_id='+obj_forum_id.value,false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'subject='+encodeURI(obj_subject.value)+
		'&body='+encodeURI(obj_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			snaf.current=null; //Do not index in back history
			forum(obj_forum_id.value); //Refresh forum
		}
		else {
			alert('Error: '+state);
			//Enble input boxes
			obj_subject.disabled=false;
			obj_body.disabled=false;
			obj_submit.disabled=false;
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
		//Enable input boxes
		obj_subject.disabled=false;
		obj_body.disabled=false;
		obj_submit.disabled=false;
	}
}

function submitthread() {
	if (!snaf.login) {
		alert('Noob! Login required!');
		return false;
	}
	
	//Grab objects
	var obj_forum_id=document.getElementById('submitthread_forum_id');
	var obj_subject=document.getElementById('submitthread_subject');
	var obj_body=document.getElementById('submitthread_body');
	var obj_submit=document.getElementById('submitthread_submit');
	
	//Disable input boxes
	obj_subject.disabled=true;
	obj_body.disabled=true;
	obj_submit.disabled=true;

	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//Make the request
	http_request.open('POST','hooks/submitthread.php?forum_id='+obj_forum_id.value,false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'subject='+encodeURI(obj_subject.value)+
		'&body='+encodeURI(obj_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			snaf.current=null; //Do not index in back history
			forum(obj_forum_id.value); //Refresh forum
		}
		else {
			alert('Error: '+state);
			//Enable input boxes
			obj_subject.disabled=false;
			obj_body.disabled=false;
			obj_submit.disabled=false;
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
		//Enable input boxes
		obj_subject.disabled=false;
		obj_body.disabled=false;
		obj_submit.disabled=false;
	}
}

function submitreply() {
	if (!snaf.login) {
		alert('Noob! Login required!');
		return false;
	}

	//Grab objects
	var obj_thread_id=document.getElementById('submitreply_thread_id');
	var obj_subject=document.getElementById('submitreply_subject');
	var obj_body=document.getElementById('submitreply_body');
	var obj_submit=document.getElementById('submitreply_submit');
	
	//Disable input boxes
	obj_subject.disabled=true;
	obj_body.disabled=true;
	obj_submit.disabled=true;

	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//Make the request
	http_request.open('POST','hooks/submitreply.php?thread_id='+obj_thread_id.value,false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'subject='+encodeURI(obj_subject.value)+
		'&body='+encodeURI(obj_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			snaf.current=null; //Do not index in back history
			thread(obj_thread_id.value); //Refresh thread
		}
		else {
			alert('Error: '+state);
			//Enable input boxes
			obj_subject.disabled=false;
			obj_body.disabled=false;
			obj_submit.disabled=false;
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
		//Enable input boxes
		obj_subject.disabled=false;
		obj_body.disabled=false;
		obj_submit.disabled=false;
	}
}

function login() {
	//Grab objects
	var obj_username=document.getElementById('login_username');
	var obj_password=document.getElementById('login_password');
	var obj_login_submit=document.getElementById('login_submit');
	var obj_login_error=document.getElementById('login_error');
	
	//Make sure we got all the needed input
	if (!obj_username.value) {
		while (obj_login_error.hasChildNodes()) {
			obj_login_error.removeChild(obj_login_error.firstChild); }
		obj_login_error.appendChild(document.createTextNode('You must provide a username'));
		return false;
	}
	if (!obj_password.value) {
		while (obj_login_error.hasChildNodes()) {
			obj_login_error.removeChild(obj_login_error.firstChild); }
		obj_login_error.appendChild(document.createTextNode('You must provide a password'));
		return false;
	}
	
	//Disable input boxes
	obj_username.disabled=true;
	obj_password.disabled=true;
	obj_login_submit.disabled=true;
	
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//Make the request
	http_request.open('POST','hooks/login.php',false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'username='+encodeURI(obj_username.value)+
		'&password='+encodeURI(obj_password.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			dologin();
		}
		else if (state == 'invalid credentials') {
			//Enable input boxes
			obj_username.disabled=false;
			obj_password.disabled=false;
			obj_login_submit.disabled=false;
			//Print error message
			while (obj_login_error.hasChildNodes()) {
				obj_login_error.removeChild(obj_login_error.firstChild); }
			obj_login_error.appendChild(document.createTextNode('Invalid credentials'));
		}
		else {
			alert('Error: '+state);
			//Enable input boxes
			obj_subject.disabled=false;
			obj_body.disabled=false;
			obj_submit.disabled=false;
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
		//Enable input boxes
		obj_username.disabled=false;
		obj_password.disabled=false;
		obj_login_submit.disabled=false;
	}
}

function httpauth() {
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//Make the request
	http_request.open('GET','hooks/login.php?httpauth',false);
	http_request.send(null);
	
	//Grab objects
	var obj_username=document.getElementById('login_username');
	var obj_password=document.getElementById('login_password');
	var obj_login_submit=document.getElementById('login_submit');
	
	//Disable input boxes
	obj_username.disabled=true;
	obj_password.disabled=true;
	obj_login_submit.disabled=true;
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			dologin();
		}
		else if (state == 'invalid credentials') {
			//Enable input boxes
			obj_username.disabled=false;
			obj_password.disabled=false;
			obj_login_submit.disabled=false;
			//Print error message
			var obj_login_error=document.getElementById('login_error');
			while (obj_login_error.hasChildNodes()) {
				obj_login_error.removeChild(obj_login_error.firstChild); }
			obj_login_error.appendChild(document.createTextNode('Invalid credentials'));
		}
		else {
			alert('Error: '+state);
			//Enable input boxes
			obj_subject.disabled=false;
			obj_body.disabled=false;
			obj_submit.disabled=false;
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
		//Enable input boxes
		obj_username.disabled=false;
		obj_password.disabled=false;
		obj_login_submit.disabled=false;
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
		var state=http_request.responseText;
		if (state == 'success') {
			dologout();
		}
		else {
			alert('Error: '+state);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}

function dologin() {
	var obj_login=document.getElementById('login');
	//Update global variable
	if (!snaf.loading) {
		snaf.login=true;
		refreshuser();
	}
	//Clear obj_login
	while (obj_login.hasChildNodes()) {
		obj_login.removeChild(obj_login.firstChild); }
	//Logout link
	var obj_logout=document.createElement('a');
	obj_logout.onclick=function(){ logout(); }
	obj_logout.appendChild(document.createTextNode('Logout'));
	obj_login.appendChild(obj_logout);
}

function dologout() {
	var obj_login=document.getElementById('login');
	//Update global variable
	if (!snaf.loading) {
		snaf.login=false;
		refreshuser();
	}
	//Clear obj_login
	while (obj_login.hasChildNodes()) {
		obj_login.removeChild(obj_login.firstChild); }
	
	//Create login form
	var obj_login_form=document.createElement('form');
	obj_login_form.method='post';
	obj_login_form.id='login_form';
	obj_login_form.onsubmit=function(){ login(); return false; }
	obj_login_form.acceptCharset='utf-8';
	obj_login.appendChild(obj_login_form);
	
	// Username
	obj_login_form.appendChild(document.createTextNode('Username: '));
	var obj_username=document.createElement('input');
	obj_username.type='text';
	obj_username.id='login_username';
	obj_username.onkeyup=function() {
		if (obj_username.value && obj_password.value) {
			obj_login_submit.disabled=false;
		} else {
			obj_login_submit.disabled=true;
		}
	}
	obj_login_form.appendChild(obj_username);
	obj_login_form.appendChild(document.createElement('br'));
	
	// Password
	obj_login_form.appendChild(document.createTextNode('Password: '));
	var obj_password=document.createElement('input');
	obj_password.type='password';
	obj_password.id='login_password';
	obj_password.onkeyup=function() {
		if (obj_username.value && obj_password.value) {
			obj_login_submit.disabled=false;
		} else {
			obj_login_submit.disabled=true;
		}
	}
	obj_login_form.appendChild(obj_password);
	obj_login_form.appendChild(document.createElement('br'));
	
	// Submit
	var obj_login_submit=document.createElement('input');
	obj_login_submit.type='submit';
	obj_login_submit.id='login_submit';
	obj_login_submit.value='Login';
	obj_login_submit.disabled=true;
	obj_login_form.appendChild(obj_login_submit);
	
	//HTTP_auth
	var obj_httpauth=document.createElement('a');
	obj_httpauth.onclick=function() { httpauth(); }
	obj_httpauth.appendChild(document.createTextNode('HTTP-Authenticate'));
	obj_login.appendChild(obj_httpauth);
	
	//Login_error
	var obj_login_error=document.createElement('div');
	obj_login_error.id='login_error';
	obj_login.appendChild(obj_login_error);
}
