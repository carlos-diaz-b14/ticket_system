<?php
include 'functions.php';
// Connect to MySQL using the below function
$pdo = pdo_connect_mysql();
// Error output variable
$login_errors = '';
// User authentication
if (isset($_POST['login'], $_POST['email'], $_POST['password'])) {
    // Retrieve the account from the database
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    // Check if account exists and the password is correct
    if ($account && password_verify($_POST['password'], $account['password'])) {
        // Declare the session variables. Session variable we'll use to determine whether a user is logged-in
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account['id'];
        $_SESSION['account_role'] = $account['role'];
        $_SESSION['account_email'] = $account['email'];
        // Redirect to the tickets page
        header('Location: tickets.php');
        exit;
    } else {
        $login_errors = 'Incorrect email and/or password!';
    }
}
// Error output variable
$register_errors = [];
// User registration
if (isset($_POST['register'], $_POST['name'], $_POST['password'], $_POST['email'])) {
    // Make sure the submitted registration values are not empty.
    if (empty($_POST['name']) || empty($_POST['password']) || empty($_POST['email'])) {
    	// One or more values are empty.
    	$register_errors[] = 'Please complete the registration form!';
    }
    // Check to see if the email is valid.
    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    	$register_errors[] = 'Email is not valid!';
    }
    // Name must contain only characters and numbers.
    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $_POST['name'])) {
        $register_errors[] = 'Name is not valid!';
    }
    // Password must be between 5 and 20 characters long.
    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    	$register_errors[] = 'Password must be between 5 and 20 characters long!';
    }
    // IF there are no errors...
    if (!$register_errors) {
        // Check if the account with that email already exist
        $stmt = $pdo->prepare('SELECT id, password FROM accounts WHERE email = ?');
        $stmt->execute([ $_POST['email'] ]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        // Check if the account exists in the database
        if ($account) {
        	// Name and/or email already exist
        	$register_errors[] = 'Name and/or email exists!';
        } else {
        	// Email doesn't exist, insert new account...
        	$stmt = $pdo->prepare('INSERT INTO accounts (name, password, email) VALUES (?, ?, ?)');
        	// We do not want to expose passwords in our database and therefore we shall hash the password
        	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        	$stmt->execute([ $_POST['name'], $password, $_POST['email'] ]);
            // Automatically authenticate the user
            $_SESSION['account_loggedin'] = TRUE;
            $_SESSION['account_id'] = $pdo->lastInsertId();
            $_SESSION['account_role'] = 'Member';
            $_SESSION['account_email'] = $_POST['email'];
            // Redirect to the tickets page
            header('Location: tickets.php');
            exit;
        }
    }
}
?>
<?=template_header('Login')?>

<div class="content update login" >

    <div class="con" style= "margin-left:auto; margin-right:auto; border:all; border-color:black;  border-style: solid;">

    	<h2>Login</h2>

        <form action="" method="post">
            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="Email" required>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Password" required>
            <input type="submit" name="login" style= "margin-left:auto; margin-right:auto;">
            <p><?=$login_errors?></p>
        </form>

    </div>
<!--
    <div class="con">

    	<h2>Register</h2>

        <form action="" method="post">
            <label for="name">Name</label>
            <input id="name" type="text" name="name" placeholder="Name" required>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" placeholder="Password" required>
            <label for="email">Email</label>
            <input id="email" type="email" name="email" placeholder="Email" required>
            <input type="submit" name="register">
            <p><?=implode('<br>', $register_errors)?></p>
        </form>

    </div>
-->
</div>
