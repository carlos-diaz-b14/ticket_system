<?php
include 'main.php';
// Default input account values
$micro = [
    'username' => '',
    'password' => '',
    'depa' => ''
    
];
if (isset($_GET['id'])) {
    // Retrieve the account from the database
    $stmt = $pdo->prepare('SELECT * FROM micro WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $nas = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing account
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the account
     
        $stmt = $pdo->prepare('UPDATE micro SET username = ?, password = ?, depa = ? WHERE id = ?');
        $stmt->execute([ $_POST['username'], $_POST['password'], $_POST['depa'], $_GET['id'] ]);
        header('Location: microsoft.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the item
        $stmt = $pdo->prepare('DELETE FROM micro WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: microsoft.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO micro (username,password,depa) VALUES (?,?,?)');
        $stmt->execute([ $_POST['username'], $_POST['password'], $_POST['depa'] ]);
        header('Location: microsoft.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' 
Microsoft Account', 'micro')?>

<h2><?=$page?>Microsoft Accounts</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" value="<?=$micro['username']?>" required>

        <label for="device">Password</label>
        <input type="text" id="password" name="password" placeholder="Password" value="<?=$micro['password']?>" required>

        <label for="ip">Department</label>
        <input type="text" id="depa" name="depa" placeholder="Department" value="<?=$micro['depa']?>" required>
        
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
