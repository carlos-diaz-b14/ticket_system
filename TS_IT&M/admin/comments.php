<?php
include 'main.php';
$params = [];
$ticket_sql = '';
// Check if the ticket_id param is specified in the URL
if (isset($_GET['ticket_id'])) {
    $ticket_sql = 'WHERE t.id = ?';
    $params[] = $_GET['ticket_id'];
}
// Retrieve all ticket comments along with the associated categories and order by the created column
$stmt = $pdo->prepare('SELECT tc.*, t.title, a.name FROM tickets_comments tc JOIN tickets t ON t.id = tc.ticket_id LEFT JOIN accounts a ON a.id = tc.account_id ' . $ticket_sql . ' GROUP BY tc.id ORDER BY tc.created DESC');
$stmt->execute($params);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Comments', 'comments')?>

<h2>Comments</h2>

<div class="links">
    <a href="comment.php">Create Comment</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>Ticket Title</td>
                    <td class="responsive-hidden">Name</td>
                    <td>Msg</td>
                    <td class="responsive-hidden">Date</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($comments)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are comments</td>
                </tr>
                <?php else: ?>
                <?php foreach ($comments as $comment): ?>
                <tr>
                    <td><?=htmlspecialchars($comment['title'], ENT_QUOTES)?></td>
                    <td class="responsive-hidden"><?=$comment['name']?></td>
                    <td><?=$comment['msg']?></td>
                    <td class="responsive-hidden"><?=date('F j, Y H:ia', strtotime($comment['created']))?></td>
                    <td>
                        <a href="../view.php?id=<?=$comment['ticket_id']?>" target="_blank">View</a>
                        <a href="comment.php?id=<?=$comment['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
