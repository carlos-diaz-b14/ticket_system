<?php
include 'main.php';
// Default input account values
$book = [
    'username' => '',
    'user' => '',
    'password' => '',
    'program' => '',
    'ver' => ''
    
];
if (isset($_GET['id'])) {
    // Retrieve the account from the database
    $stmt = $pdo->prepare('SELECT * FROM book WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $book = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing account
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the account
     
        $stmt = $pdo->prepare('UPDATE book SET username = ?, user = ?, password = ?, program = ?, ver = ? WHERE id = ?');
        $stmt->execute([ $_POST['username'], $_POST['user'], $_POST['password'], $_POST['program'], $_POST['ver'], $_GET['id'] ]);
        header('Location: booking.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the item
        $stmt = $pdo->prepare('DELETE FROM book WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: booking.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO book (username,user,password,program,ver) VALUES (?,?,?,?,?)');
        $stmt->execute([ $_POST['username'], $_POST['user'], $_POST['password'], $_POST['program'], $_POST['ver'] ]);
        header('Location: booking.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' 
Booking System', 'book')?>

<h2><?=$page?> Booking System Account</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" value="<?=$book['username']?>" required>

         <label for="username">User</label>
        <input type="text" id="user" name="user" placeholder="User" value="<?=$book['user']?>" required>


        <label for="device">Password</label>
        <input type="text" id="password" name="password" placeholder="Password" value="<?=$book['password']?>" required>

        <label for="ip">Program</label>
        <input type="text" id="program" name="program" placeholder="program" value="<?=$book['program']?>" required>
          <label for="ip">Verification Code</label>
        <input type="text" id="ver" name="ver" placeholder="Verification Code" value="<?=$book['ver']?>" required>
        <div class="submit-btns">

            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
