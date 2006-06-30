#SNAF tables

#drop and recreate db
DROP DATABASE snaf;CREATE DATABASE snaf;USE snaf;

#accounts
CREATE TABLE snaf_accounts (
	id int UNSIGNED NOT NULL AUTO_INCREMENT,
	username varchar(30) NOT NULL,
	password char(16) BINARY NOT NULL,
	permission text NOT NULL,
	details mediumtext,
	PRIMARY KEY (id),
	UNIQUE id (id),
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
	"author",
	1150736367,
	"General Discussion",
	"Talk about general stuff here"
);

INSERT INTO snaf_fat VALUES (
	0,
	2,
	0,
	"author",
	1150736367,
	"Quake",
	"Talk about Quake here"
);

INSERT INTO snaf_fat VALUES (
	0,
	3,
	0,
	"author",
	1150736367,
	"Half-Life",
	"Talk about Half-Life here"
);

#Thread 1
INSERT INTO snaf_fat VALUES (
	1,
	1,
	NULL,
	"recover",
	1150736367,
	"SNAF forum",
	"Woohooo! SNAF är på väg! :P"
);

INSERT INTO snaf_fat VALUES (
	1,
	1,
	NULL,
	"kakmannen",
	1150736667,
	"Re: SNAF forum",
	"Ja, det får vi hoppas"
);

INSERT INTO snaf_fat VALUES (
	1,
	1,
	NULL,
	"recover",
	1150736687,
	"Re: SNAF forum",
	"Mmm... Men när man programmerar något själv så går det inte så snabbt"
);

#Thread 2
INSERT INTO snaf_fat VALUES (
	1,
	2,
	NULL,
	"recover",
	1150737367,
	"SNAF problems",
	"JavaScript closure problem solved! :)"
);

INSERT INTO snaf_fat VALUES (
	1,
	2,
	NULL,
	"kakmannen",
	1151430517,
	"Re: SNAF problems",
	"post_id auto_increment problem solved! :P"
);
