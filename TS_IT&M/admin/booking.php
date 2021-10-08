<?php
include 'main.php';
// Retrieve all the accounts
$stmt = $pdo->prepare('SELECT * FROM book');
$stmt->execute();
$book = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Booking System', 'add_book')?>

<h2>Booking System Accounts</h2>

<div class="links">
    <a href="add_book.php"> New Booking System Account</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Username</td>
                    <td>User</td>
                    <td>Password</td>
                    <td>Program</td>
                    <td>Verification Code</td>
                   
                </tr>
            </thead>
            <tbody>
                <?php if (empty($book)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no Booking Accounts</td>
                </tr>
                <?php else: ?>
                <?php foreach ($book as $book): ?>
                <tr>
                    <td><?=$book['id']?></td>
                    <td><?=$book['username']?></td>
                    <td><?=$book['user']?></td>
                    <td><?=$book['password']?></td>
                    <td><?=$book['program']?></td>
                    <td><?=$book['ver']?></td>
                  
                    
                    <td>
                        <a href="add_book.php?id=<?=$book['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
