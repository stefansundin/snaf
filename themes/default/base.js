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

Clear all childs inside an element:
while (element.hasChildNodes()) {
	element.removeChild(element.firstChild); }
*/

function load() {
	//Create a new body (effectively removes all childrens in the existing body)
	document.body=document.createElement('body');
	//Grab body
	var body=document.body;
	
	//Create objects
	// userbar
	var obj_userbar=document.createElement('div');
	obj_userbar.id='userbar';
	body.appendChild(obj_userbar);
	// fat
	var obj_fat=document.createElement('div');
	obj_fat.id='fat';
	body.appendChild(obj_fat);
	// loginbox
	var obj_box=document.createElement('div');
	obj_box.id='loginbox';
	body.appendChild(obj_box);
	// submitaccountbox
	var obj_box=document.createElement('div');
	obj_box.id='submitaccountbox';
	body.appendChild(obj_box);
	// submitforumbox
	var obj_box=document.createElement('div');
	obj_box.id='submitforumbox';
	body.appendChild(obj_box);
	// submitthreadbox
	var obj_box=document.createElement('div');
	obj_box.id='submitthreadbox';
	body.appendChild(obj_box);
	// submitreplybox
	var obj_box=document.createElement('div');
	obj_box.id='submitreplybox';
	body.appendChild(obj_box);
	
	//Display
	// refresh
	refresh();
	// location
	snaf.current.location(snaf.current.locationid);
	// title
	document.title=snaf.sitename;
	// done
	snaf.loading=false;
}

function closurecallback(callbackFunc,callbackArg) {
	return function() {
		callbackFunc(callbackArg);
	}
}

function back() {
	//Hide interfering boxes
	display('hide','submitforumbox');
	display('hide','submitthreadbox');
	display('hide','submitreplybox');
	if (snaf.back.length > 0) {
		var nextback=snaf.back.pop();
		snaf.dontindex=true; //Disable indexing
		nextback.location(nextback.locationid);
	} else {
		forum(0);
	}
}

