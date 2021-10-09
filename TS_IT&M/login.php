<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$login_errors = '';
if (isset($_POST['login'], $_POST['email'], $_POST['password'])) {
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE email = ?');
    $stmt->execute([ $_POST['email'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($account && password_verify($_POST['password'], $account['password'])) {
 
        $_SESSION['account_loggedin'] = TRUE;
        $_SESSION['account_id'] = $account['id'];
        $_SESSION['account_role'] = $account['role'];
        $_SESSION['account_email'] = $account['email'];

        header('Location: tickets.php');
        exit;
    } else {
        $login_errors = 'Incorrect email and/or password!';
    }
}

$register_errors = [];

if (isset($_POST['register'], $_POST['name'], $_POST['password'], $_POST['email'])) {
 
    if (empty($_POST['name']) || empty($_POST['password']) || empty($_POST['email'])) {
    
    	$register_errors[] = 'Please complete the registration form!';
    }

    if (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    	$register_errors[] = 'Email is not valid!';
    }
    
    if (!preg_match('/^[a-zA-Z0-9 ]+$/', $_POST['name'])) {
        $register_errors[] = 'Name is not valid!';
    }

    if (strlen($_POST['password']) > 20 || strlen($_POST['password']) < 5) {
    	$register_errors[] = 'Password must be between 5 and 20 characters long!';
    }
   
    if (!$register_errors) {
      
        $stmt = $pdo->prepare('SELECT id, password FROM accounts WHERE email = ?');
        $stmt->execute([ $_POST['email'] ]);
        $account = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($account) {
        	
        	$register_errors[] = 'Name and/or email exists!';
        } else {
        
        	$stmt = $pdo->prepare('INSERT INTO accounts (name, password, email) VALUES (?, ?, ?)');
        	
        	$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        	$stmt->execute([ $_POST['name'], $password, $_POST['email'] ]);
          
            $_SESSION['account_loggedin'] = TRUE;
            $_SESSION['account_id'] = $pdo->lastInsertId();
            $_SESSION['account_role'] = 'Member';
            $_SESSION['account_email'] = $_POST['email'];
        
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

</div>
