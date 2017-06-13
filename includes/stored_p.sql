USE blog;
DELIMITER //

DROP PROCEDURE IF EXISTS sp_login;
CREATE PROCEDURE sp_login(
  IN un VARCHAR(200),
  IN pwd VARCHAR(360)
)BEGIN
SELECT * FROM users WHERE un = userName AND pwd = password;
END;

DROP PROCEDURE IF EXISTS sp_addUser;
CREATE PROCEDURE sp_addUser(
  IN newFirstName VARCHAR(150),
  IN newUserName VARCHAR(250),
  IN newPassword VARCHAR(360),
  IN newEmail VARCHAR(200)
)BEGIN
  INSERT INTO users  (firstName, userName, password, email) VALUES (
  newFirstName, 
  newUserName, 
  newPassword, 
  newEmail
  );
END;


DROP PROCEDURE IF EXISTS sp_addPost;
CREATE PROCEDURE sp_addPost(
IN title VARCHAR(200),
IN body TEXT,
IN img VARCHAR(300),
IN active BIT
)BEGIN
 INSERT INTO posts(title, body, imgRef, active) VALUES (
   title,
   body,
   img,
   active
 );
 SELECT LAST_INSERT_ID() AS postID;
END;

DROP PROCEDURE IF EXISTS sp_getPost;
CREATE PROCEDURE sp_getPost(
  IN insertPostID INT(11)
)BEGIN
  SELECT title, body
  FROM posts 
  WHERE posts.postID = insertPostID  
  ORDER BY posts.timeSubmit ASC;
END;

DROP PROCEDURE IF EXISTS sp_getAllPosts;
CREATE PROCEDURE sp_getAllPosts(
)BEGIN
  SELECT title, body, active, timeSubmit, postID
  FROM posts 
  ORDER BY posts.timeSubmit DESC;
END;

DROP PROCEDURE IF EXISTS sp_updatePost;
CREATE PROCEDURE sp_updatePost(
  IN newTitle VARCHAR(200),
  IN newBody TEXT,
  IN newActive BIT, 
  IN newImgRef VARCHAR(300),
  IN postIDs INT(11)
)BEGIN
  UPDATE posts
  SET title = newTitle,
  body = newBody,
  active = newActive,
  imgRef = newImgRef
  WHERE postID = postIDs;
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
CREATE PROCEDURE sp_deletePost(
  IN blogID INT(11)
)BEGIN
DELETE FROM posts
WHERE postID = blogID;
END;

DROP PROCEDURE IF EXISTS sp_getActive;
CREATE PROCEDURE sp_getActive(
)BEGIN
  SELECT title, body
  FROM posts 
  WHERE posts.active = 1
  ORDER BY timeSubmit ASC;
END;



DROP PROCEDURE IF EXISTS sp_addTag;
CREATE PROCEDURE sp_addTag(
  IN newTag VARCHAR(75),
  IN newPostID INT(11)
)
BEGIN
  DECLARE newTagID INT(11);
  IF (SELECT COUNT(*) FROM tags WHERE tags.tag = newTag) = 0 THEN
    INSERT INTO tags (tag) VALUES (newTag);
  END IF;
	SET newTagID = (SELECT tagID FROM tags WHERE tags.tag = newTag LIMIT 1);
  IF (SELECT COUNT(*) FROM postsTags WHERE postsTags.postID = newPostID AND postsTags.tagID = newTagID) = 0 THEN 
    INSERT INTO postsTags (postID, tagID) VALUES (newPostID, newTagID);
  END IF;
  SELECT newTagID;
END;

DROP PROCEDURE IF EXISTS sp_getTags;
CREATE PROCEDURE sp_getTags(
  IN inPostID INT(11)
)BEGIN
  SELECT tag
  FROM tags JOIN postsTags on tags.tagID = postsTags.tagID 
  WHERE postsTags.postID = inPostID;
END;

-- DROP PROCEDURE IF EXISTS sp_getPostForUpdate;
-- CREATE PROCEDURE sp_getPostForUpdate(
--   IN blogID INT(11)
-- )BEGIN
-- SELECT title, body, active, imgRef, tags, postsID
-- FROM times JOIN events ON times.eventID = events.eventID
-- WHERE events.eventID = eventIDs;
-- END;
//
DELIMITER ;
