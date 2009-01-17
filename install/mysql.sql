#SNAF tables

#drop and recreate db
DROP DATABASE snaf;CREATE DATABASE snaf CHARACTER SET utf8;USE snaf;

#Set character set encoding to utf-8
SET NAMES utf8;
SET character_set_server=utf8;

#accounts
CREATE TABLE snaf_accounts (
	user_id int UNSIGNED NOT NULL AUTO_INCREMENT,
	username varchar(30) NOT NULL,
	password binary(16) NOT NULL,
	permission text NOT NULL,
	details mediumtext,
	PRIMARY KEY (user_id),
	UNIQUE id (user_id),
	UNIQUE username (username)
);

#fat — "forums and threads"
#forum_id decide where the data should be, 0 for top-level.
#thread_id: for forums, thread_id refer to its own forum_id.
#           for threads, thread_id refer to the threads id
#           a forums thread_id may be the same as a threads,
#           selecting take regard to if post_id is 0
#post_id: for forums, post_id is 0
#         for threads, post_id is >0
#author: username
#date: time in unix time
#subject: subject
#body: body
CREATE TABLE snaf_fat (
	forum_id int UNSIGNED NOT NULL,
	thread_id int UNSIGNED NOT NULL,
	post_id int UNSIGNED NOT NULL,
	author varchar(30) NOT NULL,
	date int UNSIGNED NOT NULL,
	subject varchar(50) NOT NULL,
	body text NOT NULL
);
