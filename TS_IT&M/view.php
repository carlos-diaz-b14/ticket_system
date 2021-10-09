<?php
include 'functions.php';

$pdo = pdo_connect_mysql();

if (!isset($_GET['id'])) {
    exit('No ID specified!');
}

$stmt = $pdo->prepare('SELECT t.*, c.name AS category FROM tickets t LEFT JOIN categories c ON c.id = t.category_id WHERE t.id = ?');
$stmt->execute([ $_GET['id'] ]);
$ticket = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare('SELECT * FROM tickets_uploads WHERE ticket_id = ?');
$stmt->execute([ $_GET['id'] ]);
$ticket_uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$ticket) {
    exit('Invalid ticket ID!');
}

if ($ticket['private'] && (!isset($_GET['code']) || $_GET['code'] != md5($ticket['id'] . $ticket['email']))) {
    exit('This is a private ticket!');
}

$private_url = $ticket['private'] ? '&code=' . md5($ticket['id'] . $ticket['email']) : '';

if (isset($_GET['status'], $_SESSION['account_loggedin']) && $_SESSION['account_role'] == 'Admin' && in_array($_GET['status'], ['open', 'closed', 'resolved'])) {
    $stmt = $pdo->prepare('UPDATE tickets SET status = ? WHERE id = ?');
    $stmt->execute([ $_GET['status'], $_GET['id'] ]);

    send_ticket_email($ticket['email'], $ticket['id'], $ticket['title'], $ticket['msg'], $ticket['priority'], $ticket['category'], $ticket['private'], $_GET['status'], 'update');
    header('Location: view.php?id=' . $_GET['id'] . $private_url);
    exit;
}

if (isset($_POST['msg'], $_SESSION['account_loggedin']) && !empty($_POST['msg'])) {
 
    $stmt = $pdo->prepare('INSERT INTO tickets_comments (ticket_id, msg, account_id) VALUES (?, ?, ?)');
    $stmt->execute([ $_GET['id'], $_POST['msg'], $_SESSION['account_id'] ]);
   
    send_ticket_email($ticket['email'], $ticket['id'], $ticket['title'], $ticket['msg'], $ticket['priority'], $ticket['category'], $ticket['private'], $ticket['status'], 'comment');
    header('Location: view.php?id=' . $_GET['id'] . $private_url);
    exit;
}

$stmt = $pdo->prepare('SELECT tc.*, a.name, a.role FROM tickets_comments tc LEFT JOIN accounts a ON a.id = tc.account_id WHERE tc.ticket_id = ? ORDER BY tc.created');
$stmt->execute([ $_GET['id'] ]);
$comments = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_header(htmlspecialchars($ticket['title'], ENT_QUOTES))?>

<div class="content view">

	<h2><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?> <span class="<?=$ticket['status']?>">(<?=$ticket['status']?>)</span></h2>

    <div class="ticket">
        <div>
            <p>
                <span class="priority <?=$ticket['priority']?>"><?=$ticket['priority']?></span>
                <span class="sep">&bull;</span>
                <span class="category"><?=$ticket['category']?></span>
                <?php if (isset($_SESSION['account_loggedin']) && $_SESSION['account_role'] == 'Admin'): ?>
                <span class="sep">&bull;</span>
                <span class="category"><?=$ticket['email']?></span>
                <?php endif; ?>
            </p>
            <p class="created"><?=date('F dS, G:ia', strtotime($ticket['created']))?></p>
        </div>
        <p class="msg"><?=nl2br(htmlspecialchars($ticket['msg'], ENT_QUOTES))?></p>
    </div>

    <div class="uploads">
        <?php foreach($ticket_uploads as $ticket_upload): ?>
        <a href="<?=$ticket_upload['filepath']?>" download>
            <img src="<?=$ticket_upload['filepath']?>" width="100" height="100" alt="">
        </a>
        <?php endforeach; ?>
    </div>

    <?php if (isset($_SESSION['account_loggedin']) && $_SESSION['account_role'] == 'Admin'): ?>
    <div class="btns">
        <a href="admin/ticket.php?id=<?=$_GET['id']?>" target="_blank" class="btn">Edit</a>
        <a href="view.php?id=<?=$_GET['id']?>&status=resolved<?=$private_url?>" class="btn">Resolve</a>
        <a href="view.php?id=<?=$_GET['id']?>&status=closed<?=$private_url?>" class="btn red">Close</a>
    </div>
    <?php endif; ?>

    <div class="comments">
        <?php foreach($comments as $comment): ?>
        <div class="comment">
            <div>
                <i class="fas fa-comment fa-2x"></i>
            </div>
            <p>
                <span class="header">
                    <?php if ($comment['name']): ?>
                    <span class="name<?=$comment['role'] == 'Admin' ? ' is-admin' : ''?>"><?=$comment['name']?></span>
                    <?php endif; ?>
                    <span class="date"><?=date('F dS, G:ia', strtotime($comment['created']))?></span>
                </span>
                <?=nl2br(htmlspecialchars($comment['msg'], ENT_QUOTES))?>
            </p>
        </div>
        <?php endforeach; ?>
        <?php if (isset($_SESSION['account_loggedin'])): ?>
        <form action="" method="post" class="responsive-width-100">
            <textarea name="msg" placeholder="Enter your comment..." class="responsive-width-100"></textarea>
            <input type="submit" value="Post Comment">
        </form>
        <?php endif; ?>
    </div>

</div>

<?=template_footer()?>
