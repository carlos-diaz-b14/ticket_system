<?php
include 'main.php';
// Retrieve all the accounts
$stmt = $pdo->prepare('SELECT * FROM static_ip');
$stmt->execute();
$static_ip = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Static Ip', 'add_ip')?>

<h2>Static IP</h2>

<div class="links">
    <a href="add_ip.php">Input New IP</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td>Device Type</td>
                    <td>IP Adress</td>
                   
                </tr>
            </thead>
            <tbody>
                <?php if (empty($static_ip)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no ip adresses</td>
                </tr>
                <?php else: ?>
                <?php foreach ($static_ip as $static_ip): ?>
                <tr>
                    <td><?=$static_ip['id']?></td>
                    <td><?=$static_ip['username']?></td>
                    <td><?=$static_ip['device']?></td>
                    <td><?=$static_ip['ip']?></td>
                  
                    
                    <td>
                        <a href="add_ip.php?id=<?=$static_ip['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
