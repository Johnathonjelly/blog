<?php
session_start();
$msgs = array();
$email = '';
$pwd = '';
if (isset($_POST['didSubmit'])) {
    $un = isset($_POST['userName']) ? $_POST['userName'] : '';
    $firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $pwd = isset($_POST['password']) ? $_POST['password'] : '';

if (empty($un) || empty($pwd)) {
    $msgs[] = "Username and password required.";
}
if (empty($firstName) || empty($email)) {
    $msgs[] = "First name and email required.";
}

  if(count($msgs) === 0) {
    require_once('includes/dbconn.php');
    $connect = new Connection;
    $connection = $connect->getConnection();
    $sql = $connection->prepare('CALL sp_addUser(?, ?, ?, ?)');
    $sql->execute(array($firstName, $un, $pwd, $email));
    
    if (headers_sent()) {
     die("Redirect failed. Please click on this link: <a href=login.php>Login</a>");
    } else {
    exit(header("Location: includes/home.php"));
    }
    header('Location: Login.php');
  }
}
 ?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="css/master.css">
    <title>Register</title>
</head>
<body>
    <h1 class ="text-center">Register</h1>
    <form class="register" action="register.php" method="post">
      <fieldset>
        <legend>Register</legend>
        <?php
          if (count($msgs) > 0) {
            echo '<ul>';
            foreach ($msgs as $errors) {
              echo "<li>$errors</li>";
            }
            echo "</ul>";
          }
         ?>
        <input type="hidden" name="submitted" id="submitted" value="1">
        <dl>
        <dt><label for="FirstName">First Name:*</label></dt>
        <dd><input id="firstName" type="firstName" name="firstName" placeholder="First Name" maxlength="150" required autofocus="autofocus"></dd>
        <dt><label for="userName">User Name:*</label></dt>
        <dd><input id="userName" type="text" name="userName" placeholder="User Name"></dd>
        <dt><label for="email">email:*</label></dt>
        <dd>
        <input id="email" type="text" name="email" placeholder="Email Address" maxlength="250" required />
        </dd>
        <dt><label for="password">Password:*</label></dt>
        <dd>
        <input type="password" name="password" placeholder="Password" maxlength="360" required />
        </dd>
        <dt></dt>
       <dd><input type="submit" name="didSubmit" value="Submit" /></dd>
        </dl>
        <a href="login.php">Login</a>
        <a href="logout.php">Logout?</a>
      </fieldset>

    </form>
  </body>
</html>
