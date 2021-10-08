<?php
session_start();
include_once 'config.php';

function pdo_connect_mysql() {
    try {
    
    	$pdo = new PDO('mysql:host=' . db_host . ';dbname=' . db_name . ';charset=' . db_charset, db_user, db_pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $exception) {
    
    	exit('Failed to connect to database!');
    }
    return $pdo;
}
// Send ticket email function
function send_ticket_email($email, $id, $title, $msg, $priority, $category, $private, $status, $type = 'create') {
    // Ticket create subject
	$subject = 'Your ticket has been created';
    // Ticket update subject
    $subject = $type == 'update' ? 'Your ticket has been updated' : $subject;
    // Ticket comment subject
    $subject = $type == 'comment' ? 'Someone has commented on your ticket' : $subject;
    // Mail headers
	$headers = 'From: ' . mail_from . "\r\n" . 'Reply-To: ' . mail_from . "\r\n" . 'Return-Path: ' . mail_from . "\r\n" . 'X-Mailer: PHP/' . phpversion() . "\r\n" . 'MIME-Version: 1.0' . "\r\n" . 'Content-Type: text/html; charset=UTF-8' . "\r\n";
    // Ticket URL
    $link = view_ticket_link . '?id=' . $id . '&code=' . md5($id . $email);
    // Include the ticket email template as a string
    ob_start();
    include 'ticket-email-template.php';
    $ticket_email_template = ob_get_clean();
    // Send ticket email
	mail($email, $subject, $ticket_email_template, $headers);
}
// Template header
function template_header($title) {
$login_link = isset($_SESSION['account_loggedin']) ? '<a href="logout.php"><i class="fas fa-sign-out-alt"></i>Logout</a>' : '<a href="login.php"><i class="fas fa-lock"></i>Login</a>';
$admin_link = isset($_SESSION['account_loggedin']) && $_SESSION['account_role'] == 'Admin' ? '<a href="admin/index.php" target="_blank"><i class="fas fa-cog"></i>Admin</a>' : '';
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
        <meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>$title</title>
		<link href="style.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
    <nav class="navtop">
    	<div>
    		<h1><a href="index.php">IT & Maintenance System</a></h1>
          
            $login_link
            $admin_link
    	</div>
    </nav>
EOT;
}
// Template footer
function template_footer() {
echo <<<EOT
    </body>
</html>
EOT;
}
// Template admin header
function template_admin_header($title, $selected = 'dashboard') {
    $admin_links = '
        <a href="index.php"' . ($selected == 'dashboard' ? ' class="selected"' : '') . '><i class="fas fa-tachometer-alt"></i>Dashboard</a>
        <a href="tickets.php"' . ($selected == 'tickets' ? ' class="selected"' : '') . '><i class="fas fa-ticket-alt"></i>Tickets</a>
        <a href="comments.php"' . ($selected == 'comments' ? ' class="selected"' : '') . '><i class="fas fa-comments"></i>Comments</a>
        <a href="accounts.php"' . ($selected == 'accounts' ? ' class="selected"' : '') . '><i class="fas fa-users"></i>Accounts</a>
        <a href="categories.php"' . ($selected == 'categories' ? ' class="selected"' : '') . '><i class="fas fa-list"></i>Categories</a>
        <a href="emailtemplate.php"' . ($selected == 'emailtemplate' ? ' class="selected"' : '') . '><i class="fas fa-envelope"></i>Email Templates</a>
          <a href="inventory.php"' . ($selected == 'inventory' ? ' class="selected"' : '') . '><i class="fas fa-university"></i>General Inventory</a>
            <a href="ip.php"' . ($selected == 'ip' ? ' class="selected"' : '') . '><i class="fas fa-rss"></i>Static IP</a>
              <a href="nas.php"' . ($selected == 'nas' ? ' class="selected"' : '') . '><i class="fas fa-server"></i>NAS</a>
              <a href="microsoft.php"' . ($selected == 'microsoft' ? ' class="selected"' : '') . '><i class="fas fa-briefcase"></i>Microsoft Accounts</a>
               <a href="booking.php"' . ($selected == 'booking' ? ' class="selected"' : '') . '><i class="fas fa-book"></i>Booking System</a>
               <a href="shaw.php"' . ($selected == 'shaw' ? ' class="selected"' : '') . '><i class="fas fa-phone"></i>Shaw Smart Voice</a>
               <a href="pass.php"' . ($selected == 'pass' ? ' class="selected"' : '') . '><i class="fas fa-key"></i>Passwords</a>
        <a href="settings.php"' . ($selected == 'settings' ? ' class="selected"' : '') . '><i class="fas fa-tools"></i>Settings</a>
    ';
echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
		<title>$title</title>
		<link href="admin.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body class="admin">
        <aside class="responsive-width-100 responsive-hidden">
            <h1>Admin Panel</h1>
            $admin_links
        </aside>
        <main class="responsive-width-100">
            <header>
                <a class="responsive-toggle" href="#">
                    <i class="fas fa-bars"></i>
                </a>
                <div class="space-between"></div>
                <a href="about.php" class="right"><i class="fas fa-question-circle"></i></a>
                <a href="logout.php" class="right"><i class="fas fa-sign-out-alt"></i></a>
            </header>
EOT;
}
// Template admin footer
function template_admin_footer() {
echo <<<EOT
        </main>
        <script>
        document.querySelector(".responsive-toggle").onclick = function(event) {
            event.preventDefault();
            let aside = document.querySelector("aside"), main = document.querySelector("main"), header = document.querySelector("header");
            let asideStyle = window.getComputedStyle(aside);
            if (asideStyle.display == "none") {
                aside.classList.remove("closed", "responsive-hidden");
                main.classList.remove("full");
                header.classList.remove("full");
            } else {
                aside.classList.add("closed", "responsive-hidden");
                main.classList.add("full");
                header.classList.add("full");
            }
        };
        </script>
    </body>
</html>
EOT;
}
?>
