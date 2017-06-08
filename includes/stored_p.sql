USE blog;
DELIMITER //

DROP PROCEDURE IF EXISTS sp_login;

CREATE PROCEDURE sp_login(
  IN un VARCHAR(200),
  IN pwd VARCHAR(360)
)BEGIN
SELECT * FROM users WHERE un = userName AND pwd = password;
END;


DROP PROCEDURE IF EXISTS sp_addPost;

CREATE PROCEDURE sp_addPost(
IN title VARCHAR(200),
IN body TEXT,
IN img VARCHAR(300),
IN active BIT
)BEGIN
 INSERT INTO events(title, body, imgRef, active) VALUES (
   title,
   body,
   tags,
   img,
   active
 );
 SELECT LAST_INSERT_ID() AS eventID;
END;

DROP PROCEDURE IF EXISTS sp_addTags(
  IN tagA VARCHAR(90),
  IN tagB VARCHAR(90),
  IN tagC VARCHAR(90)
)

DROP PROCEDURE IF EXISTS sp_getActivePosts;
CREATE PROCEDURE sp_getPosts(
  IN id INT
)BEGIN
  SELECT title, body, active
  FROM times JOIN events ON times.eventID = events.eventID
    WHERE MONTH(times.eventTime) = month AND YEAR(times.eventTime) = year;
END;

DROP PROCEDURE IF EXISTS sp_getAllEvents;
CREATE PROCEDURE sp_getAllEvents(
)BEGIN
  SELECT eventTime, title, description, url, location, active, events.eventID
  FROM times JOIN events ON times.eventID = events.eventID
  ORDER BY times.eventTime DESC;
END;

DROP PROCEDURE IF EXISTS sp_updateEvent;
CREATE PROCEDURE sp_updateEvent(
  IN title VARCHAR(250),
  IN description TEXT,
  IN url VARCHAR(250),
  IN location VARCHAR(250),
  IN active BIT,
  IN eventIDs INT(11)
)BEGIN
  UPDATE events
  SET title = title,
  description = description,
  url = url,
  location = location,
  active = active
  WHERE eventID = eventIDs;
END;

DROP PROCEDURE IF EXISTS sp_updateTags;
CREATE PROCEDURE sp_updateTags(
  IN tagIDs INT(11),
  IN tags VARCHAR(175)
)BEGIN
  UPDATE tags
  SET tags = tags
  WHERE tagID = tagIDs;
END;

DROP PROCEDURE IF EXISTS sp_deletePost;
CREATE PROCEDURE sp_deleteEvent(
  IN blogID INT(11)
)BEGIN
DELETE FROM posts
WHERE postID = blogID;
END;

DROP PROCEDURE IF EXISTS sp_getActive;
CREATE PROCEDURE sp_getActive(
)BEGIN
SELECT  title, body
FROM posts 
WHERE posts.active = 1
ORDER BY timestamp ASC;
END;

DROP PROCEDURE IF EXISTS sp_getTags(
  IN tagIDs,
  IN blogIDs
)

DROP PROCEDURE IF EXISTS sp_getPostForUpdate;
CREATE PROCEDURE sp_getEventForUpdate(
  IN blogID INT(11)
)BEGIN
SELECT title, body, active, imgRef, tags, postsID
FROM times JOIN events ON times.eventID = events.eventID
WHERE events.eventID = eventIDs;
END;
//
DELIMITER ;