function locationrefresh() {
	snaf.dontindex=true; //Disable indexing
	snaf.current.location(snaf.current.locationid); //Refresh
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
				//Change the add forum text
				var obj_addforum_link=document.getElementById('addforum_link');
				while (obj_addforum_link.hasChildNodes()) {
					obj_addforum_link.removeChild(obj_addforum_link.firstChild); }
				obj_addforum_link.appendChild(document.createTextNode('add forum'));
				//Change the add thread text
				var obj_addthread_link=document.getElementById('addthread_link');
				while (obj_addthread_link.hasChildNodes()) {
					obj_addthread_link.removeChild(obj_addthread_link.firstChild); }
				obj_addthread_link.appendChild(document.createTextNode('add thread'));
			}
			var obj_submitforumbox=document.getElementById('submitforumbox');
			if (obj_submitforumbox.hasChildNodes()) {
				//Enable submitforum form
				var form_subject=document.getElementById('submitforum_subject');
				var form_body=document.getElementById('submitforum_body');
				var form_submit=document.getElementById('submitforum_submit');
				form_subject.disabled=false;
				form_body.disabled=false;
				if (form_subject.value
				 && form_body.value) {
					form_submit.disabled=false;
				} else {
					form_submit.disabled=true;
				}
			}
			var obj_submitthreadbox=document.getElementById('submitthreadbox');
			if (obj_submitthreadbox.hasChildNodes()) {
				//Change author
				var obj_author=document.getElementById('addthread_author');
				while (obj_author.hasChildNodes()) {
					obj_author.removeChild(obj_author.firstChild); }
				var obj_author_link=document.createElement('a');
				obj_author_link.onclick=closurecallback(user,snaf.user.username);
				obj_author_link.appendChild(document.createTextNode(snaf.user.username));
				obj_author.appendChild(obj_author_link);
				//Enable submitthread form
				var form_subject=document.getElementById('submitthread_subject');
				var form_body=document.getElementById('submitthread_body');
				var form_submit=document.getElementById('submitthread_submit');
				form_subject.disabled=false;
				form_body.disabled=false;
				if (form_subject.value
				 && form_body.value) {
					form_submit.disabled=false;
				} else {
					form_submit.disabled=true;
				}
			}
			if (snaf.current.location == thread) {
				//Change the add reply text
				var obj_addreply_link=document.getElementById('addreply_link');
				while (obj_addreply_link.hasChildNodes()) {
					obj_addreply_link.removeChild(obj_addreply_link.firstChild); }
				obj_addreply_link.appendChild(document.createTextNode('add reply'));
			}
			var obj_submitreplybox=document.getElementById('submitreplybox');
			if (obj_submitreplybox.hasChildNodes()) {
				//Change author
				var obj_author=document.getElementById('addreply_author');
				while (obj_author.hasChildNodes()) {
					obj_author.removeChild(obj_author.firstChild); }
				var obj_author_link=document.createElement('a');
				obj_author_link.onclick=closurecallback(user,snaf.user.username);
				obj_author_link.appendChild(document.createTextNode(snaf.user.username));
				obj_author.appendChild(obj_author_link);
				//Enable submitreply form
				var form_subject=document.getElementById('submitreply_subject');
				var form_body=document.getElementById('submitreply_body');
				var form_submit=document.getElementById('submitreply_submit');
				form_subject.disabled=false;
				form_body.disabled=false;
				if (form_subject.value
				 && form_body.value) {
					form_submit.disabled=false;
				} else {
					form_submit.disabled=true;
				}
				//Change submit button text
				form_submit.value='Add reply';
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
		if (snaf.support.httpauth) {
			var obj_ub_httpauth=document.createElement('a');
			obj_ub_httpauth.id='ub_httpauth';
			obj_ub_httpauth.onclick=function() { httpauth(); }
			obj_ub_httpauth.appendChild(document.createTextNode('http-auth'));
			obj_ub_httpauth.title='Sign in through HTTP-Authentication';
			obj_userbar.appendChild(obj_ub_httpauth);
		}
		// ub_submitaccount
		var obj_ub_login=document.createElement('a');
		obj_ub_login.id='ub_submitaccount';
		obj_ub_login.onclick=function(){ display('toggle','submitaccountbox'); }
		obj_ub_login.appendChild(document.createTextNode('create account'));
		obj_userbar.appendChild(obj_ub_login);
		
		//Update other things
		if (!snaf.loading) {
			if (snaf.current.location == forum) {
				//Change the add forum text
				var obj_addforum_link=document.getElementById('addforum_link');
				while (obj_addforum_link.hasChildNodes()) {
					obj_addforum_link.removeChild(obj_addforum_link.firstChild); }
				obj_addforum_link.appendChild(document.createTextNode('add forum (requires login)'));
				//Change the add thread text
				var obj_addthread_link=document.getElementById('addthread_link');
				while (obj_addthread_link.hasChildNodes()) {
					obj_addthread_link.removeChild(obj_addthread_link.firstChild); }
				obj_addthread_link.appendChild(document.createTextNode('add thread (requires login)'));
			}
			var obj_submitforumbox=document.getElementById('submitforumbox');
			if (obj_submitforumbox.hasChildNodes()) {
				//Disable submitforum form
				var form_subject=document.getElementById('submitforum_subject');
				var form_body=document.getElementById('submitforum_body');
				var form_submit=document.getElementById('submitforum_submit');
				form_subject.disabled=true;
				form_body.disabled=true;
				form_submit.disabled=true;
			}
			var obj_submitthreadbox=document.getElementById('submitthreadbox');
			if (obj_submitthreadbox.hasChildNodes()) {
				//Change author
				var obj_author=document.getElementById('addthread_author');
				while (obj_author.hasChildNodes()) {
					obj_author.removeChild(obj_author.firstChild); }
				obj_author.appendChild(document.createTextNode('Anonymous Coward'));
				//Disable submitthread form
				var form_subject=document.getElementById('submitthread_subject');
				var form_body=document.getElementById('submitthread_body');
				var form_submit=document.getElementById('submitthread_submit');
				form_subject.disabled=true;
				form_body.disabled=true;
				form_submit.disabled=true;
			}
			if (snaf.current.location == thread) {
				//Change the add reply text
				var obj_addreply_link=document.getElementById('addreply_link');
				while (obj_addreply_link.hasChildNodes()) {
					obj_addreply_link.removeChild(obj_addreply_link.firstChild); }
				obj_addreply_link.appendChild(document.createTextNode('add reply (requires login)'));
			}
			var obj_submitreplybox=document.getElementById('submitreplybox');
			if (obj_submitreplybox.hasChildNodes()) {
				//Change author
				var obj_author=document.getElementById('addreply_author');
				while (obj_author.hasChildNodes()) {
					obj_author.removeChild(obj_author.firstChild); }
				obj_author.appendChild(document.createTextNode('Anonymous Coward'));
				//Disable submitreply form
				var form_subject=document.getElementById('submitreply_subject');
				var form_body=document.getElementById('submitreply_body');
				var form_submit=document.getElementById('submitreply_submit');
				form_subject.disabled=true;
				form_body.disabled=true;
				form_submit.disabled=true;
				//Change submit button text
				form_submit.value+=' (requires login)';
			}
		}
	}
}

