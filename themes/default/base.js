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
while (element.hasChildNodes()) {
	element.removeChild(element.firstChild); }
*/

function load() {
	//Create a new body (effectively removes all childrens in the existing body)
	document.body=document.createElement('body');
	//Grab body
	var obj_body=document.body;
	
	//Create objects
	// userbar
	var obj_userbar=document.createElement('div');
	obj_userbar.id='userbar';
	obj_body.appendChild(obj_userbar);
	// loginbox
	var obj_box=document.createElement('div');
	obj_box.id='loginbox';
	obj_body.appendChild(obj_box);
	// submitaccountbox
	var obj_box=document.createElement('div');
	obj_box.id='submitaccountbox';
	obj_body.appendChild(obj_box);
	// fat
	var obj_fat=document.createElement('div');
	obj_fat.id='fat';
	obj_body.appendChild(obj_fat);
	
	//Display
	// refresh
	refresh();
	// location
	snaf.current.location(snaf.current.locationid);
	// done
	document.title='SNAF';
	snaf.loading=false;
}

function closurecallback(callbackFunc,callbackArg) {
	return function() {
		callbackFunc(callbackArg);
	}
}

function back() {
	if (snaf.back.length > 0) {
		var nextback=snaf.back.pop();
		snaf.dontindex=true; //Disable indexing
		nextback.location(nextback.locationid);
	} else {
		forum(0);
	}
}

