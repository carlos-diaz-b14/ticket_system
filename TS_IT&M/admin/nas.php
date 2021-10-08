<?php
include 'main.php';
// Retrieve all the accounts
$stmt = $pdo->prepare('SELECT * FROM nas');
$stmt->execute();
$nas = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('NAS', 'add_nas')?>

<h2>NAS</h2>

<div class="links">
    <a href="add_nas.php">Input New NAS User</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td>Password</td>
                    <td>Department</td>
                   
                </tr>
            </thead>
            <tbody>
                <?php if (empty($nas)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no nas users</td>
                </tr>
                <?php else: ?>
                <?php foreach ($nas as $nas): ?>
                <tr>
                    <td><?=$nas['id']?></td>
                    <td><?=$nas['username']?></td>
                    <td><?=$nas['password']?></td>
                    <td><?=$nas['depa']?></td>
                  
                    
                    <td>
                        <a href="add_nas.php?id=<?=$nas['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
