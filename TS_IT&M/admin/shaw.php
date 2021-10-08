<?php
include 'main.php';
// Retrieve all the accounts
$stmt = $pdo->prepare('SELECT * FROM shaw');
$stmt->execute();
$shaw = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Shaw Smart Voice', 'add_shaw')?>

<h2>Shaw Smart Voice </h2>

<div class="links">
    <a href="add_shaw.php"> New Shaw Smart Voice Account</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td>User id</td>
                    <td>Password</td>
                    <td>Extension</td>
                    <td>Department</td>
                   
                </tr>
            </thead>
            <tbody>
                <?php if (empty($shaw)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no smartvoice Accounts</td>
                </tr>
                <?php else: ?>
                <?php foreach ($shaw as $shaw): ?>
                <tr>
                    <td><?=$shaw['id']?></td>
                    <td><?=$shaw['username']?></td>
                    <td><?=$shaw['userid']?></td>
                    <td><?=$shaw['password']?></td>
                    <td><?=$shaw['ext']?></td>
                    <td><?=$shaw['depa']?></td>
                    
                    
                  
                    
                    <td>
                        <a href="add_shaw.php?id=<?=$shaw['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
