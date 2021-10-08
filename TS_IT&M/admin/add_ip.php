<?php
include 'main.php';
// Default input account values
$add_ip = [
    'username' => '',
    'device' => '',
    'ip' => ''
    
];
if (isset($_GET['id'])) {
    // Retrieve the account from the database
    $stmt = $pdo->prepare('SELECT * FROM static_ip WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $add_ip = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing account
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the account
     
        $stmt = $pdo->prepare('UPDATE static_ip SET username = ?, device = ?, ip = ? WHERE id = ?');
        $stmt->execute([ $_POST['username'], $_POST['device'], $_POST['ip'], $_GET['id'] ]);
        header('Location: ip.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the item
        $stmt = $pdo->prepare('DELETE FROM static_ip WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: ip.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO static_ip (username,device,ip) VALUES (?,?,?)');
        $stmt->execute([ $_POST['username'], $_POST['device'], $_POST['ip'] ]);
        header('Location: ip.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' Static Ip', 'add_ip')?>

<h2><?=$page?> IP</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="Username" value="<?=$add_ip['username']?>" required>

        <label for="device">Device Type</label>
        <input type="text" id="device" name="device" placeholder="Device" value="<?=$add_ip['device']?>" required>

        <label for="ip">IP Address</label>
        <input type="ip" id="ip" name="ip" placeholder="IP" value="<?=$add_ip['ip']?>" required>
        
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
