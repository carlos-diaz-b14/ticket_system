<?php
include 'main.php';
// Default input account values
$shaw = [
    'username' => '',
    'userid' => '',
    'password' => '',
    'ext' => '',
     'depa' => ''
    
];
if (isset($_GET['id'])) {
    // Retrieve the account from the database
    $stmt = $pdo->prepare('SELECT * FROM shaw WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $shaw = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing account
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the account
     
        $stmt = $pdo->prepare('UPDATE shaw SET username = ?, userid = ?, password = ?, ext = ?, depa = ? WHERE id = ?');
        $stmt->execute([ $_POST['username'], $_POST['userid'], $_POST['password'], $_POST['ext'], $_POST['depa'], $_GET['id'] ]);
        header('Location: shaw.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the item
        $stmt = $pdo->prepare('DELETE FROM shaw WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: shaw.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO shaw (username,userid,password,ext,depa) VALUES (?,?,?,?,?)');
        $stmt->execute([ $_POST['username'], $_POST['userid'], $_POST['password'], $_POST['ext'], $_POST['depa'] ]);
        header('Location: shaw.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' 
Shaw Smart Voice', 'shaw')?>

<h2><?=$page?> Shaw Smart Voice</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" value="<?=$shaw['username']?>" required>

         <label for="username">UserID</label>
        <input type="text" id="userid" name="userid" placeholder="userid" value="<?=$shaw['userid']?>" required>

        <label for="device">Password</label>
        <input type="text" id="password" name="password" placeholder="Password" value="<?=$shaw['password']?>" required>

        <label for="ip">Extension Number</label>
        <input type="text" id="ext" name="ext" placeholder="ext" value="<?=$shaw['ext']?>" required>
          <label for="ip">Department</label>
        <input type="text" id="depa" name="depa" placeholder="Department" value="<?=$shaw['depa']?>" required>
        <div class="submit-btns">

            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
