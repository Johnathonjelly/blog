-- CREATE DATABASE blog;
use blog;

-- DROP TABLE IF EXISTS tags;
-- DROP TABLE IF EXISTS posts;
-- DROP TABLE IF EXISTS postsTags;
-- DROP TABLE IF EXISTS users;
-- DROP TABLE IF EXISTS comments;

CREATE TABLE posts(
    postID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200),
    body TEXT,
    active BIT,
    imgRef VARCHAR(300),
    UNIQUE (title)
);

CREATE TABLE tags(
    tagsID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    tagA VARCHAR(90),
    tagB VARCHAR(90),
    tagC VARCHAR(90)
);

CREATE TABLE postsTags(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    postID INT NOT NULL,
    tagID INT NOT NULL,
    timestamp DATETIME DEFAULT NOW()
);

CREATE TABLE users(
    userID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    firstName VARCHAR(150) NOT NULL,
    userName VARCHAR(200) NOT NULL,
    email VARCHAR(250) NOT NULL,
    admin BIT,
    UNIQUE (userName, email)
);

CREATE TABLE comments(
    commentID INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    userID INT NOT NULL, 
    timestamp DATETIME DEFAULT NOW(),
    message TEXT
);

