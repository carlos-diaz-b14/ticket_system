<?php
include 'functions.php';

$pdo = pdo_connect_mysql();

$categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);

$status = isset($_GET['status']) ? $_GET['status'] : 'all';
$category = isset($_GET['category']) ? $_GET['category'] : 'all';
$priority = isset($_GET['priority']) ? $_GET['priority'] : 'all';
$search = isset($_GET['search']) ? $_GET['search'] : '';

$page = isset($_GET['page']) ? $_GET['page'] : 1;

$num_tickets_per_page = num_tickets_per_page;

$sql = '';
$sql .= $status != 'all' ? ' status = :status AND' : '';
$sql .= $category != 'all' ? ' category_id = :category AND' : '';
$sql .= $priority != 'all' ? ' priority = :priority AND' : '';
$sql .= $search ? ' title LIKE :search AND' : '';
$sql .= isset($_SESSION['account_loggedin']) && $_SESSION['account_role'] == 'Admin' ? '' : ' private = 0 AND';
$sql = !empty($sql) ? rtrim('WHERE ' . $sql, 'AND') : '';

$stmt = $pdo->prepare('SELECT * FROM tickets ' . $sql . ' ORDER BY created DESC LIMIT :current_page, :tickets_per_page');

if ($status != 'all') {
	$stmt->bindParam(':status', $status);
}
if ($category != 'all') {
	$stmt->bindParam(':category', $category);
}
if ($priority != 'all') {
	$stmt->bindParam(':priority', $priority);
}
if ($search) {
	$s = '%' . $search . '%';
	$stmt->bindParam(':search', $s);
}
$stmt->bindValue(':current_page', ($page-1)*(int)$num_tickets_per_page, PDO::PARAM_INT);
$stmt->bindValue(':tickets_per_page', (int)$num_tickets_per_page, PDO::PARAM_INT);
$stmt->execute();
// Fetch all tickets
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
// Fetch tickets associated with the user's account
if (isset($_SESSION['account_loggedin'])) {
	$stmt = $pdo->prepare('SELECT * FROM tickets WHERE account_id = ? ORDER BY created DESC');
	$stmt->execute([ $_SESSION['account_id'] ]);
	$account_tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_SESSION['account_loggedin']) && $_SESSION['account_role'] == 'Admin') {
	
	$num_tickets = $pdo->query('SELECT COUNT(*) FROM tickets')->fetchColumn();
	$num_open_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "open"')->fetchColumn();
	$num_closed_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "closed"')->fetchColumn();
	$num_resolved_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "resolved"')->fetchColumn();
} else {
	$num_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE private = 0')->fetchColumn();
	$num_open_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "open" AND private = 0')->fetchColumn();
	$num_closed_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "closed" AND private = 0')->fetchColumn();
	$num_resolved_tickets = $pdo->query('SELECT COUNT(*) FROM tickets WHERE status = "resolved" AND private = 0')->fetchColumn();
}
?>
<?=template_header('Tickets')?>

