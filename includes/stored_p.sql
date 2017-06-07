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
   img,
   active
 );
 SELECT LAST_INSERT_ID() AS eventID;
END;

DROP PROCEDURE IF EXISTS sp_addTags(

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

DROP PROCEDURE IF EXISTS sp_updateTime;
CREATE PROCEDURE sp_updateTime(
  IN eventTimes DATETIME,
  IN eventIDs INT(11)
)BEGIN
  UPDATE times
  SET eventTime = eventTimes
  WHERE eventID = eventIDs;
END;

DROP PROCEDURE IF EXISTS sp_deleteEvent;
CREATE PROCEDURE sp_deleteEvent(
  IN eventIDs INT(11)
)BEGIN
DELETE FROM events
WHERE eventID = eventIDs;
END;

DROP PROCEDURE IF EXISTS sp_getActive;
CREATE PROCEDURE sp_getActive(
  IN year CHAR(4),
  IN month VARCHAR(2),
  IN day VARCHAR(2)
)BEGIN
SELECT eventTime, title, description, url, location
FROM times JOIN events ON times.eventID = events.eventID
WHERE YEAR(times.eventTime) = year AND
MONTH(times.eventTime) = month AND
DAY(times.eventTime) = day AND events.active = 1
ORDER BY times.eventTime ASC;
END;

DROP PROCEDURE IF EXISTS sp_getEventForUpdate;
CREATE PROCEDURE sp_getEventForUpdate(
  IN eventIDs INT(11)
)BEGIN
SELECT title, description, url, location, active, eventTime, events.eventID
FROM times JOIN events ON times.eventID = events.eventID
WHERE events.eventID = eventIDs;
END;
//
DELIMITER ;
