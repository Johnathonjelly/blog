<?php
//Author: Johnathon Southworth
//Class: CS296 PHP Jeff Miller
//Final -- Blog
session_start();
require_once('includes/dbconn.php');
if ($_SESSION['admin'] !== 1) {
  header('Location: login.php');
}
$connect = new Connection;
$connection = $connect->getConnection();
// var_dump($_SESSION);  
$msgs = array();
/********************************************
****************BEGIN INSERT LOGIC*****************/
if (isset($_POST['didSubmit'])) {
  $blogTitle = isset($_POST['title']) ? $_POST['title'] : '';
  $blogBody = isset($_POST['bodyContent']) ? $_POST['bodyContent'] : '';
  $blogTags = isset($_POST['tags']) ? $_POST['tags'] : '';
  $blogActive = isset($_POST['active']) ? $_POST['active'] : '';
  $blogIMG =  isset($_POST['imgRef']) ? $_POST['imgRef'] : '';

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
      $msgs[] = "Make sure you check if post is active or not.";
    }



//if no msgs in msg array we are clear to do stored procedures
    if (count($msgs) === 0) {
      $sql = $connection->prepare('CALL sp_addPost(?, ?, ?, ?)');
      $sql->execute(array($blogTitle, $blogBody, $blogIMG, $blogActive));
      $results = $sql->fetch();
      $postID = $results['postID'];

      //tags logic
      $tagArr = explode(' ', $blogTags);
      foreach($tagArr as $tag) {
        $sql = $connection->prepare('CALL sp_addTag(?, ?)');
        $sql->execute(array($tag, $postID));
      }
    }    
  foreach($msgs as $msg) {
      echo $msg;
  }
}
/**************************
*********ACTION LOGIC*******/
if (isset($_POST['delete'])) {
  //delete stuff
  echo "<strong>DELETE PRESSED</strong>";
} else if(isset($_POST['update'])) {
  //update stuff
    echo "<strong>UPDATE PRESSED</strong>";
}
?>
  <html lang="en">

  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Admin Panel</title>
  </head>

  <body>
    <fieldset>
      <legend>Blog Post</legend>
      <form class="admin-form" action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
        <dl>
          <dt><label for="title">Title</label></dt>
          <dd><input type="text" id="title" name="title" placeholder="Title"></dd>
          <dt><label for="bodyContent">Content</label></dt>
          <dd><textarea id="bodyContent" name="bodyContent" rows="60" cols="140"></textarea></dd>
          <dt><label for="tags">Tags</label></dt>
          <dd><input id="tags" type="text" name="tags" placeholder="js php react elixir books"></dd>
          <dt><label for="active">Active</label></dt>
          <dd><input type="checkbox" id="active" name="active"></dd>
          <dt><label for="imgRef">Image Reference</label></dt>
          <dd><input type="text" id="imgRef" name="imgRef"></dd>
          <button type="submit" name="didSubmit" value="1">Submit</button>
        </dl>
      </form>
    </fieldset>
    <button type="button" class="allEvents" name="activeEvents">Show All Posts</button>
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

    <?php
      // append all posts to a table
      $connection = $connect->getConnection();
      $sql = $connection->prepare('CALL sp_getAllPosts()');
      $sql->execute();
      $results = $sql->fetchAll();
    ?>
    <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
  <?php
    foreach ($results as $post) {
    $blogTitles = htmlentities(isset($post["title"]) ? $post["title"] : "");
    $postID = $post['postID'];
      echo "<tr><td>{$blogTitles}</td>
      <td>{$post['active']}</td>
      <td><button type=\"submit\" name=\"update\" value=\"$postID\">Update</button> 
      <button type=\"submit\" name=\"delete\" value=\"delete\">Delete</button></td>";
    }
    //save links for later just incase
      // <td><a href='update.php?blogID={$post['postID']}'>Update</a> ||
      // <a href='delete.php?blogID={$post['postID']}'>Delete</a></td></tr>
  ?>
    </form>
  </tbody>
</table>

    <!--<script type="text/javascript" src="js/main.js?v=1.13"></script>-->
<?php 
include 'includes/nav.php';
include 'includes/foot.php';
?>