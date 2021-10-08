<?php
include 'main.php';
// Default input account values
$pass = [
    'type' => '',
    'device' => '',
    'password' => '',
    'location' => '',
     'comments' => ''
    
];
if (isset($_GET['id'])) {
    // Retrieve the account from the database
    $stmt = $pdo->prepare('SELECT * FROM pass WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $pass = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing account
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the account
     
        $stmt = $pdo->prepare('UPDATE pass SET type = ?, device = ?, password = ?, location = ?, comments = ? WHERE id = ?');
        $stmt->execute([ $_POST['type'], $_POST['device'], $_POST['password'], $_POST['location'], $_POST['comments'], $_GET['id'] ]);
        header('Location: pass.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the item
        $stmt = $pdo->prepare('DELETE FROM pass WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: pass.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO pass (type,device,password,location,comments) VALUES (?,?,?,?,?)');
        $stmt->execute([ $_POST['type'], $_POST['device'], $_POST['password'], $_POST['location'], $_POST['comments'] ]);
        header('Location: pass.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' 
Passwords', 'pass')?>

<h2><?=$page?> Passwords</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="type">Password type</label>
        <input type="text" id="type" name="type" placeholder="type" value="<?=$pass['type']?>" required>

         <label for="device">Device name or Network Name</label>
        <input type="text" id="device" name="device" placeholder="device" value="<?=$pass['device']?>" required>

        <label for="password">Password</label>
        <input type="text" id="password" name="password" placeholder="Password" value="<?=$pass['password']?>" required>

        <label for="location">Location</label>
        <input type="text" id="location" name="location" placeholder="location" value="<?=$pass['location']?>" required>
          <label for="comments">Comments</label>
        <input type="text" id="comments" name="comments" placeholder="comments" value="<?=$pass['comments']?>" required>
        <div class="submit-btns">

            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
