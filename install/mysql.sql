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
#For forums; forum_id refer to the forums parent forum (0 if top-level forum),
#and thread_id refer to its own forum_id
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
	1337,
	"General Discussion",
	"Talk about general stuff here"
);

INSERT INTO snaf_fat VALUES (
	0,
	2,
	0,
	"author",
	1337,
	"Quake",
	"Talk about Quake here"
);

INSERT INTO snaf_fat VALUES (
	0,
	3,
	0,
	"author",
	1337,
	"Half-Life",
	"Talk about Half-Life here"
);

#Thread 1
INSERT INTO snaf_fat VALUES (
	1,
	4,
	NULL,
	"recover",
	1150736367,
	"SNAF forum",
	"Woohooo! SNAF är på väg! :P"
);

INSERT INTO snaf_fat VALUES (
	1,
	4,
	NULL,
	"kakmannen",
	1150736667,
	"Re: SNAF forum",
	"Ja, det får vi hoppas"
);

INSERT INTO snaf_fat VALUES (
	1,
	4,
	NULL,
	"recover",
	1150736687,
	"Re: SNAF forum",
	"Mmm... Men när man programmerar något själv så går det inte så snabbt"
);

#Thread 2
INSERT INTO snaf_fat VALUES (
	1,
	5,
	NULL,
	"recover",
	1150737367,
	"SNAF problems",
	"JavaScript closure problem solved! :)"
);

INSERT INTO snaf_fat VALUES (
	1,
	5,
	NULL,
	"kakmannen",
	1151430517,
	"Re: SNAF problems",
	"post_id auto_increment problem solved! :P"
);
