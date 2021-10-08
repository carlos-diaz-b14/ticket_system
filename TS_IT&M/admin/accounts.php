<?php
include 'main.php';
// Retrieve all the accounts
$stmt = $pdo->prepare('SELECT * FROM accounts');
$stmt->execute();
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Accounts', 'accounts')?>

<h2>Accounts</h2>

<div class="links">
    <a href="account.php">Create Account</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Name</td>
                    <td>Email</td>
                    <td>Role</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($accounts)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no accounts</td>
                </tr>
                <?php else: ?>
                <?php foreach ($accounts as $account): ?>
                <tr>
                    <td><?=$account['id']?></td>
                    <td><?=$account['name']?></td>
                    <td><?=$account['email']?></td>
                    <td><?=$account['role']?></td>
                    <td>
                        <a href="account.php?id=<?=$account['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