function refresh() {
	if ((!snaf.display.login || snaf.loading) && snaf.user.login) {
		//Update global variable
		snaf.display.login=true;
		//Grab userbar
		var obj_userbar=document.getElementById('userbar');
		//Clear userbar
		while (obj_userbar.hasChildNodes()) {
			obj_userbar.removeChild(obj_userbar.firstChild); }
		//Add userbar items
		// ub_user
		var obj_ub_user=document.createElement('a');
		obj_ub_user.id='ub_user';
		obj_ub_user.onclick=function(){ user(snaf.user.username); }
		obj_ub_user.appendChild(document.createTextNode(snaf.user.username));
		obj_userbar.appendChild(obj_ub_user);
		// ub_pref
		var obj_ub_pref=document.createElement('a');
		obj_ub_pref.id='ub_pref';
		//obj_ub_pref.onclick=function(){ pref(); }
		obj_ub_pref.appendChild(document.createTextNode('preferences'));
		obj_userbar.appendChild(obj_ub_pref);
		// ub_logout
		var obj_ub_logout=document.createElement('a');
		obj_ub_logout.id='ub_logout';
		obj_ub_logout.onclick=function(){ logout(); }
		obj_ub_logout.appendChild(document.createTextNode('logout'));
		obj_userbar.appendChild(obj_ub_logout);
		
		//Update other things
		if (!snaf.loading) {
			if (snaf.display.loginbox) {
				display('hide','loginbox');
			}
			if (snaf.current.location == forum) {
				//Enable submit forum form
				var obj_submitforum_subject=document.getElementById('submitforum_subject');
				var obj_submitforum_body=document.getElementById('submitforum_body');
				var obj_submitforum_submit=document.getElementById('submitforum_submit');
				obj_submitforum_subject.disabled=false;
				obj_submitforum_body.disabled=false;
				if (obj_submitforum_subject.value && obj_submitforum_body.value) {
					obj_submitforum_submit.disabled=false;
				} else {
					obj_submitforum_submit.disabled=true;
				}
				
				//Enable submit thread form
				var obj_submitthread_subject=document.getElementById('submitthread_subject');
				var obj_submitthread_body=document.getElementById('submitthread_body');
				var obj_submitthread_submit=document.getElementById('submitthread_submit');
				obj_submitthread_subject.disabled=false;
				obj_submitthread_body.disabled=false;
				if (obj_submitthread_subject.value && obj_submitthread_body.value) {
					obj_submitthread_submit.disabled=false;
				} else {
					obj_submitthread_submit.disabled=true;
				}
			}
			else if (snaf.current.location == thread) {
				//Enable submit reply form
				var obj_submitreply_subject=document.getElementById('submitreply_subject');
				var obj_submitreply_body=document.getElementById('submitreply_body');
				var obj_submitreply_submit=document.getElementById('submitreply_submit');
				obj_submitreply_subject.disabled=false;
				obj_submitreply_body.disabled=false;
				if (obj_submitreply_subject.value && obj_submitreply_body.value) {
					obj_submitreply_submit.disabled=false;
				} else {
					obj_submitreply_submit.disabled=true;
				}
			}
		}
	}
	else if ((snaf.display.login || snaf.loading) && !snaf.user.login) {
		//Update global variable
		snaf.display.login=false;
		//Grab userbar
		var obj_userbar=document.getElementById('userbar');
		//Clear userbar
		while (obj_userbar.hasChildNodes()) {
			obj_userbar.removeChild(obj_userbar.firstChild); }
		//Add userbar items
		// ub_login
		var obj_ub_login=document.createElement('a');
		obj_ub_login.id='ub_login';
		obj_ub_login.onclick=function(){ display('toggle','loginbox'); }
		obj_ub_login.appendChild(document.createTextNode('login'));
		obj_userbar.appendChild(obj_ub_login);
		// ub_httpauth
		var obj_ub_httpauth=document.createElement('a');
		obj_ub_httpauth.id='ub_httpauth';
		obj_ub_httpauth.onclick=function() { httpauth(); }
		obj_ub_httpauth.appendChild(document.createTextNode('http-auth'));
		obj_ub_httpauth.title='Sign in through HTTP-Authentication';
		obj_userbar.appendChild(obj_ub_httpauth);
		// ub_submitaccount
		var obj_ub_login=document.createElement('a');
		obj_ub_login.id='ub_submitaccount';
		obj_ub_login.onclick=function(){ display('toggle','submitaccountbox'); }
		obj_ub_login.appendChild(document.createTextNode('create account'));
		obj_userbar.appendChild(obj_ub_login);
		
		//Update other things
		if (!snaf.loading) {
			if (snaf.current.location == forum) {
				//Disable submit forum form
				var obj_submitforum_subject=document.getElementById('submitforum_subject');
				var obj_submitforum_body=document.getElementById('submitforum_body');
				var obj_submitforum_submit=document.getElementById('submitforum_submit');
				obj_submitforum_subject.disabled=true;
				obj_submitforum_body.disabled=true;
				obj_submitforum_submit.disabled=true;
				
				//Disable submit thread form
				var obj_submitthread_subject=document.getElementById('submitthread_subject');
				var obj_submitthread_body=document.getElementById('submitthread_body');
				var obj_submitthread_submit=document.getElementById('submitthread_submit');
				obj_submitthread_subject.disabled=true;
				obj_submitthread_body.disabled=true;
				obj_submitthread_submit.disabled=true;
			}
			else if (snaf.current.location == thread) {
				//Disable submit reply form
				var obj_submitreply_subject=document.getElementById('submitreply_subject');
				var obj_submitreply_body=document.getElementById('submitreply_body');
				var obj_submitreply_submit=document.getElementById('submitreply_submit');
				obj_submitreply_subject.disabled=true;
				obj_submitreply_body.disabled=true;
				obj_submitreply_submit.disabled=true;
			}
		}
	}
}

