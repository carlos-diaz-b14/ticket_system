<?php
include 'main.php';
// Default input account values
$inventoryadd = [
    'hardware' => '',
    'brand' => '',
    'qty' => '',
    'campus' => '',
    'classroom' => ''
];
if (isset($_GET['id'])) {
    // Retrieve the account from the database
    $stmt = $pdo->prepare('SELECT * FROM inventory WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $inventoryadd = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing account
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the account
     
        $stmt = $pdo->prepare('UPDATE inventory SET hardware = ?, brand = ?, qty = ?, campus = ?, classroom = ? WHERE id = ?');
        $stmt->execute([ $_POST['hardware'], $_POST['brand'], $_POST['qty'], $_POST['campus'], $_POST['classroom'], $_GET['id'] ]);
        header('Location: inventory.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the item
        $stmt = $pdo->prepare('DELETE FROM inventory WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: inventory.php');
        exit;
    }
} else {
    // Create a new account
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO inventory (hardware,brand,qty,campus,classroom) VALUES (?,?,?,?,?)');
        $stmt->execute([ $_POST['hardware'], $_POST['brand'], $_POST['qty'], $_POST['campus'], $_POST['classroom'] ]);
        header('Location: inventory.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' inventory', 'inventoryadd')?>

<h2><?=$page?> Item</h2>
  
<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="hardware">Hardware</label>
        <input type="text" id="hardware" name="hardware" placeholder="hardware" value="<?=$inventoryadd['hardware']?>" required>

        <label for="brand">Brand</label>
        <input type="text" id="brand" name="brand" placeholder="brand" value="<?=$inventoryadd['brand']?>" required>

        <label for="qty">QTY</label>
        <input type="num" id="qty" name="qty" placeholder="Quantity" value="<?=$inventoryadd['qty']?>" required>

        <label for="campus">Campus</label>
        <select name="campus" id="campus" style="margin-bottom: 30px;">
            <option value="main"<?=$inventoryadd['campus']=='main'?' selected':''?>>Main</option>
            <option value="tech"<?=$inventoryadd['campus']=='tech'?' selected':''?>>Tech</option>
        </select>
        <label for="classroom">Location</label>
        <input type="text" id="classroom" name="classroom" placeholder="Classroom" value="<?=$inventoryadd['classroom']?>">
        
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>



<?=template_admin_footer()?>
