<?php
include 'main.php';
$status_sql = '';

if (isset($_GET['status']) && in_array($_GET['status'], ['open', 'closed', 'resolved'])) {
    $status_sql = 'WHERE t.status = "' . $_GET['status'] . '"';
}

$stmt = $pdo->prepare('SELECT t.*, (SELECT count(*) FROM tickets_comments tc WHERE t.id = tc.ticket_id) AS msgs, c.name AS category FROM tickets t LEFT JOIN categories c ON c.id = t.category_id ' . $status_sql . ' ORDER BY t.created DESC');
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Tickets', 'tickets')?>

<h2>Tickets</h2>

<div class="links">
    <a href="ticket.php">Create Ticket</a>
    <a href="tickets.php?status=open">View Open Tickets</a>
    <a href="tickets.php?status=closed">View Closed Tickets</a>
    <a href="tickets.php?status=resolved">View Resolved Tickets</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>Title</td>
                    <td class="responsive-hidden">Email</td>
                    <td class="responsive-hidden">Category</td>
                    <td>Priority</td>
                    <td>Status</td>
                    <td>Msgs</td>
                    <td class="responsive-hidden">Date</td>
                    <td>Actions</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($tickets)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are tickets</td>
                </tr>
                <?php else: ?>
                <?php foreach ($tickets as $ticket): ?>
                <tr>
                    <td><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?></td>
                    <td class="responsive-hidden"><?=$ticket['email']?></td>
                    <td class="responsive-hidden"><?=$ticket['category']?></td>
                    <td class="priority <?=$ticket['priority']?>"><?=$ticket['priority']?></td>
                    <td class="status <?=$ticket['status']?>"><?=$ticket['status']?></td>
                    <td>
                        <a href="comments.php?ticket_id=<?=$ticket['id']?>"><?=$ticket['msgs']?></a>
                    </td>
                    <td class="responsive-hidden"><?=date('F j, Y H:ia', strtotime($ticket['created']))?></td>
                    <td>
                        <a href="../view.php?id=<?=$ticket['id']?>" target="_blank">View</a>
                        <a href="ticket.php?id=<?=$ticket['id']?>">Edit</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
