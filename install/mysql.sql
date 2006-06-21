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
#For forums, forum_id refer to the forums parent forum (0 if top-level forum),
#and thread_id refer to its own forum_id
CREATE TABLE snaf_fat (
	forum_id int UNSIGNED NOT NULL,
	thread_id int UNSIGNED NOT NULL,
	post_id int UNSIGNED NOT NULL,
	author varchar(30) NOT NULL,
	date int UNSIGNED NOT NULL,
	subject varchar(50) NOT NULL,
	body text NOT NULL
);

#Forum
INSERT INTO snaf_fat VALUES (
	0,
	1,
	0,
	"author",
	1337,
	"General Discussion",
	"Talk about general stuff here"
);

#Thread w/ reply
INSERT INTO snaf_fat VALUES (
	1,
	1,
	1,
	"recover",
	1150736367,
	"KAKA!",
	"Lets go haxx some cookies"
);

INSERT INTO snaf_fat VALUES (
	1,
	1,
	2,
	"kakmannen",
	1150736667,
	"Re: KAKA!",
	"Ja, vi kan haxxa kakor :)"
);