<div class="content tickets">

	<?php if (isset($account_tickets)): ?>

	<h2>Your Tickets</h2>

	<div class="tickets-list">
		<?php foreach ($account_tickets as $ticket): ?>
		<a href="view.php?id=<?=$ticket['id']?><?=$ticket['private'] ? '&code=' . md5($ticket['id'] . $ticket['email']) : ''?>" class="ticket">
			<span class="con">
				<?php if ($ticket['status'] == 'open'): ?>
				<i class="far fa-clock fa-2x"></i>
				<?php elseif ($ticket['status'] == 'resolved'): ?>
				<i class="fas fa-check fa-2x"></i>
				<?php elseif ($ticket['status'] == 'closed'): ?>
				<i class="fas fa-times fa-2x"></i>
				<?php endif; ?>
			</span>
			<span class="con">
				<span class="title"><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?></span>
				<span class="msg responsive-hidden"><?=htmlspecialchars($ticket['msg'], ENT_QUOTES)?></span>
			</span>
			<span class="con2">
				<span class="created responsive-hidden"><?=date('F dS, G:ia', strtotime($ticket['created']))?></span>
				<span class="priority <?=$ticket['priority']?>"><?=$ticket['priority']?></span>
			</span>
		</a>
		<?php endforeach; ?>
		<?php if (!$account_tickets): ?>
		<p>You have no tickets.</p>
		<?php endif; ?>
	</div>

	<?php endif; ?>

	<h2><?=ucfirst($status)?> Tickets</h2>

	<form action="" method="get">
		<div>
			<label for="status">Status</label>
			<select name="status" id="status" onchange="this.parentElement.parentElement.submit()">
				<option value="all"<?=$status=='all'?' selected':''?>>All (<?=number_format($num_tickets)?>)</option>
				<option value="open"<?=$status=='open'?' selected':''?>>Open (<?=number_format($num_open_tickets)?>)</option>
				<option value="resolved"<?=$status=='resolved'?' selected':''?>>Resolved (<?=number_format($num_resolved_tickets)?>)</option>
				<option value="closed"<?=$status=='closed'?' selected':''?>>Closed (<?=number_format($num_closed_tickets)?>)</option>
			</select>
			<label for="category">Category</label>
			<select name="category" id="category" onchange="this.parentElement.parentElement.submit()">
				<option value="all"<?=$category=='all'?' selected':''?>>All</option>
				<?php foreach($categories as $c): ?>
	            <option value="<?=$c['id']?>"<?=$c['id']==$category?' selected':''?>><?=$c['name']?></option>
	            <?php endforeach; ?>
			</select>
			<label for="priority">Priority</label>
			<select name="priority" id="priority" onchange="this.parentElement.parentElement.submit()">
				<option value="all"<?=$priority=='all'?' selected':''?>>All</option>
				<option value="low"<?=$priority=='low'?' selected':''?>>Low</option>
				<option value="medium"<?=$priority=='medium'?' selected':''?>>Medium</option>
				<option value="high"<?=$priority=='high'?' selected':''?>>High</option>
			</select>
		</div>
		<input name="search" type="text" placeholder="Search..." value="<?=htmlspecialchars(trim($search, '%'), ENT_QUOTES)?>" onkeypress="if(event.keyCode == 13) this.parentElement.submit()">
	</form>

	<div class="tickets-list">
		<?php foreach ($tickets as $ticket): ?>
		<a href="view.php?id=<?=$ticket['id']?><?=isset($_SESSION['account_loggedin']) && $_SESSION['account_role'] == 'Admin' && $ticket['private'] ? '&code=' . md5($ticket['id'] . $ticket['email']) : ''?>" class="ticket">
			<span class="con">
				<?php if ($ticket['status'] == 'open'): ?>
				<i class="far fa-clock fa-2x"></i>
				<?php elseif ($ticket['status'] == 'resolved'): ?>
				<i class="fas fa-check fa-2x"></i>
				<?php elseif ($ticket['status'] == 'closed'): ?>
				<i class="fas fa-times fa-2x"></i>
				<?php endif; ?>
			</span>
			<span class="con">
				<span class="title"><?=htmlspecialchars($ticket['title'], ENT_QUOTES)?></span>
				<span class="msg responsive-hidden"><?=htmlspecialchars($ticket['msg'], ENT_QUOTES)?></span>
			</span>
			<span class="con2">
				<span class="created responsive-hidden"><?=date('F dS, G:ia', strtotime($ticket['created']))?></span>
				<span class="priority <?=$ticket['priority']?>"><?=$ticket['priority']?></span>
			</span>
		</a>
		<?php endforeach; ?>
		<?php if (!$tickets): ?>
		<p>There are no tickets.</p>
		<?php endif; ?>
	</div>

	<div class="pagination">
		<?php if ($page > 1): ?>
		<a href="tickets.php?status=<?=$status?>&category=<?=$category?>&priority=<?=$priority?>&search=<?=$search?>&page=<?=$page-1?>">Prev</a>
		<?php endif; ?>
		<?php if (count($tickets) >= $num_tickets_per_page): ?>
		<a href="tickets.php?status=<?=$status?>&category=<?=$category?>&priority=<?=$priority?>&search=<?=$search?>&page=<?=$page+1?>">Next</a>
		<?php endif; ?>
	</div>

</div>

<?=template_footer()?>
