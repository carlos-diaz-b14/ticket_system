<?php
include 'main.php';
// Default input comment values
$comment = [
    'msg' => '',
    'ticket_id' => 0,
    'account_id' => 0,
    'created' => date('Y-m-d\TH:i:s')
];
// Retrieve all accounts from the database
$accounts = $pdo->query('SELECT * FROM accounts')->fetchAll(PDO::FETCH_ASSOC);
// Retrieve all tickets from the database
$tickets = $pdo->query('SELECT * FROM tickets')->fetchAll(PDO::FETCH_ASSOC);
// Check whether the comment ID is specified
if (isset($_GET['id'])) {
    // Retrieve the comment from the database
    $stmt = $pdo->prepare('SELECT * FROM tickets_comments WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $comment = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing comment
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the comment
        $stmt = $pdo->prepare('UPDATE tickets_comments SET msg = ?, ticket_id = ?, account_id = ?, created = ? WHERE id = ?');
        $stmt->execute([ $_POST['msg'], $_POST['ticket_id'], $_POST['account_id'], date('Y-m-d H:i:s', strtotime($_POST['created'])), $_GET['id'] ]);
        header('Location: comments.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the comment
        $stmt = $pdo->prepare('DELETE FROM tickets_comments WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: comments.php');
        exit;
    }
} else {
    // Create a new comment
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO tickets_comments (msg,ticket_id,account_id,created) VALUES (?,?,?,?)');
        $stmt->execute([ $_POST['msg'], $_POST['ticket_id'], $_POST['account_id'], date('Y-m-d H:i:s', strtotime($_POST['created'])) ]);
        header('Location: comments.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' Comment', 'comments')?>

<h2><?=$page?> Comment</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="msg">Message</label>
        <textarea id="msg" name="msg" placeholder="Enter your message..." required><?=htmlspecialchars($comment['msg'], ENT_QUOTES)?></textarea>
        <label for="created">Created</label>
        <input type="datetime-local" name="created" placeholder="Created" value="<?=date('Y-m-d\TH:i:s', strtotime($comment['created']))?>" required>
        <label for="account_id">Account</label>
        <select id="account_id" name="account_id" style="margin-bottom: 30px;">
            <option value="0">No Account</option>
            <?php foreach ($accounts as $a): ?>
            <option value="<?=$a['id']?>"<?=$a['id']==$comment['account_id']?' selected':''?>><?=$a['id']?> - <?=$a['email']?></option>
            <?php endforeach; ?>
        </select>
        <label for="ticket_id">Ticket</label>
        <select id="ticket_id" name="ticket_id" style="margin-bottom: 30px;">
            <option value="0">No Ticket</option>
            <?php foreach ($tickets as $t): ?>
            <option value="<?=$t['id']?>"<?=$t['id']==$comment['ticket_id']?' selected':''?>><?=$t['id']?> - <?=$t['title']?></option>
            <?php endforeach; ?>
        </select>
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