function display(state,box) {
	if (box == 'all') {
		if (state == 'hide') {
			display('hide','loginbox');
			display('hide','submitaccountbox');
		}
	}
	else if (box == 'loginbox') {
		if (state == 'toggle') {
			if (snaf.display.loginbox) { display('hide','loginbox'); }
			else { display('show','loginbox'); }
		}
		else if (state == 'show') {
			//Hide other boxes
			display('hide','all');
			//Update global variable
			snaf.display.loginbox=true;
			//Grab loginbox
			var obj_loginbox=document.getElementById('loginbox');
			//Show loginbox
			obj_loginbox.style.display='block';
			
			//Create loginbox contents if not already present
			if (!obj_loginbox.hasChildNodes()) {
				//Create login form
				var obj_form=document.createElement('form');
				obj_form.method='post';
				obj_form.id='login_form';
				obj_form.onsubmit=function(){ login(); return false; }
				obj_form.acceptCharset='utf-8';
				obj_loginbox.appendChild(obj_form);
				
				// Username
				obj_form.appendChild(document.createTextNode('Username: '));
				var obj_username=document.createElement('input');
				obj_username.type='text';
				obj_username.id='login_username';
				obj_username.onkeyup=closurecallback(formsanitycheck,'login');
				obj_form.appendChild(obj_username);
				obj_form.appendChild(document.createElement('br'));
				
				// Password
				obj_form.appendChild(document.createTextNode('Password: '));
				var obj_password=document.createElement('input');
				obj_password.type='password';
				obj_password.id='login_password';
				obj_password.onkeyup=closurecallback(formsanitycheck,'login');
				obj_form.appendChild(obj_password);
				obj_form.appendChild(document.createElement('br'));
				
				// Submit
				var obj_submit=document.createElement('input');
				obj_submit.type='submit';
				obj_submit.id='login_submit';
				obj_submit.value='Sign in';
				obj_submit.disabled=true;
				obj_form.appendChild(obj_submit);
				
				//Error
				var obj_error=document.createElement('div');
				obj_error.id='login_error';
				obj_loginbox.appendChild(obj_error);
			}
			else {
				var obj_username=document.getElementById('login_username');
			}
			//Focus username box
			obj_username.focus();
		}
		else if (state == 'hide') {
			//Update global variable
			snaf.display.loginbox=false;
			//Grab loginbox
			var obj_loginbox=document.getElementById('loginbox');
			//Hide loginbox
			obj_loginbox.style.display='none';
		}
	}
	else if (box == 'submitaccountbox') {
		if (state == 'toggle') {
			if (snaf.display.submitaccountbox) {
				display('hide','submitaccountbox'); }
			else { display('show','submitaccountbox'); }
		}
		else if (state == 'show') {
			//Hide all boxes
			display('hide','all');
			//Update global variable
			snaf.display.submitaccountbox=true;
			//Grab box
			var obj_box=document.getElementById('submitaccountbox');
			//Show box
			obj_box.style.display='block';
			
			//Create submitaccountbox contents if not already present
			if (!obj_box.hasChildNodes()) {
				//Create submitaccountbox form
				var obj_form=document.createElement('form');
				obj_form.method='post';
				obj_form.id='submitaccount_form';
				obj_form.onsubmit=function(){ submitaccount(); return false; }
				obj_form.acceptCharset='utf-8';
				obj_box.appendChild(obj_form);
				
				// Username
				obj_form.appendChild(document.createTextNode('Username: '));
				var obj_username=document.createElement('input');
				obj_username.type='text';
				obj_username.id='submitaccount_username';
				obj_username.onkeyup=closurecallback(formsanitycheck,'submitaccount');
				obj_form.appendChild(obj_username);
				obj_form.appendChild(document.createElement('br'));
				
				// Password
				obj_form.appendChild(document.createTextNode('Password: '));
				var obj_password=document.createElement('input');
				obj_password.type='password';
				obj_password.id='submitaccount_password';
				obj_password.onkeyup=closurecallback(formsanitycheck,'submitaccount');
				obj_form.appendChild(obj_password);
				obj_form.appendChild(document.createElement('br'));
				
				// Confirm password
				obj_form.appendChild(document.createTextNode('Confirm password: '));
				var obj_confirmpassword=document.createElement('input');
				obj_confirmpassword.type='password';
				obj_confirmpassword.id='submitaccount_confirmpassword';
				obj_confirmpassword.onkeyup=closurecallback(formsanitycheck,'submitaccount');
				obj_form.appendChild(obj_confirmpassword);
				obj_form.appendChild(document.createElement('br'));
				
				// Email
				obj_form.appendChild(document.createTextNode('Email (optional): '));
				var obj_email=document.createElement('input');
				obj_email.type='text';
				obj_email.id='submitaccount_email';
				obj_email.onkeyup=closurecallback(formsanitycheck,'submitaccount');
				obj_form.appendChild(obj_email);
				obj_form.appendChild(document.createElement('br'));
				
				// Submit
				var obj_submit=document.createElement('input');
				obj_submit.type='submit';
				obj_submit.id='submitaccount_submit';
				obj_submit.value='Create account';
				obj_submit.disabled=true;
				obj_form.appendChild(obj_submit);
				
				//Error
				var obj_error=document.createElement('div');
				obj_error.id='submitaccount_error';
				obj_box.appendChild(obj_error);
			}
			else {
				var obj_username=document.getElementById('submitaccount_username');
			}
			//Focus username box
			obj_username.focus();
		}
		else if (state == 'hide') {
			//Update global variable
			snaf.display.submitaccountbox=false;
			//Grab box
			var obj_box=document.getElementById('submitaccountbox');
			//Hide box
			obj_box.style.display='none';
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
		//Do not add to back history if dontindex is set
		if (!snaf.dontindex) { snaf.back.push(snaf.current); }
		snaf.dontindex=false;
		snaf.current={
		 'location':forum,
		 'locationid':forum_id };
		
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
			snaf.dontindex=true; //Disable indexing
			forum(forum_id); //Refresh forum
		}
		obj_refresh.appendChild(document.createTextNode('Refresh'));
		obj_fat.appendChild(obj_refresh);
		
		var xml=http_request.responseXML;
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
		var obj_submitforum_form=document.createElement('form');
		obj_submitforum_form.onsubmit=function() { submitforum(); return false; }
		obj_submitforum_form.id='submitforum_form';
		obj_submitforum_form.acceptCharset='utf-8';
		obj_fat.appendChild(obj_submitforum_form);
		// Subject
		obj_submitforum_form.appendChild(document.createTextNode('Subject:'));
		obj_submitforum_form.appendChild(document.createElement('br'));
		var obj_submitforum_subject=document.createElement('input');
		obj_submitforum_subject.type='text';
		obj_submitforum_subject.id='submitforum_subject';
		if (!snaf.user.login) {
			obj_submitforum_subject.disabled=true; }
		obj_submitforum_subject.onkeyup=function() {
			if (obj_submitforum_subject.value && obj_submitforum_body.value) {
				obj_submitforum_submit.disabled=false;
			} else {
				obj_submitforum_submit.disabled=true;
			}
		}
		obj_submitforum_form.appendChild(obj_submitforum_subject);
		obj_submitforum_form.appendChild(document.createElement('br'));
		// Body
		obj_submitforum_form.appendChild(document.createTextNode('Body:'));
		obj_submitforum_form.appendChild(document.createElement('br'));
		var obj_submitforum_body=document.createElement('textarea');
		obj_submitforum_body.id='submitforum_body';
		if (!snaf.user.login) {
			obj_submitforum_body.disabled=true; }
		obj_submitforum_body.onkeyup=function() {
			if (obj_submitforum_subject.value && obj_submitforum_body.value) {
				obj_submitforum_submit.disabled=false;
			} else {
				obj_submitforum_submit.disabled=true;
			}
		}
		obj_submitforum_form.appendChild(obj_submitforum_body);
		obj_submitforum_form.appendChild(document.createElement('br'));
		// Submit
		var obj_submitforum_submit=document.createElement('input');
		obj_submitforum_submit.type='submit';
		obj_submitforum_submit.id='submitforum_submit';
		obj_submitforum_submit.value='New forum';
		obj_submitforum_submit.disabled=true;
		obj_submitforum_form.appendChild(obj_submitforum_submit);
		
		//Create submit thread form
		var obj_submitthread_form=document.createElement('form');
		obj_submitthread_form.onsubmit=function() { submitthread(); return false; }
		obj_submitthread_form.id='submitthread_form';
		obj_submitthread_form.acceptCharset='utf-8';
		obj_fat.appendChild(obj_submitthread_form);
		// Subject
		obj_submitthread_form.appendChild(document.createTextNode('Subject:'));
		obj_submitthread_form.appendChild(document.createElement('br'));
		var obj_submitthread_subject=document.createElement('input');
		obj_submitthread_subject.type='text';
		obj_submitthread_subject.id='submitthread_subject';
		if (!snaf.user.login) {
			obj_submitthread_subject.disabled=true; }
		obj_submitthread_subject.onkeyup=function() {
			if (obj_submitthread_subject.value && obj_submitthread_body.value) {
				obj_submitthread_submit.disabled=false;
			} else {
				obj_submitthread_submit.disabled=true;
			}
		}
		obj_submitthread_form.appendChild(obj_submitthread_subject);
		obj_submitthread_form.appendChild(document.createElement('br'));
		// Body
		obj_submitthread_form.appendChild(document.createTextNode('Body:'));
		obj_submitthread_form.appendChild(document.createElement('br'));
		var obj_submitthread_body=document.createElement('textarea');
		obj_submitthread_body.id='submitthread_body';
		if (!snaf.user.login) {
			obj_submitthread_body.disabled=true; }
		obj_submitthread_body.onkeyup=function() {
			if (obj_submitthread_subject.value && obj_submitthread_body.value) {
				obj_submitthread_submit.disabled=false;
			} else {
				obj_submitthread_submit.disabled=true;
			}
		}
		obj_submitthread_form.appendChild(obj_submitthread_body);
		obj_submitthread_form.appendChild(document.createElement('br'));
		// Submit
		var obj_submitthread_submit=document.createElement('input');
		obj_submitthread_submit.type='submit';
		obj_submitthread_submit.id='submitthread_submit';
		obj_submitthread_submit.value='New thread';
		obj_submitthread_submit.disabled=true;
		obj_submitthread_form.appendChild(obj_submitthread_submit);
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
		//Do not add to back history if dontindex is set
		if (!snaf.dontindex) { snaf.back.push(snaf.current); }
		snaf.dontindex=false;
		snaf.current={
		 'location':thread,
		 'locationid':thread_id };
		
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
			snaf.dontindex=true; //Disable indexing
			thread(thread_id); //Refresh thread
		}
		obj_refresh.appendChild(document.createTextNode('Refresh'));
		obj_fat.appendChild(obj_refresh);
		
		var xml=http_request.responseXML;
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
			var obj_submitreply_form=document.createElement('form');
			obj_submitreply_form.onsubmit=function() { submitreply(); return false; }
			obj_submitreply_form.id='submitreply_form';
			obj_submitreply_form.acceptCharset='utf-8';
			obj_fat.appendChild(obj_submitreply_form);
			// Subject
			obj_submitreply_form.appendChild(document.createTextNode('Subject:'));
			obj_submitreply_form.appendChild(document.createElement('br'));
			var obj_submitreply_subject=document.createElement('input');
			obj_submitreply_subject.type='text';
			obj_submitreply_subject.id='submitreply_subject';
			obj_submitreply_subject.value='Re: '+xml_posts[0].getElementsByTagName('subject')[0].textContent;
			if (!snaf.user.login) {
				obj_submitreply_subject.disabled=true; }
			obj_submitreply_subject.onkeyup=function() {
				if (obj_submitreply_subject.value && obj_submitreply_body.value) {
					obj_submitreply_submit.disabled=false;
				} else {
					obj_submitreply_submit.disabled=true;
				}
			}
			obj_submitreply_form.appendChild(obj_submitreply_subject);
			obj_submitreply_form.appendChild(document.createElement('br'));
			// Body
			obj_submitreply_form.appendChild(document.createTextNode('Body:'));
			obj_submitreply_form.appendChild(document.createElement('br'));
			var obj_submitreply_body=document.createElement('textarea');
			obj_submitreply_body.id='submitreply_body';
			if (!snaf.user.login) {
				obj_submitreply_body.disabled=true; }
			obj_submitreply_body.onkeyup=function() {
				if (obj_submitreply_subject.value && obj_submitreply_body.value) {
					obj_submitreply_submit.disabled=false;
				} else {
					obj_submitreply_submit.disabled=true;
				}
			}
			obj_submitreply_form.appendChild(obj_submitreply_body);
			obj_submitreply_form.appendChild(document.createElement('br'));
			// Submit
			var obj_submitreply_submit=document.createElement('input');
			obj_submitreply_submit.type='submit';
			obj_submitreply_submit.id='submitreply_submit';
			obj_submitreply_submit.value='Reply';
			obj_submitreply_submit.disabled=true;
			obj_submitreply_form.appendChild(obj_submitreply_submit);
		} else {
			obj_fat.appendChild(document.createElement('br'));
			obj_fat.appendChild(document.createTextNode('Thread not found'));
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}