function display(state,box) {
	if (state == 'hide' && box == 'all') {
		display('hide','loginbox');
		display('hide','submitaccountbox');
		display('hide','submitforumbox');
		display('hide','submitthreadbox');
		display('hide','submitreplybox');
	}
	else if (box == 'loginbox') {
		if (state == 'toggle') {
			if (snaf.display.loginbox) { display('hide',box); }
			else { display('show',box); }
		}
		else if (state == 'show') {
			//Hide interfering boxes
			display('hide','submitaccountbox');
			//Update global variable
			snaf.display.loginbox=true;
			//Grab loginbox
			var obj_loginbox=document.getElementById('loginbox');
			//Show loginbox
			obj_loginbox.style.display='block';
			
			//Create loginbox contents if not already present
			if (!obj_loginbox.hasChildNodes()) {
				//Create login form
				var form=document.createElement('form');
				form.method='post';
				form.id='login_form';
				form.onsubmit=function(){
					login(form_username.value,
					 form_password.value,
					 form_rememberme.checked);
					return false;
				}
				form.acceptCharset='utf-8';
				obj_loginbox.appendChild(form);
				
				// Username
				form.appendChild(document.createTextNode('Username: '));
				var form_username=document.createElement('input');
				form_username.type='text';
				form_username.id='login_username';
				form_username.onkeypress=closurecallback(formsanitycheck,'login');
				form_username.onkeyup=closurecallback(formsanitycheck,'login');
				form.appendChild(form_username);
				form.appendChild(document.createElement('br'));
				
				// Password
				form.appendChild(document.createTextNode('Password: '));
				var form_password=document.createElement('input');
				form_password.type='password';
				form_password.id='login_password';
				form_password.onkeypress=closurecallback(formsanitycheck,'login');
				form_password.onkeyup=closurecallback(formsanitycheck,'login');
				form.appendChild(form_password);
				form.appendChild(document.createElement('br'));
				
				// Remember me
				var obj_rememberme_label=document.createElement('label');
				obj_rememberme_label.id='login_rememberme_label';
				var form_rememberme=document.createElement('input');
				form_rememberme.type='checkbox';
				form_rememberme.id='login_rememberme';
				form_rememberme.checked=true;
				obj_rememberme_label.appendChild(form_rememberme);
				obj_rememberme_label.appendChild(document.createTextNode('Remember me for ten years'));
				form.appendChild(obj_rememberme_label);
				form.appendChild(document.createElement('br'));
				
				// Submit
				var form_submit=document.createElement('input');
				form_submit.type='submit';
				form_submit.id='login_submit';
				form_submit.value='Sign in';
				form_submit.disabled=true;
				form.appendChild(form_submit);
				
				//Error
				var obj_error=document.createElement('div');
				obj_error.id='login_error';
				obj_loginbox.appendChild(obj_error);
			}
			else {
				var form_username=document.getElementById('login_username');
			}
			//Focus username box
			form_username.focus();
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
				display('hide',box); }
			else { display('show',box); }
		}
		else if (state == 'show') {
			//Hide interfering boxes
			display('hide','loginbox');
			//Update global variable
			snaf.display.submitaccountbox=true;
			//Grab box
			var obj_box=document.getElementById('submitaccountbox');
			//Show box
			obj_box.style.display='block';
			
			//Create submitaccountbox contents if not already present
			if (!obj_box.hasChildNodes()) {
				//Create submitaccountbox form
				var form=document.createElement('form');
				form.method='post';
				form.id='submitaccount_form';
				form.onsubmit=function(){ submitaccount(); return false; }
				form.acceptCharset='utf-8';
				obj_box.appendChild(form);
				
				// Username
				form.appendChild(document.createTextNode('Username: '));
				var form_username=document.createElement('input');
				form_username.type='text';
				form_username.id='submitaccount_username';
				form_username.onkeypress=closurecallback(formsanitycheck,'submitaccount');
				form_username.onkeyup=closurecallback(formsanitycheck,'submitaccount');
				form.appendChild(form_username);
				form.appendChild(document.createElement('br'));
				
				// Password
				form.appendChild(document.createTextNode('Password: '));
				var form_password=document.createElement('input');
				form_password.type='password';
				form_password.id='submitaccount_password';
				form_password.onkeypress=closurecallback(formsanitycheck,'submitaccount');
				form_password.onkeyup=closurecallback(formsanitycheck,'submitaccount');
				form.appendChild(form_password);
				form.appendChild(document.createElement('br'));
				
				// Confirm password
				form.appendChild(document.createTextNode('Confirm password: '));
				var form_confirmpassword=document.createElement('input');
				form_confirmpassword.type='password';
				form_confirmpassword.id='submitaccount_confirmpassword';
				form_confirmpassword.onkeyup=closurecallback(formsanitycheck,'submitaccount');
				form.appendChild(form_confirmpassword);
				form.appendChild(document.createElement('br'));
				
				// Email
				form.appendChild(document.createTextNode('Email (optional): '));
				var form_email=document.createElement('input');
				form_email.type='text';
				form_email.id='submitaccount_email';
				form_email.onkeyup=closurecallback(formsanitycheck,'submitaccount');
				form.appendChild(form_email);
				form.appendChild(document.createElement('br'));
				
				// Submit
				var form_submit=document.createElement('input');
				form_submit.type='submit';
				form_submit.id='submitaccount_submit';
				form_submit.value='Create account';
				form_submit.disabled=true;
				form.appendChild(form_submit);
				
				//Error
				var obj_error=document.createElement('div');
				obj_error.id='submitaccount_error';
				obj_box.appendChild(obj_error);
			}
			else {
				var form_username=document.getElementById('submitaccount_username');
			}
			//Focus username box
			form_username.focus();
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
	else if (box == 'submitforumbox') {
		if (state == 'toggle') {
			if (snaf.display.submitforumbox) {
				display('hide',box); }
			else { display('show',box); }
		}
		else if (state == 'show') {
			//Hide interfering boxes
			display('hide','submitthreadbox');
			display('hide','submitreplybox');
			//Update global variable
			snaf.display.submitforumbox=true;
			//Grab box
			var obj_box=document.getElementById('submitforumbox');
			//Show box
			obj_box.style.display='block';
			
			//Create contents if not already present
			if (!obj_box.hasChildNodes()) {
				//Create submitforum form
				var form=document.createElement('form');
				form.onsubmit=function() { submitforum(); return false; }
				form.id='submitforum_form';
				form.acceptCharset='utf-8';
				obj_box.appendChild(form);
				// Subject
				form.appendChild(document.createTextNode('Subject:'));
				form.appendChild(document.createElement('br'));
				var form_subject=document.createElement('input');
				form_subject.type='text';
				form_subject.id='submitforum_subject';
				if (!snaf.user.login) {
					form_subject.disabled=true; }
				form_subject.onkeypress=closurecallback(formsanitycheck,'submitforum');
				form_subject.onkeyup=closurecallback(formsanitycheck,'submitforum');
				form.appendChild(form_subject);
				form.appendChild(document.createElement('br'));
				// Body
				form.appendChild(document.createTextNode('Body:'));
				form.appendChild(document.createElement('br'));
				var form_body=document.createElement('textarea');
				form_body.id='submitforum_body';
				if (!snaf.user.login) {
					form_body.disabled=true; }
				form_body.onkeypress=closurecallback(formsanitycheck,'submitforum');
				form_body.onkeyup=closurecallback(formsanitycheck,'submitforum');
				form.appendChild(form_body);
				form.appendChild(document.createElement('br'));
				// Submit
				var form_submit=document.createElement('input');
				form_submit.type='submit';
				form_submit.id='submitforum_submit';
				form_submit.value='New forum';
				form_submit.disabled=true;
				form.appendChild(form_submit);
			}
			else {
				var form_subject=document.getElementById('submitforum_subject');
			}
			//Focus username box
			form_subject.focus();
		}
		else if (state == 'hide') {
			//Update global variable
			snaf.display.submitforumbox=false;
			//Grab box
			var obj_box=document.getElementById('submitforumbox');
			//Hide box
			obj_box.style.display='none';
		}
	}
	else if (box == 'submitthreadbox') {
		if (state == 'toggle') {
			if (snaf.display.submitthreadbox) {
				display('hide',box); }
			else { display('show',box); }
		}
		else if (state == 'show') {
			//Hide interfering boxes
			display('hide','submitforumbox');
			display('hide','submitreplybox');
			//Update global variable
			snaf.display.submitthreadbox=true;
			//Grab box
			var obj_box=document.getElementById('submitthreadbox');
			//Show box
			obj_box.style.display='block';
			
			//Create contents if not already present
			if (!obj_box.hasChildNodes()) {
				//Create submitthread form
				var form=document.createElement('form');
				form.onsubmit=function() { submitthread(); return false; }
				form.id='submitthread_form';
				form.acceptCharset='utf-8';
				obj_box.appendChild(form);
				
				//Topinfo
				var obj_topinfo=document.createElement('div');
				obj_topinfo.className='topinfo';
				form.appendChild(obj_topinfo);
				// Date
				var obj_topinfo_date=document.createElement('div');
				obj_topinfo_date.className='date';
				obj_topinfo_date.appendChild(document.createTextNode('New thread'));
				obj_topinfo.appendChild(obj_topinfo_date);
				// Subject
				var obj_topinfo_subject=document.createElement('div');
				obj_topinfo_subject.className='subject';
				obj_topinfo.appendChild(obj_topinfo_subject);
				var form_subject=document.createElement('input');
				form_subject.type='text';
				form_subject.id='submitthread_subject';
				if (!snaf.user.login) {
					form_subject.disabled=true; }
				form_subject.onkeypress=closurecallback(formsanitycheck,'submitthread');
				form_subject.onkeyup=closurecallback(formsanitycheck,'submitthread');
				obj_topinfo_subject.appendChild(form_subject);
				
				//Author
				var obj_author=document.createElement('div');
				obj_author.className='author';
				obj_author.id='addthread_author';
				if (snaf.user.login) {
					var obj_author_link=document.createElement('a');
					obj_author_link.onclick=closurecallback(user,snaf.user.username);
					obj_author_link.appendChild(document.createTextNode(snaf.user.username));
					obj_author.appendChild(obj_author_link);
				} else {
					obj_author.appendChild(document.createTextNode('Anonymous Coward'));
				}
				form.appendChild(obj_author);
				
				//Body
				var obj_body=document.createElement('div');
				obj_body.className='body';
				form.appendChild(obj_body);
				var form_body=document.createElement('textarea');
				form_body.id='submitthread_body';
				if (!snaf.user.login) {
					form_body.disabled=true; }
				form_body.onkeypress=closurecallback(formsanitycheck,'submitthread');
				form_body.onkeyup=closurecallback(formsanitycheck,'submitthread');
				obj_body.appendChild(form_body);
				obj_body.appendChild(document.createElement('br'));
				//Submit
				var form_submit=document.createElement('input');
				form_submit.type='submit';
				form_submit.id='submitthread_submit';
				form_submit.value='Add thread';
				if (!snaf.user.login) {
					form_submit.value+=' (requires login)';
				}
				form_submit.disabled=true;
				obj_body.appendChild(form_submit);
			}
			else {
				var form_subject=document.getElementById('submitthread_subject');
			}
			//Focus subject box
			form_subject.focus();
		}
		else if (state == 'hide') {
			//Update global variable
			snaf.display.submitthreadbox=false;
			//Grab box
			var obj_box=document.getElementById('submitthreadbox');
			//Hide box
			obj_box.style.display='none';
		}
	}
	else if (box == 'submitreplybox') {
		if (state == 'toggle') {
			if (snaf.display.submitreplybox) {
				display('hide',box); }
			else { display('show',box,arguments[2],arguments[3]); }
		}
		else if (state == 'show') {
			//Hide interfering boxes
			display('hide','submitforumbox');
			display('hide','submitthreadbox');
			//Update global variable
			snaf.display.submitreplybox=true;
			//Grab box
			var obj_box=document.getElementById('submitreplybox');
			//Show box
			obj_box.style.display='block';
			
			//Create contents if not already present
			if (!obj_box.hasChildNodes()) {
				//Create submitreply form
				var form=document.createElement('form');
				form.onsubmit=function() { submitreply(); return false; }
				form.id='submitreply_form';
				form.acceptCharset='utf-8';
				obj_box.appendChild(form);
				
				//Topinfo
				var obj_topinfo=document.createElement('div');
				obj_topinfo.className='topinfo';
				form.appendChild(obj_topinfo);
				// Date
				var obj_topinfo_date=document.createElement('div');
				obj_topinfo_date.className='date';
				obj_topinfo_date.appendChild(document.createTextNode('Add reply #'+(parseInt(arguments[2])+1)));
				obj_topinfo.appendChild(obj_topinfo_date);
				// Subject
				var obj_topinfo_subject=document.createElement('div');
				obj_topinfo_subject.className='subject';
				obj_topinfo.appendChild(obj_topinfo_subject);
				var form_subject=document.createElement('input');
				form_subject.type='text';
				form_subject.id='submitreply_subject';
				form_subject.value='Re: '+arguments[3];
				if (!snaf.user.login) {
					form_subject.disabled=true; }
				form_subject.onkeypress=closurecallback(formsanitycheck,'submitreply');
				form_subject.onkeyup=closurecallback(formsanitycheck,'submitreply');
				obj_topinfo_subject.appendChild(form_subject);
				
				//Author
				var obj_author=document.createElement('div');
				obj_author.className='author';
				obj_author.id='addreply_author';
				if (snaf.user.login) {
					var obj_author_link=document.createElement('a');
					obj_author_link.onclick=closurecallback(user,snaf.user.username);
					obj_author_link.appendChild(document.createTextNode(snaf.user.username));
					obj_author.appendChild(obj_author_link);
				} else {
					obj_author.appendChild(document.createTextNode('Anonymous Coward'));
				}
				form.appendChild(obj_author);
				
				//Body
				var obj_body=document.createElement('div');
				obj_body.className='body';
				form.appendChild(obj_body);
				var form_body=document.createElement('textarea');
				form_body.id='submitreply_body';
				if (!snaf.user.login) {
					form_body.disabled=true; }
				form_body.onkeypress=closurecallback(formsanitycheck,'submitreply');
				form_body.onkeyup=closurecallback(formsanitycheck,'submitreply');
				obj_body.appendChild(form_body);
				obj_body.appendChild(document.createElement('br'));
				//Submit
				var form_submit=document.createElement('input');
				form_submit.type='submit';
				form_submit.id='submitreply_submit';
				form_submit.value='Add reply';
				if (!snaf.user.login) {
					form_submit.value+=' (requires login)';
				}
				form_submit.disabled=true;
				obj_body.appendChild(form_submit);
			}
			else {
				var form_body=document.getElementById('submitreply_body');
			}
			//Focus body box
			form_body.focus();
		}
		else if (state == 'hide') {
			//Update global variable
			snaf.display.submitreplybox=false;
			//Grab box
			var obj_box=document.getElementById('submitreplybox');
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
		//Hide interfering boxes
		display('hide','submitforumbox');
		display('hide','submitthreadbox');
		display('hide','submitreplybox');
		
		//Grab fat
		var obj_fat=document.getElementById('fat');
		//Clear fat
		while (obj_fat.hasChildNodes()) {
			obj_fat.removeChild(obj_fat.firstChild); }
		
		//Top
		var obj_top=document.createElement('div');
		obj_top.id='top';
		obj_fat.appendChild(obj_top);
		// Back
		if (forum_id != 0) {
			var obj_back=document.createElement('div');
			obj_back.id='back';
			var obj_back_link=document.createElement('a');
			obj_back_link.onclick=function() { back(); }
			obj_back_link.appendChild(document.createTextNode('back'));
			obj_back.appendChild(obj_back_link);
			obj_top.appendChild(obj_back);
		}
		// Refresh
		var obj_refresh=document.createElement('div');
		obj_refresh.id='refresh';
		var obj_refresh_link=document.createElement('a');
		obj_refresh_link.onclick=locationrefresh;
		obj_refresh_link.appendChild(document.createTextNode('refresh'));
		obj_refresh.appendChild(obj_refresh_link);
		obj_top.appendChild(obj_refresh);
		
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
			var obj_subject=document.createElement('div');
			obj_subject.className='subject';
			var obj_subject_link=document.createElement('a');
			obj_subject_link.onclick=closurecallback(forum,xml_forum_id);
			obj_subject_link.appendChild(document.createTextNode(xml_subject));
			obj_subject.appendChild(obj_subject_link);
			obj_body.appendChild(obj_subject);
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
			obj_fat.appendChild(document.createTextNode('No forums or threads found.'));
		}
		
		//Create addthread link
		var obj_addthread=document.createElement('div');
		obj_addthread.id='addthread';
		obj_fat.appendChild(obj_addthread);
		var obj_addthread_link=document.createElement('a');
		obj_addthread_link.id='addthread_link';
		obj_addthread_link.appendChild(document.createTextNode('add thread'));
		if (!snaf.user.login) {
			obj_addthread_link.appendChild(document.createTextNode(' (requires login)'));
		}
		obj_addthread_link.onclick=function() { display('toggle','submitthreadbox'); }
		obj_addthread.appendChild(obj_addthread_link);
		//Create addforum link
		var obj_addforum=document.createElement('div');
		obj_addforum.id='addforum';
		obj_fat.appendChild(obj_addforum);
		var obj_addforum_link=document.createElement('a');
		obj_addforum_link.id='addforum_link';
		obj_addforum_link.appendChild(document.createTextNode('add forum'));
		if (!snaf.user.login) {
			obj_addforum_link.appendChild(document.createTextNode(' (requires login)'));
		}
		obj_addforum_link.onclick=function() { display('toggle','submitforumbox'); }
		obj_addforum.appendChild(obj_addforum_link);
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
		//Hide interfering boxes
		display('hide','submitforumbox');
		display('hide','submitthreadbox');
		
		//Grab fat
		var obj_fat=document.getElementById('fat');
		//Clear fat
		while (obj_fat.hasChildNodes()) { obj_fat.removeChild(obj_fat.firstChild); }
		
		//Top
		var obj_top=document.createElement('div');
		obj_top.id='top';
		obj_fat.appendChild(obj_top);
		// Back
		var obj_back=document.createElement('div');
		obj_back.id='back';
		var obj_back_link=document.createElement('a');
		obj_back_link.onclick=function() { back(); }
		obj_back_link.appendChild(document.createTextNode('back'));
		obj_back.appendChild(obj_back_link);
		obj_top.appendChild(obj_back);
		// Refresh
		var obj_refresh=document.createElement('div');
		obj_refresh.id='refresh';
		var obj_refresh_link=document.createElement('a');
		obj_refresh_link.onclick=locationrefresh;
		obj_refresh_link.appendChild(document.createTextNode('refresh'));
		obj_refresh.appendChild(obj_refresh_link);
		obj_top.appendChild(obj_refresh);
		
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
				
				//Topinfo
				var obj_topinfo=document.createElement('div');
				obj_topinfo.className='topinfo';
				obj_post.appendChild(obj_topinfo);
				// Date
				var obj_topinfo_date=document.createElement('div');
				obj_topinfo_date.className='date';
				obj_topinfo_date.appendChild(document.createTextNode('Reply #'+xml_post_id+' on '+xml_date));
				obj_topinfo.appendChild(obj_topinfo_date);
				// Subject
				var obj_topinfo_subject=document.createElement('div');
				obj_topinfo_subject.className='subject';
				obj_topinfo_subject.appendChild(document.createTextNode(xml_subject));
				obj_topinfo.appendChild(obj_topinfo_subject);
				
				//Author
				var obj_author=document.createElement('div');
				obj_author.className='author';
				var obj_author_link=document.createElement('a');
				obj_author_link.onclick=closurecallback(user,xml_author);
				obj_author_link.appendChild(document.createTextNode(xml_author));
				obj_author.appendChild(obj_author_link);
				obj_post.appendChild(obj_author);
				
				//Body
				var obj_body=document.createElement('div');
				obj_body.className='body';
				obj_body.appendChild(document.createTextNode(xml_body));
				obj_post.appendChild(obj_body);
			}
			//Create reply link
			var obj_addreply=document.createElement('div');
			obj_addreply.id='addreply';
			obj_fat.appendChild(obj_addreply);
			var obj_addreply_link=document.createElement('a');
			obj_addreply_link.id='addreply_link';
			obj_addreply_link.appendChild(document.createTextNode('add reply'));
			if (!snaf.user.login) {
				obj_addreply_link.appendChild(document.createTextNode(' (requires login)'));
			}
			obj_addreply_link.onclick=function() {
				display('toggle',
				 'submitreplybox',
				 xml_post_id,
				 xml_posts[0].getElementsByTagName('subject')[0].textContent); }
			obj_addreply.appendChild(obj_addreply_link);
		} else {
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
		var form_username=document.getElementById('login_username');
		var form_password=document.getElementById('login_password');
		var form_submit=document.getElementById('login_submit');
		if (form_username.value
		 && form_password.value) {
			//Disable submit
			form_submit.disabled=false;
		} else {
			//Enable submit
			form_submit.disabled=true;
		}
	}
	else if (form == 'submitaccount') {
		//Grab objects
		var form_username=document.getElementById('submitaccount_username');
		var form_password=document.getElementById('submitaccount_password');
		var form_confirmpassword=document.getElementById('submitaccount_confirmpassword');
		var form_submit=document.getElementById('submitaccount_submit');
		var obj_error=document.getElementById('submitaccount_error');
		//Make sure passwords match
		if (form_password.value == form_confirmpassword.value) {
			if (form_username.value
			 && form_password.value
			 && form_confirmpassword.value) {
				//Clear eventual error messages
				while (obj_error.hasChildNodes()) {
					obj_error.removeChild(obj_error.firstChild); }
				//Enable submit
				form_submit.disabled=false;
			}
		}
		else if (form_password.value
		      && form_confirmpassword.value) {
			//Print error message
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
			obj_error.appendChild(document.createTextNode('Passwords do not match'));
			//Disable submit
			form_submit.disabled=true;
		}
		else {
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
		}
	}
	else if (form == 'submitforum') {
		var form_subject=document.getElementById('submitforum_subject');
		var form_body=document.getElementById('submitforum_body');
		var form_submit=document.getElementById('submitforum_submit');
		if (form_subject.value
		 && form_body.value) {
			form_submit.disabled=false;
		} else {
			form_submit.disabled=true;
		}
	}
	else if (form == 'submitthread') {
		var form_subject=document.getElementById('submitthread_subject');
		var form_body=document.getElementById('submitthread_body');
		var form_submit=document.getElementById('submitthread_submit');
		if (form_subject.value
		 && form_body.value) {
			form_submit.disabled=false;
		} else {
			form_submit.disabled=true;
		}
	}
	else if (form == 'submitreply') {
		var form_subject=document.getElementById('submitreply_subject');
		var form_body=document.getElementById('submitreply_body');
		var form_submit=document.getElementById('submitreply_submit');
		if (form_subject.value
		 && form_body.value) {
			form_submit.disabled=false;
		} else {
			form_submit.disabled=true;
		}
	}
}

