<?php
include 'main.php';
// Retrieve all the accounts
$stmt = $pdo->prepare('SELECT * FROM pass');
$stmt->execute();
$pass = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Passwords', 'pass')?>

<h2>General Passwords </h2>

<div class="links">
    <a href="add_pass.php"> New Password</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Type</td>
                    <td>Device Name or Network Name</td>
                    <td>Password</td>
                    <td>Location</td>
                    <td>Comments</td>
                   
                </tr>
            </thead>
            <tbody>
                <?php if (empty($pass)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no passwords</td>
                </tr>
                <?php else: ?>
                <?php foreach ($pass as $pass): ?>
                <tr>
                    <td><?=$pass['id']?></td>
                    <td><?=$pass['type']?></td>
                    <td><?=$pass['device']?></td>
                    <td><?=$pass['password']?></td>
                    <td><?=$pass['location']?></td>
                    <td><?=$pass['comments']?></td>
                    
                    
                  
                    
                    <td>
                        <a href="add_pas.php?id=<?=$pass['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
