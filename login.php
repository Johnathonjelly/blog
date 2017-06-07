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
        } else {
            $_SESSION['uid'] = $results['userID'];

            $msgs[] = "Success! UserID = {$_SESSION['uid']} ";
            header('Location: index.php');
        }
    }
}
$title = 'Login';
require_once('head.php');
?>
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
        <input type="text" id="username" name="username" placeholder="Username" value="<?=htmlentities($un);?>" autofocus="autofocus">
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
  </body>
</html>
