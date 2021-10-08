<?php
include 'main.php';
// Retrieve all the accounts
$stmt = $pdo->prepare('SELECT * FROM micro');
$stmt->execute();
$micro = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Microsoft Accounts', 'add_micro')?>

<h2>Microsoft Accounts</h2>

<div class="links">
    <a href="add_micro.php"> New Microsoft Account</a>
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
                <?php if (empty($micro)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no Microsoft Accounts</td>
                </tr>
                <?php else: ?>
                <?php foreach ($micro as $micro): ?>
                <tr>
                    <td><?=$micro['id']?></td>
                    <td><?=$micro['username']?></td>
                    <td><?=$micro['password']?></td>
                    <td><?=$micro['depa']?></td>
                  
                    
                    <td>
                        <a href="add_micro.php?id=<?=$micro['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