function formsanitycheck(form) {
	if (form == 'login') {
		//Grab objects
		var obj_username=document.getElementById('login_username');
		var obj_password=document.getElementById('login_password');
		var obj_submit=document.getElementById('login_submit');
		if (obj_username.value && obj_password.value) {
			//Disable submit
			obj_submit.disabled=false;
		} else {
			//Enable submit
			obj_submit.disabled=true;
		}
	}
	else if (form == 'submitaccount') {
		//Grab objects
		var obj_username=document.getElementById('submitaccount_username');
		var obj_password=document.getElementById('submitaccount_password');
		var obj_confirmpassword=document.getElementById('submitaccount_confirmpassword');
		var obj_submit=document.getElementById('submitaccount_submit');
		var obj_error=document.getElementById('submitaccount_error');
		//Make sure passwords match
		if (obj_password.value == obj_confirmpassword.value) {
			if (obj_username.value
			 && obj_password.value
			 && obj_confirmpassword.value) {
				//Clear eventual error messages
				while (obj_error.hasChildNodes()) {
					obj_error.removeChild(obj_error.firstChild); }
				//Enable submit
				obj_submit.disabled=false;
			}
		}
		else if (obj_password.value
		      && obj_confirmpassword.value) {
			//Print error message
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
			obj_error.appendChild(
			 document.createTextNode('Passwords do not match'));
			//Disable submit
			obj_submit.disabled=true;
		}
		else {
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
		}
	}
}

