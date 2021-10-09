<?php
include 'main.php';

$account = [
    'name' => '',
    'password' => '',
    'email' => '',
    'role' => 'Member'
];
if (isset($_GET['id'])) {
  
    $stmt = $pdo->prepare('SELECT * FROM accounts WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $account = $stmt->fetch(PDO::FETCH_ASSOC);

    $page = 'Edit';
    if (isset($_POST['submit'])) {
      
        $password = $_POST['password'] == $account['password'] ? $_POST['password'] : password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $pdo->prepare('UPDATE accounts SET name = ?, password = ?, email = ?, role = ? WHERE id = ?');
        $stmt->execute([ $_POST['name'], $password, $_POST['email'], $_POST['role'], $_GET['id'] ]);
        header('Location: accounts.php');
        exit;
    }
    if (isset($_POST['delete'])) {
     
        $stmt = $pdo->prepare('DELETE FROM accounts WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: accounts.php');
        exit;
    }
} else {
  
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO accounts (name,password,email,role) VALUES (?,?,?,?)');
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt->execute([ $_POST['name'], $password, $_POST['email'], $_POST['role'] ]);
        header('Location: accounts.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' Account', 'accounts')?>

<h2><?=$page?> Account</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Name" value="<?=$account['name']?>" required>
        <label for="password">Password</label>
        <input type="password" id="password" name="password" placeholder="Password" value="<?=$account['password']?>" required>
        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="Email" value="<?=$account['email']?>" required>
        <label for="role">Role</label>
        <select name="role" id="role" style="margin-bottom: 30px;">
            <option value="Member"<?=$account['role']=='Member'?' selected':''?>>Member</option>
            <option value="Admin"<?=$account['role']=='Admin'?' selected':''?>>Admin</option>
        </select>
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
