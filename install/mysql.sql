#SNAF tables

#drop and recreate db
DROP DATABASE snaf;CREATE DATABASE snaf;USE snaf;

#accounts
CREATE TABLE snaf_accounts (
	user_id int UNSIGNED NOT NULL AUTO_INCREMENT,
	username varchar(30) NOT NULL,
	password char(16) BINARY NOT NULL,
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
	post_id int UNSIGNED NOT NULL AUTO_INCREMENT,
	author varchar(30) NOT NULL,
	date int UNSIGNED NOT NULL,
	subject varchar(50) NOT NULL,
	body text NOT NULL,
	PRIMARY KEY (thread_id,post_id)
);

#Forums
INSERT INTO snaf_fat VALUES (
	0,
	1,
	0,
	"recover",
	1150736367,
	"SNAF",
	"Discuss SNAF here"
);

INSERT INTO snaf_fat VALUES (
	1,
	2,
	0,
	"recover",
	1150736367,
	"Announcements",
	"Announcements will be posted here"
);

INSERT INTO snaf_fat VALUES (
	1,
	3,
	0,
	"recover",
	1150736367,
	"Bugtracker",
	"Report bugs here"
);

INSERT INTO snaf_fat VALUES (
	1,
	4,
	0,
	"recover",
	1150736367,
	"Feature request",
	"Request features here"
);

INSERT INTO snaf_fat VALUES (
	0,
	5,
	0,
	"recover",
	1150736367,
	"General Discussion",
	"Talk about general stuff here"
);

#Thread 1
INSERT INTO snaf_fat VALUES (
	2,
	1,
	NULL,
	"recover",
	1150736367,
	"Introducing SNAF",
	"SNAF is a community-forum focusing on utilizing the most cutting-edge specifications of the web available."
);

INSERT INTO snaf_fat VALUES (
	2,
	2,
	NULL,
	"recover",
	1150736367,
	"Contributing",
	"SNAF is currently in alpha-phase development. You can still make a checkout of the trunk source (most recent code) from our Subversion repository with 'svn checkout svn://recover.mine.nu/snaf/trunk snaf'. Commiting requires authorization, but that doesn't stop you from contributing, you should email me (recover89@gmail.com) if you have made a patch you think should be introduced in the SNAF mainline releases."
);

INSERT INTO snaf_fat VALUES (
	2,
	3,
	NULL,
	"recover",
	1150737367,
	"Problems solved",
	"JavaScript closure problem solved! :)"
);

INSERT INTO snaf_fat VALUES (
	2,
	3,
	NULL,
	"recover",
	1151430517,
	"Re: Problems solved",
	"post_id auto_increment problem solved! :P"
);