function submitaccount() {
	//Grab objects
	var form_username=document.getElementById('submitaccount_username');
	var form_password=document.getElementById('submitaccount_password');
	var form_confirmpassword=document.getElementById('submitaccount_confirmpassword');
	var form_email=document.getElementById('submitaccount_email');
	var form_submit=document.getElementById('submitaccount_submit');
	var form_error=document.getElementById('submitaccount_error');
	
	//Disable input boxes
	form_username.disabled=true;
	form_password.disabled=true;
	form_confirmpassword.disabled=true;
	form_email.disabled=true;
	form_submit.disabled=true;
	
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
		'username='+encodeURI(form_username.value)+
		'&password='+encodeURI(form_password.value)+
		'&email='+encodeURI(form_email.value));
	
	//What happened?
	if (http_request.status == 200) {
		var xml=http_request.responseXML;
		var xml_action=xml.getElementsByTagName('action')[0];
		var xml_result=xml_action.getAttribute('result');
		if (xml_result == 'success') {
			display('hide','submitaccountbox');
			login(form_username.value,form_password.value,false);
			//Reset input boxes
			form_username.value='';
			form_password.value='';
			form_confirmpassword.value='';
			form_email.value='';
			//Change disabled state
			form_username.disabled=false;
			form_password.disabled=false;
			form_confirmpassword.disabled=false;
			form_email.disabled=false;
			form_submit.disabled=true;
			//Clear eventual error
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
			return true;
		}
		else if (xml_result == 'username already exists') {
			//Print error message
			while (obj_error.hasChildNodes()) {
				obj_error.removeChild(obj_error.firstChild); }
			obj_error.appendChild(document.createTextNode('Username already exists'));
			
		}
		else {
			alert('Error: '+xml_result);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enble input boxes
	form_username.disabled=false;
	form_password.disabled=false;
	form_confirmpassword.disabled=false;
	form_email.disabled=false;
	form_submit.disabled=false;
}

function submitforum() {
	//Grab objects
	var form_subject=document.getElementById('submitforum_subject');
	var form_body=document.getElementById('submitforum_body');
	var form_submit=document.getElementById('submitforum_submit');
	
	//Disable input boxes
	form_subject.disabled=true;
	form_body.disabled=true;
	form_submit.disabled=true;

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
		'subject='+encodeURI(form_subject.value)+
		'&body='+encodeURI(form_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var xml=http_request.responseXML;
		var xml_action=xml.getElementsByTagName('action')[0];
		var xml_result=xml_action.getAttribute('result');
		if (xml_result == 'success') {
			//Reset input boxes default
			form_subject.value='';
			form_body.value='';
			//Change disabled state
			form_subject.disabled=false;
			form_body.disabled=false;
			form_submit.disabled=true;
			//Hide submitforumbox
			display('hide','submitforumbox');
			//Refresh
			locationrefresh();
			return true;
		}
		else {
			alert('Error: '+xml_result);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enable input boxes
	form_subject.disabled=false;
	form_body.disabled=false;
	form_submit.disabled=false;
}

function submitthread() {
	//Grab objects
	var form_subject=document.getElementById('submitthread_subject');
	var form_body=document.getElementById('submitthread_body');
	var form_submit=document.getElementById('submitthread_submit');
	
	//Disable input boxes
	form_subject.disabled=true;
	form_body.disabled=true;
	form_submit.disabled=true;
	
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
		'subject='+encodeURI(form_subject.value)+
		'&body='+encodeURI(form_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var xml=http_request.responseXML;
		var xml_action=xml.getElementsByTagName('action')[0];
		var xml_result=xml_action.getAttribute('result');
		if (xml_result == 'success') {
			//Reset input boxes default
			form_subject.value='';
			form_body.value='';
			//Change disabled state
			form_subject.disabled=false;
			form_body.disabled=false;
			form_submit.disabled=true;
			//Hide submitthreadbox
			display('hide','submitthreadbox');
			//Go to newly created thread
			var xml_thread_id=xml_action.getAttribute('thread_id');
			thread(xml_thread_id);
			return true;
		}
		else {
			alert('Error: '+xml_result);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enable input boxes
	form_subject.disabled=false;
	form_body.disabled=false;
	form_submit.disabled=false;
}

function submitreply() {
	//Grab objects
	var form_subject=document.getElementById('submitreply_subject');
	var form_body=document.getElementById('submitreply_body');
	var form_submit=document.getElementById('submitreply_submit');
	
	//Disable input boxes
	form_subject.disabled=true;
	form_body.disabled=true;
	form_submit.disabled=true;

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
		'subject='+encodeURI(form_subject.value)+
		'&body='+encodeURI(form_body.value));
	
	//What happened?
	if (http_request.status == 200) {
		var xml=http_request.responseXML;
		var xml_action=xml.getElementsByTagName('action')[0];
		var xml_result=xml_action.getAttribute('result');
		if (xml_result == 'success') {
			//Reset input boxes
			//form_subject.value='';
			form_body.value='';
			//Change disabled state
			form_subject.disabled=false;
			form_body.disabled=false;
			form_submit.disabled=true;
			//Hide submitreplybox
			display('hide','submitreplybox');
			//Refresh
			locationrefresh();
			return true;
		}
		else {
			alert('Error: '+xml_result);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
	//Enable input boxes
	form_subject.disabled=false;
	form_body.disabled=false;
	form_submit.disabled=false;
}

function login(username, password, rememberme) {
	var obj_loginbox=document.getElementById('loginbox');
	if (obj_loginbox.hasChildNodes()) {
		//Grab objects
		var form_username=document.getElementById('login_username');
		var form_password=document.getElementById('login_password');
		var form_rememberme=document.getElementById('login_rememberme');
		var form_submit=document.getElementById('login_submit');
		var obj_error=document.getElementById('login_error');
		//Disable input boxes
		form_username.disabled=true;
		form_password.disabled=true;
		form_rememberme.disabled=true;
		form_submit.disabled=true;
	}
	
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
		'username='+encodeURI(username)+
		'&password='+encodeURI(password)+
		(rememberme?'&rememberme=1':''));
	
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
			if (obj_loginbox.hasChildNodes()) {
				//Reset input boxes
				form_username.value='';
				form_password.value='';
				form_rememberme.checked=true;
				//Change disabled state
				form_username.disabled=false;
				form_password.disabled=false;
				form_rememberme.disabled=false;
				form_submit.disabled=true;
				//Clear eventual error
				while (obj_error.hasChildNodes()) {
					obj_error.removeChild(obj_error.firstChild); }
				return true;
			}
		}
		else if (obj_loginbox.hasChildNodes()) {
			if (xml_result == 'invalid credentials') {
				//Print error message
				while (obj_error.hasChildNodes()) {
					obj_error.removeChild(obj_error.firstChild); }
				obj_error.appendChild(document.createTextNode('Invalid credentials'));
			}
			else {
				alert('Error: '+xml_result);
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
	if (obj_loginbox.hasChildNodes()) {
		//Enable input boxes
		form_username.disabled=false;
		form_password.disabled=false;
		form_rememberme.disabled=false;
		if (form_username.value
		 && form_password.value) {
			form_submit.disabled=false;
		} else {
			formsubmit.disabled=true;
		}
	}
}

function httpauth() {
	var obj_loginbox=document.getElementById('loginbox');
	if (obj_loginbox.hasChildNodes()) {
		//Grab objects
		var form_username=document.getElementById('login_username');
		var form_password=document.getElementById('login_password');
		var form_rememberme=document.getElementById('login_rememberme');
		var form_submit=document.getElementById('login_submit');
		var obj_error=document.getElementById('login_error');
		//Disable input boxes
		form_username.disabled=true;
		form_password.disabled=true;
		form_rememberme.disabled=true;
		form_submit.disabled=true;
	}
	
	var http_request=false;
	http_request=new XMLHttpRequest();
	if (!http_request) {
		alert('Unable to create an XMLHttpRequest instance.\n'+
		      'You are most likely using an old browser.');
		return false;
	}
	
	//Make the request
	http_request.open('GET','hooks/httpauth.php',false);
	http_request.send(null);
	
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
			
			if (obj_loginbox.hasChildNodes()) {
				//Reset input boxes
				form_username.value='';
				form_password.value='';
				form_rememberme.checked=true;
				//Change disabled state
				form_username.disabled=false;
				form_password.disabled=false;
				form_rememberme.disabled=false;
				form_submit.disabled=true;
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
	if (obj_loginbox.hasChildNodes()) {
		//Enable input boxes
		form_username.disabled=false;
		form_password.disabled=false;
		form_rememberme.disabled=false;
		if (form_username.value
		 && form_password.value) {
			form_submit.disabled=false;
		} else {
			form_submit.disabled=true;
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
		var xml=http_request.responseXML;
		var xml_action=xml.getElementsByTagName('action')[0];
		var xml_result=xml_action.getAttribute('result');
		if (xml_result == 'success') {
			snaf.user.login=false;
			snaf.user.user_id=undefined;
			snaf.user.username=undefined;
			refresh();
		}
		else {
			alert('Error: '+xml_result);
		}
	} else {
		alert('There was a problem with the request:\n'+
		       http_request.statusText);
	}
}
