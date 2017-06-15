<?php 
require('includes/dbconn.php');
session_start();
$postID = isset($_GET['postID']) ? $_GET['postID'] : -1;
$userID = isset($_SESSION['uid']) ? $_SESSION['uid'] : -1;

//insert response if submit button pressed
if (isset($_POST['didSubmit'])) {
    $response = isset($_POST['msg']) ? $_POST['msg'] : '';
    $connect = new Connection;
    $connection = $connect->getConnection();
    $sql = $connection->prepare('CALL sp_addResponse(?, ?, ?)');
    $sql->execute([$response, $postID, $userID]);
 }


//show active calling sp_getActive returns all active posts in descending order
//use this stored procedure to return the most recent post and display it as the page body if 
//a query string is not present
function showActive() {
    $connect = new Connection;
    $connection = $connect->getConnection();
    $sql = $connection->prepare('CALL sp_getActive()');
    $sql->execute();
    $results = $sql->fetchAll();
    echo "{$results[0]['body']}";
}

function getPost($postID) {
    $connect = new Connection;
    $connection = $connect->getConnection();
    $sql = $connection->prepare('CALL sp_getPost(?)');
    $sql->execute(array($postID));
    $results = $sql->fetch();
    echo "{$results['body']}";
}

function showResponse() {
    $connect = new Connection;
    $connection = $connect->getConnection();
    $sql = $connection->prepare('CALL sp_getResponse');
    $sql->execute([$postID]);
    $results = $sql->fetchAll();
    //get response from users and display
    for ($i = 0; $i < count($results); $i += 1) {
        echo "<dd><label>{$results[$i]['userName']}</label></dd>
            <dt>{$results[$i]['message']}</dt>";
    }
}

//show all posts function
//used to get the title and use that as user nav to see other posts
function showPostList() {
    $connect = new Connection;
    $connection = $connect->getConnection();
    $sql = $connection->prepare('CALL sp_getActive()');
    $sql->execute();
    $results = $sql->fetchAll();
    for ($i = 0; $i < count($results); $i += 1) {
    echo 
        "<ul>
            <li><a href='{$_SERVER['SCRIPT_NAME']}?postID={$results[$i]['postID']}&date={$results[$i]['dateSubmitted']}'>{$results[$i]['title']}</a></li>
        </ul>";
    }
}

?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/master.css">
    <title>Home</title>
</head>

<body>
    <div class="container">
        <div class="top-container box">
            <header>
                <h2>welcome msg</h2>
                <?php include 'includes/nav.php'?>
            </header>
        </div>
        <!--end top container-->
        <main class="main-content box">
            <div>
                <?php
                    if ($postID < 0) {
                        //if there is a query string show the post with the ID relating to query string
                        showActive();
                    } else {
                        //else show the most recently submitted post
                        getPost($postID);
                    }
                ?>
            </div>
            <aside class="posts-list">
                <?php showPostList()?>
            </aside>
        </main>
        <div class="messages">
            <dl>
                <dt><label for="response">Response</label></dt>
                <dd><textarea id="response" name="msg" cols="45" rows="8" required></textarea></dd>
                <dt></dt>
                <dd><button type="submit" name="didSubmit" value="1">Save</button></dd>
                <?php
                    // showResponse(); 
                ?>
            </dl>
        </div><!--end messages-->
    </div><!--end main container-->

<?php 
include 'includes/foot.php';
?>