#SNAF tables

#drop and recreate db
DROP DATABASE snaf;CREATE DATABASE snaf;USE snaf;

#accounts
CREATE TABLE snaf_accounts (
	id int NOT NULL auto_increment,
	username varchar(30) NOT NULL,
	password char(16) BINARY NOT NULL,
	permission text NOT NULL,
	details mediumtext,
	PRIMARY KEY (id),
	UNIQUE id (id),
	UNIQUE username (username)
);

#threads
CREATE TABLE snaf_threads (
	id int NOT NULL auto_increment,
	forum_id int NOT NULL,
	thread_id int NOT NULL,
	author varchar(30) NOT NULL,
	date int NOT NULL,
	subject varchar(50) NOT NULL,
	body text NOT NULL,
	PRIMARY KEY (id),
	UNIQUE id (id)
);