function submitaccount() {
	//Grab objects
	var obj_username=document.getElementById('submitaccount_username');
	var obj_password=document.getElementById('submitaccount_password');
	var obj_confirmpassword=document.getElementById('submitaccount_confirmpassword');
	var obj_email=document.getElementById('submitaccount_email');
	var obj_submit=document.getElementById('submitaccount_submit');
	var obj_error=document.getElementById('submitaccount_error');
	
	//Disable input boxes
	obj_username.disabled=true;
	obj_password.disabled=true;
	obj_confirmpassword.disabled=true;
	obj_email.disabled=true;
	obj_submit.disabled=true;
	
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//Make the request
	http_request.open('POST','hooks/submitaccount.php',false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'username='+encodeURI(obj_username.value)+
		'&password='+encodeURI(obj_password.value)+
		'&email='+encodeURI(obj_email.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			display('show','loginbox');
			//Grab objects
			var obj_login_form=document.getElementById('login_form');
			var obj_login_username=document.getElementById('login_username');
			var obj_login_password=document.getElementById('login_password');
			//Issue credentials & submit
			obj_login_username.value=obj_username.value;
			obj_login_password.value=obj_password.value;
			obj_login_form.onsubmit();
			//Clear boxes
			obj_username.value='';
			obj_password.value='';
			obj_confirmpassword.value='';
			obj_email.value='';
			//Change disabled state
			obj_username.disabled=false;
			obj_password.disabled=false;
			obj_confirmpassword.disabled=false;
			obj_email.disabled=false;
			obj_submit.disabled=true;
			//Clear eventual error
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
			return true;
		}
		else if (state == 'username already exists') {
			//Print error message
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
			obj_error.appendChild(document.createTextNode('Username already exists'));
			
		}
		else {
			alert('Error: '+state);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enble input boxes
	obj_username.disabled=false;
	obj_password.disabled=false;
	obj_confirmpassword.disabled=false;
	obj_email.disabled=false;
	obj_submit.disabled=false;
}

function submitforum() {
	//Grab objects
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
	http_request.open('POST','hooks/submitforum.php?forum_id='+snaf.current.locationid,false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'subject='+encodeURI(obj_subject.value)+
		'&body='+encodeURI(obj_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			snaf.dontindex=true; //Disable indexing
			forum(snaf.current.locationid); //Refresh forum
			return true;
		}
		else {
			alert('Error: '+state);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enable input boxes
	obj_subject.disabled=false;
	obj_body.disabled=false;
	obj_submit.disabled=false;
}

function submitthread() {
	//Grab objects
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
	http_request.open('POST','hooks/submitthread.php?forum_id='+snaf.current.locationid,false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'subject='+encodeURI(obj_subject.value)+
		'&body='+encodeURI(obj_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			snaf.dontindex=true; //Disable indexing
			forum(snaf.current.locationid); //Refresh forum
			return true;
		}
		else {
			alert('Error: '+state);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enable input boxes
	obj_subject.disabled=false;
	obj_body.disabled=false;
	obj_submit.disabled=false;
}

function submitreply() {
	//Grab objects
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
	http_request.open('POST','hooks/submitreply.php?thread_id='+snaf.current.locationid,false);
	http_request.setRequestHeader('Content-Type'
	           ,'application/x-www-form-urlencoded; charset=utf-8');
	http_request.send(
		'subject='+encodeURI(obj_subject.value)+
		'&body='+encodeURI(obj_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var state=http_request.responseText;
		if (state == 'success') {
			snaf.dontindex=true; //Disable indexing
			thread(snaf.current.locationid); //Refresh thread
			return true;
		}
		else {
			alert('Error: '+state);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enable input boxes
	obj_subject.disabled=false;
	obj_body.disabled=false;
	obj_submit.disabled=false;
}

function login() {
	//Grab objects
	var obj_username=document.getElementById('login_username');
	var obj_password=document.getElementById('login_password');
	var obj_submit=document.getElementById('login_submit');
	var obj_error=document.getElementById('login_error');
	
	//Disable input boxes
	obj_username.disabled=true;
	obj_password.disabled=true;
	obj_submit.disabled=true;
	
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
		var xml=http_request.responseXML;
		var xml_action=xml.getElementsByTagName('action')[0];
		var xml_result=xml_action.getAttribute('result');
		if (xml_result == 'success') {
			snaf.user.login=true;
			snaf.user.user_id=xml_action.getAttribute('user_id');
			snaf.user.username=xml_action.getAttribute('username');
			refresh();
			//Clear boxes
			obj_username.value='';
			obj_password.value='';
			//Change disabled state
			obj_username.disabled=false;
			obj_password.disabled=false;
			obj_submit.disabled=true;
			//Clear eventual error
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
			return true;
		}
		else if (xml_result == 'invalid credentials') {
			//Print error message
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
			obj_error.appendChild(document.createTextNode('Invalid credentials'));
		}
		else {
			alert('Error: '+xml_result);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enable input boxes
	obj_username.disabled=false;
	obj_password.disabled=false;
	obj_submit.disabled=false;
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
	
	if (snaf.display.loginbox) {
		//Grab objects
		var obj_username=document.getElementById('login_username');
		var obj_password=document.getElementById('login_password');
		var obj_submit=document.getElementById('login_submit');
		
		//Disable input boxes
		obj_username.disabled=true;
		obj_password.disabled=true;
		obj_submit.disabled=true;
	}
	
	//What happened?
	if (http_request.status == 200) {
		var xml=http_request.responseXML;
		var xml_action=xml.getElementsByTagName('action')[0];
		var xml_result=xml_action.getAttribute('result');
		if (xml_result == 'success') {
			snaf.user.login=true;
			snaf.user.user_id=xml_action.getAttribute('user_id');
			snaf.user.username=xml_action.getAttribute('username');
			refresh();
			var obj_loginbox=document.getElementById('loginbox');
			if (obj_loginbox.hasChildNodes()) {
				//Clear boxes
				obj_username.value='';
				obj_password.value='';
				//Change disabled state
				obj_username.disabled=false;
				obj_password.disabled=false;
				obj_submit.disabled=true;
				//Clear eventual error
				while (obj_error.hasChildNodes()) {
					obj_error.removeChild(obj_error.firstChild); }
				return true;
			}
		}
		else {
			if (xml_result == 'invalid credentials') {
				alert('Invalid credentials');
			}
			else if (xml_result == 'not enough input') {
				alert('Not enough input');
			}
			else {
				alert('Error: '+xml_result);
			}
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	if (snaf.display.loginbox) {
		//Enable input boxes
		obj_username.disabled=false;
		obj_password.disabled=false;
		if (obj_username.value && obj_password.value) {
			obj_submit.disabled=false;
		} else {
			obj_submit.disabled=true;
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
		var state=http_request.responseText;
		if (state == 'success') {
			snaf.user.login=false;
			refresh();
		}
		else {
			alert('Error: '+state);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}
