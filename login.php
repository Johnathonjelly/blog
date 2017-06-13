<?php
session_start();
$msgs = array();
$un = ''; $pwd;
if (isset($_POST['didSubmit'])) {
    $un = isset($_POST['userName']) ? $_POST['userName'] : '';
    $pwd = isset($_POST['password']) ? $_POST['password'] : '';
    if (empty($un) || empty($pwd)) {
        $msgs[] = 'Username and password are required';
    }
    if (count($msgs) === 0) {
        require_once('includes/dbconn.php');
        $connect = new Connection;
        $connection = $connect->getConnection();
        $sql = $connection->prepare('CALL sp_login(?, ?)');
        $sql->execute(array($un, $pwd));
        $results = $sql->fetch();
        
        if ($results === false) {
            $msgs[] = 'Wrong username or password';
        } elseif ($results['admin'] == 0) {
            $_SESSION['uid'] = $results['userID'];
            $msgs[] = "Success! UserID = {$_SESSION['uid']} ";
           
            if (headers_sent()) {
                die("Redirect failed. Please click on this link: <a href=index.php>Home</a>");
                } else {
                    exit(header("Location: index.php"));
                }
        } elseif ($results['admin'] == 1) {
            $_SESSION['uid'] = $results['userID'];
            $_SESSION['admin'] = $results['admin'];
                if (headers_sent()) {
                    die("Redirect failed. Please click on this link: <a href=admin.php>Admin</a>");
                } else {
                    exit(header("Location: admin.php"));
                }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Login</title>
</head>
<body>
<div class="wrapper">
    <form class="login" action="<?=$_SERVER["SCRIPT_NAME"]?>" method="post">
    <fieldset>
        <legend>Login</legend>
<?php
    if (count($msgs) > 0) {
    echo '<ul>';
    foreach ($msgs as $error) {
        echo "<li><strong>$error</strong></li>";
    }
    echo "</ul>";
    }
?>
        <dl>
            <dt>
                <label for="username">Username</label>
            </dt>
            <dd>
                <input type="text" id="username" name="userName" placeholder="Username" value="<?=htmlentities($un);?>" autofocus="autofocus">
            </dd>
            <dt>
                <label for="password">Password</label>
            </dt>
            <dd>
                <input type="password" id="password" name="password" placeholder="Password">
            </dd>
        </dl>
        <button type="submit" name="submit">Submit</button>
        <input type="hidden" name="didSubmit" value="1">
        <hr>
        <a href="logout.php">Logout?</a>
        <a href="index.php">Home</a>
    </fieldset>
    </form>
        </div><!--end wrapper-->
        </fieldset>
      </form>
    </div>
<?php 
include 'includes/foot.php';
?>
