<?php
//Author: Johnathon Southworth
//Class: CS296 PHP Jeff Miller
//Final -- Blog
$title = 'Admin'; //set head tag title  
require_once('head.php');
require_once('includes/dbconn.php'); 

session_start();
$msgs = array();
if (isset($_SESSION['uid'])) {
  $uid = $_SESSION['uid'];
} else {
  header('Location: login.php');
}

if (isset($_POST['didSubmit'])) {
  $eventTitle = isset($_POST['title']) ? $_POST['title'] : '';
  $eventDescription = isset($_POST['description']) ? $_POST['description'] : '';
  isset($_POST['timeAndDate']) ? $_POST['timeAndDate'] : '';
  $eventActive = isset($_POST['active']) ? $_POST['active'] : '';
  $eventURL =  isset($_POST['url']) ? $_POST['url'] : '';
  $eventLocation = isset($_POST['location']) ? $_POST['location'] : '';
  $dateTimes = isset($_POST['timeAndDate']) ? $_POST['timeAndDate'] : array();


//if * empty give correct messages
    if (empty($_POST['title'])) {
      $msgs[] = "The post must have a title. ";
    }
    if (empty($_POST['bodyContent'])) {
      $msgs[] = "The post must have body content. ";
    }

//if no msgs in msg array we are clear to do stored procedures
    if (count($msgs) === 0) {
      $connect = new Connection;
      $connection = $connect->getConnection();
      $sql = $connection->prepare('CALL sp_addPost(?, ?, ?)');
      $sql->execute(array($eventTitle, $eventDescription, $eventURL,   $eventActive, $eventLocation));
      $results = $sql->fetch();
      $postID = $results['postID'];

      for ($i = 0; $i < count($dateTimes); $i++) {
        $convertedTime = new DateTime($dateTimes[$i]);
        $sql = $connection->prepare('CALL sp_addTime(?, ?)');
        $sql->execute(array($eventID, $convertedTime->format('Y-m-d H:i:s')));
      }
    }
  }

foreach($msgs as $msg) {
    echo $msg;
}
?>
    <fieldset>
      <legend>Event</legend>
    <form class="admin-form" action="<?=$_SERVER['SCRIPT_NAME']?>" method="post">
      <dl>
        <dt><label for="title">Title</label></dt>
        <dd><input type="text" id="title" name="title" placeholder="Title"></dd>
        <dt><label for="bodyContent">Description</label></dt>
        <dd><textarea id="bodyContent" name="bodyContent" rows="8" cols="80"></textarea></dd>
        <dt><label for="tags">Tags</label></dt>
        <dd><input id="tags" type="text" name="tags" placeholder="js php react elixir books"></dd>
        <dt><label for="active">Active</label></dt>
        <dd><input type="checkbox" id="active" name="active"></dd>

    <input type="hidden" name="didSubmit" value="1">
    <button type="submit">Submit</button>
      </dl>
    </form>
    </fieldset>
<button type="button" class="allEvents" name="activeEvents">Show All Events</button>
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
    //append all events to a table
    $connect = new Connection;
    $connection = $connect->getConnection();
    $sql = $connection->prepare('CALL sp_getAllPosts()');
    $sql->execute();
    $results = $sql->fetchAll();
//sanatize all data
    foreach ($results as $info) {
    $blogTitles = htmlentities(isset($info["title"]) ? $info["title"] : "");
      echo "<tr><td>{$eventTitles}</td>
      <td>{$timeStamp}</td>
      <td>{$eventActiveORNot}</td>
      <td><a href='update.php?blogID={$info['blogID']}'>Update</a> ||
      <a href='delete.php?blogID={$info['blogID']}'>Delete</a></td></tr>";
    }
     ?>
   </tbody>
</table>
    <a href="logout.php">Logout?</a>
    <a href="index.php">Home</a>
    <script type="text/javascript" src="js/main.js?v=1.13"></script>
  </body>
</html>
