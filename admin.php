<?php
//Author: Johnathon Southworth
//Class: CS296 PHP Jeff Miller
//Final -- Blog
session_start();
require_once('includes/dbconn.php');
if ((int)$_SESSION['admin'] !== 1) {
  header("Location: login.php?msg={$_SESSION['admin']}");
}
$connect = new Connection;
$connection = $connect->getConnection();
// var_dump($_SESSION);  
$msgs = array();
$blogTitle = '';
$blogBody = '';
$blogTags = '';
$blogActive = 0;
$postID = -1;
var_dump($postID);

/**************************
*********ACTION LOGIC*******/
if (isset($_POST['delete'])) {
  $sql = $connection->prepare('CALL sp_deletePost(?)');
  $sql->execute(array($_POST['delete']));
  header("Location: admin.php?cmd=delete&success=true");
}  

function loadPost($postID) {
   global $blogTitle, $blogBody, $blogTags, $blogActive, $connection;
   $sql = $connection->prepare('CALL sp_getPost(?)');
   $sql->execute(array($postID));
   $results = $sql->fetch();
   if (!empty($results)) {
    $blogTitle = $results['title'];
    $blogBody = $results['body'];
    // $blogTags = $results['tag'];
    $blogActive = $results['active']; 
    }
}
if (isset($_POST['load'])) {
  $postID = $_POST['load'];
  loadPost($postID);
}
function getPostsTable() {
    // append all posts to a table
    global $connect;
    $connection = $connect->getConnection();
    $sql = $connection->prepare('CALL sp_getAllPosts()');
    $sql->execute();
    $results = $sql->fetchAll();

    foreach ($results as $post) {
      $blogTitle = htmlentities(isset($post["title"]) ? $post["title"] : "");
      $blogBody = htmlentities(isset($post["body"]) ? $post["body"] : "");
      $blogActive = htmlentities(isset($post["active"]) ? $post["active"] : "");
      $postID = $post['postID'];
        echo "<form action=\"admin.php\" method=\"post\">
        <tr><td>{$blogTitle}</td>
        <td>{$post['active']}</td>
        <td><button type=\"submit\" name=\"load\" value=\"$postID\">Load</button> 
        <button type=\"submit\" name=\"delete\" value=\"$postID\">Delete</button></td>
        </form>";
      }
    }

/********************************************
****************BEGIN INSERT LOGIC***********/

if (isset($_POST['didSubmit'])) {
  $blogTitle = isset($_POST['title']) ? $_POST['title'] : '';
  $blogBody = isset($_POST['bodyContent']) ? $_POST['bodyContent'] : '';
  $blogTags = isset($_POST['tags']) ? $_POST['tags'] : '';
  $blogActive = isset($_POST['active']) ? $_POST['active'] : '';

//if * empty give correct messages 
    if (empty($_POST['title'])) {
      $msgs[] = "<strong>The post must have a title. </strong>";
    }
    if (empty($_POST['bodyContent'])) {
      $msgs[] = "<strong>The post must have body content. </strong>";
    }
    if (empty($_POST['tags'])) {
      $msgs[] = "<strong>Put some tags in there.</strong>";
    }
    if (empty($_POST['active'])) {
      $msgs[] = "Check if post is active or not.";
    }

    if (count($msgs) === 0) {
      if ($postID == -1) {
        $sql = $connection->prepare('CALL sp_addPost(?, ?, ?)');
        $sql->execute(array($blogTitle, $blogBody, $blogActive));
        $results = $sql->fetch();
        $postID = $results['postID'];

      //right here delete all the tags in postsTags where postID 
      $sql = $connection->prepare('CALL sp_deleteTags(?)');
      $sql->execute(array($postID));
      //tags logic
        $tagArr = explode(' ', $blogTags);
        foreach($tagArr as $tag) {
          $sql = $connection->prepare('CALL sp_addTag(?, ?)');
          $sql->execute(array($tag, $postID));
        }
      } elseif ($postID > 0) {
        $sql = $connection->prepare('CALL sp_updatePost(?, ?, ?, ?)');
        $sql->execute(array($blogTitle, $blogBody, $blogActive, $postID));
      }   
  foreach($msgs as $msg) {
      echo $msg;
  }
      }   
}
?>

  <html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/master.css">
    <script src="js/jquery-3.2.1.min.js"></script>
    <script src="js/tinymce/tinymce.min.js"></script>
    <title>Admin Panel</title>
  </head>

  <body>
    <button type="button" class="showbutton">Show All Posts</button>
    <!--insert a table head and append info from DB in a loop populating the rows-->
    <table id="eventInfoTable" class="hidden">
      <thead>
        <tr>
          <th>Title</th>
          <th>Active</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php getPostsTable(); ?>
      </tbody>
    </table>
    <fieldset>
      <legend>Blog Post</legend>
      <form class="admin-form" action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
        <dl>
          <dt><label for="title">Title</label></dt>
          <dd><input type="text" id="title" name="title" placeholder="Title" value="<?=$blogTitle?>"></dd>
          <dt><label for="bodyContent">Content</label></dt>
          <dd><textarea id="bodyContent" name="bodyContent" rows="60" cols="140"><?=$blogBody?></textarea></dd>
          <input name="image" type="file" id="upload" class="hidden" onchange="">
          <dt><label for="tags">Tags</label></dt>
          <dd><input id="tags" type="text" name="tags" placeholder="js php react elixir books" value="<?=$blogTags?>"></dd>
          <dt><label for="active">Active</label></dt>
          <dd><input type="checkbox" id="active" name="active" <?php if ($blogActive) { 
            echo "checked=\"checked\""; } ?> </dd>
          <input type="hidden" name="postID" value="<?=$postID?>">
          <button type="submit" name="didSubmit" value="1">Submit</button>
        </dl>
      </form>
    </fieldset>

<?php 
include 'includes/nav.php'; ?>
  <script type="text/javascript" src="js/main.js"></script>
<?php 
include 'includes/foot.php';
?>