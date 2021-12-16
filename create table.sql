
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

DROP DATABASE IF EXISTS project_work;
CREATE DATABASE project_work;

USE project_work;

CREATE TABLE Users (
	u_id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
	username varchar(50) NOT NULL,
	email varchar(50) NOT NULL,
	password varchar(255) NOT NULL
);

CREATE TABLE user_details (
	id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
	u_id int(11) NOT NULL,
	fname varchar(50) NOT NULL,
	lname varchar(50) NOT NULL,
	gender varchar(10) NOT NULL,
	birthday date NOT NULL DEFAULT '1998-02-18',
	address varchar(50) NOT NULL,
	apply_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	photo_id text NOT NULL DEFAULT "images/samplephotocard.png",
	FOREIGN KEY (u_id) REFERENCES Users(u_id)
);

CREATE TABLE background_checks (
	id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
	detail_id int(11) NOT NULL,
	bg_status boolean NOT NULL DEFAULT false,
	admin_aprv boolean DEFAULT NULL,	
	feedback text,
	FOREIGN KEY (detail_id) REFERENCES user_details(id)
);

CREATE TABLE inquiry (
	id int(11) AUTO_INCREMENT PRIMARY KEY NOT NULL,
	user_id int(11) NOT NULL,
	subject varchar(50) NOT NULL,
	message text NOT NULL,
	FOREIGN KEY (user_id) REFERENCES Users(u_id)
);



INSERT INTO users( username, email, `password`)
	VALUES ("badree", "tut_project1@gmail.com", '$2y$10$SfGoP/hnFaB114Fe3N0mMuGB8KJGvBqTAhvCAAJvSTcF59K4amyqy'),
	("admin", "admin_project2@gmail.com", "$2y$10$BIW6Gbf4BwQi9o4wgTEYaeGOJOajE/zzvKOyW8rAgmK6RegVzqp4K"),
	("Sarkar", "sarkar_project1@gmail.com", '$2y$10$SfGoP/hnFaB114Fe3N0mMuGB8KJGvBqTAhvCAAJvSTcF59K4amyqy'),
	("Martha", "mar_project1@gmail.com", '$2y$10$SfGoP/hnFaB114Fe3N0mMuGB8KJGvBqTAhvCAAJvSTcF59K4amyqy');
	-- $2y$10$SfGoP/hnFaB114Fe3N0mMuGB8KJGvBqTAhvCAAJvSTcF59K4amyqy
	-- $2y$10$123837073030000999999uOuK3y93L93VMMOuY7NLPrzDV4GcFQD6


INSERT INTO user_details(u_id, fname, lname, birthday, gender, address)
	VALUES (1, "Badree", "Dahal", "1999-02-26", "male", "266 marrickville rd, Marrickville, 2204 NSW"),
	(3, "Sarkar", " ", "1995-10-12", "male", "10 George St, Clyde, 2140 NSW"),
	(4, "Martha", "FAR","1997-07-22", "female", "7 Russel St, Strathfield, 2503 NSW");


INSERT INTO background_checks(detail_id, bg_status, admin_aprv)
	VALUES (1, true, true),
	(2, true, null),
	(3, false, null);

INSERT INTO inquiry(user_id, subject, message)
	VALUES (1, "Regarding my recent application", "Hi there, I have concerns about my receny application. I had applied for my address validation a week ago, but haven't got any respose regarding it. Could you please let me know reason behind the delay. Thank you!"),
	(3, "Photo Card", "Hello, I have applied for my address verification couple days ago. I now realize that the photo id I have provided is wrong. I mistakenly put my brother's id. Is it possible to change it now?");

COMMIT;


