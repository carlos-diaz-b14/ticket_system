<?php
include 'main.php';

$stmt = $pdo->prepare('SELECT t.*, (SELECT count(*) FROM tickets_comments tc WHERE t.id = tc.ticket_id) AS msgs, c.name AS category FROM tickets t LEFT JOIN categories c ON c.id = t.category_id WHERE cast(t.created as DATE) = cast(now() as DATE) ORDER BY t.priority DESC, t.created DESC');
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT t.*, (SELECT count(*) FROM tickets_comments tc WHERE t.id = tc.ticket_id) AS msgs, c.name AS category FROM tickets t LEFT JOIN categories c ON c.id = t.category_id WHERE t.status = "open" ORDER BY t.priority DESC, t.created DESC');
$stmt->execute();
$open_tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM tickets');
$stmt->execute();
$tickets_total = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM tickets WHERE status = "open"');
$stmt->execute();
$open_tickets_total = $stmt->fetchColumn();

$stmt = $pdo->prepare('SELECT COUNT(*) AS total FROM tickets WHERE status = "resolved"');
$stmt->execute();
$resolved_tickets_total = $stmt->fetchColumn();
?>
<?=template_admin_header('Dashboard', 'dashboard')?>

<h2>Dashboard</h2>

<div class="dashboard">
    <div class="content-block stat">
        <div>
            <h3>Today's Tickets</h3>
            <p><?=number_format(count($tickets))?></p>
        </div>
        <i class="fas fa-ticket-alt"></i>
    </div>

    <div class="content-block stat">
        <div>
            <h3>Total Tickets</h3>
            <p><a href="tickets.php"><?=number_format($tickets_total)?></a></p>
        </div>
        <i class="fas fa-ticket-alt"></i>
    </div>

    <div class="content-block stat">
        <div>
            <h3>Open Tickets</h3>
            <p><a href="tickets.php?status=open"><?=number_format($open_tickets_total)?></a></p>
        </div>
        <i class="fas fa-clock"></i>
    </div>

    <div class="content-block stat">
        <div>
            <h3>Resolved Tickets</h3>
            <p><a href="tickets.php?status=resolved"><?=number_format($resolved_tickets_total)?></a></p>
        </div>
        <i class="fas fa-check-circle"></i>
    </div>
</div>

<h2>Today's Tickets</h2>

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
                    <td colspan="8" style="text-align:center;">There are no recent tickets</td>
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

<h2 style="margin-top:40px">Open Tickets</h2>

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
                <?php if (empty($open_tickets)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no recent tickets</td>
                </tr>
                <?php else: ?>
                <?php foreach ($open_tickets as $ticket): ?>
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
