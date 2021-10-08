<?php
include 'main.php';
// Default input ticket values
$ticket = [
    'title' => '',
    'msg' => '',
    'email' => '',
    'created' => date('Y-m-d\TH:i:s'),
    'status' => 'open',
    'priority' => 'low',
    'category_id' => 1,
    'private' => 0,
    'account_id' => 0
];
// Fetch all the category names from the categories MySQL table
$categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);
// Ticket status array
$status = ['open', 'closed', 'resolved'];
// Ticket priority array
$priority = ['low', 'medium', 'high'];
// Check whether the ticket ID is specified
if (isset($_GET['id'])) {
    // Retrieve the ticket from the database
    $stmt = $pdo->prepare('SELECT * FROM tickets WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $ticket = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing ticket
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the ticket
        $stmt = $pdo->prepare('UPDATE tickets SET title = ?, msg = ?, email = ?, created = ?, status = ?, priority = ?, category_id = ?, private = ?, account_id = ? WHERE id = ?');
        $stmt->execute([ $_POST['title'], $_POST['msg'], $_POST['email'], date('Y-m-d H:i:s', strtotime($_POST['created'])), $_POST['status'], $_POST['priority'], $_POST['category'], $_POST['private'], $_POST['account_id'], $_GET['id'] ]);
        header('Location: tickets.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the ticket
        // Delete uploads associated with the ticket
        $stmt = $pdo->prepare('SELECT * FROM tickets_uploads WHERE ticket_id = ?');
        $stmt->execute([ $_GET['id'] ]);
        $ticket_uploads = $stmt->fetchAll(PDO::FETCH_ASSOC);
        foreach ($ticket_uploads as $ticket_upload) {
            unlink('../' . $ticket_upload['filepath']);
        }
        // Delete ticket from database along with the comments and uploads
        $stmt = $pdo->prepare('DELETE t, tu, tc FROM tickets t LEFT JOIN tickets_uploads tu ON tu.ticket_id = t.id LEFT JOIN tickets_comments tc ON tc.ticket_id = t.id WHERE t.id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: tickets.php');
        exit;
    }
} else {
    // Create a new ticket
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO tickets (title,msg,email,created,status,priority,category_id,private,account_id) VALUES (?,?,?,?,?,?,?,?,?)');
        $stmt->execute([ $_POST['title'], $_POST['msg'], $_POST['email'], date('Y-m-d H:i:s', strtotime($_POST['created'])), $_POST['status'], $_POST['priority'], $_POST['category'], $_POST['private'], $_POST['account_id'] ]);
        header('Location: tickets.php');
        exit;
    }
}
// Retrieve all accounts from the database
$stmt = $pdo->prepare('SELECT * FROM accounts');
$stmt->execute();
$accounts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header($page . ' Ticket', 'tickets')?>

<h2><?=$page?> Ticket</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="title">Title</label>
        <input type="text" id="title" name="title" placeholder="Title" value="<?=htmlspecialchars($ticket['title'], ENT_QUOTES)?>" required>
        <label for="msg">Message</label>
        <textarea id="msg" name="msg" placeholder="Enter your message..." required><?=htmlspecialchars($ticket['msg'], ENT_QUOTES)?></textarea>
        <label for="email">Email</label>
        <input type="text" id="email" name="email" placeholder="Email" value="<?=$ticket['email']?>" required>
        <label for="created">Created</label>
        <input type="datetime-local" name="created" placeholder="Created" value="<?=date('Y-m-d\TH:i:s', strtotime($ticket['created']))?>" required>
        <label for="status">Status</label>
        <select id="status" name="status" style="margin-bottom: 30px;">
            <?php foreach ($status as $s): ?>
            <option value="<?=$s?>"<?=$s==$ticket['status']?' selected':''?>><?=$s?></option>
            <?php endforeach; ?>
        </select>
        <label for="category">Category</label>
        <select name="category" id="category" style="margin-bottom: 30px;">
            <?php foreach($categories as $c): ?>
            <option value="<?=$c['id']?>"<?=$c['id']==$ticket['category_id']?' selected':''?>><?=$c['name']?></option>
            <?php endforeach; ?>
        </select>
        <label for="priority">Priority</label>
        <select id="priority" name="priority" style="margin-bottom: 30px;">
            <?php foreach ($priority as $p): ?>
            <option value="<?=$p?>"<?=$p==$ticket['priority']?' selected':''?>><?=$p?></option>
            <?php endforeach; ?>
        </select>
        <label for="priority">Private</label>
        <select name="private" id="private" style="margin-bottom: 30px;">
            <option value="0"<?=$ticket['private']==0?' selected':''?>>No</option>
            <option value="1"<?=$ticket['private']==1?' selected':''?>>Yes</option>
        </select>
        <label for="account_id">Account</label>
        <select id="account_id" name="account_id" style="margin-bottom: 30px;">
            <option value="0">No Account</option>
            <?php foreach ($accounts as $a): ?>
            <option value="<?=$a['id']?>"<?=$a['id']==$ticket['account_id']?' selected':''?>><?=$a['id']?> - <?=$a['email']?></option>
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
