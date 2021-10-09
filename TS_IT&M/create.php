<?php
include 'functions.php';
$pdo = pdo_connect_mysql();
$msg = '';

if (authentication_required && !isset($_SESSION['account_loggedin'])) {
    header('Location: login.php');
    exit;
}

$categories = $pdo->query('SELECT * FROM categories')->fetchAll(PDO::FETCH_ASSOC);


if (isset($_POST['title'], $_POST['msg'], $_POST['priority'], $_POST['category'], $_POST['private']) && (isset($_SESSION['account_loggedin']) || isset($_POST['email']))) {

    $email = isset($_SESSION['account_loggedin']) ? $_SESSION['account_email'] : $_POST['email'];
    if (empty($_POST['title']) || empty($email) || empty($_POST['msg']) || empty($_POST['priority'])) {
        $msg = 'Please complete the form!';
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = 'Please provide a valid email address!';
    } else {
       
        $stmt = $pdo->prepare('INSERT INTO tickets (title, email, msg, priority, category_id, private, account_id) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $account_id = isset($_SESSION['account_loggedin']) ? $_SESSION['account_id'] : 0;
        $stmt->execute([ $_POST['title'], $email, $_POST['msg'], $_POST['priority'], $_POST['category'], $_POST['private'], $account_id ]);
      
        $ticket_id = $pdo->lastInsertId();
        
        if (isset($_FILES['photos'])) {
         
            for ($i = 0; $i < count($_FILES['photos']['name']); $i++) {
              
                $ext = pathinfo($_FILES['photos']['name'][$i], PATHINFO_EXTENSION);
               
            	$photo_path = 'uploads/' . sha1(uniqid() . $ticket_id . $i) .  '.' . $ext;
            
            	if (!empty($_FILES['photos']['tmp_name'][$i]) && getimagesize($_FILES['photos']['tmp_name'][$i])) {
            		if (!file_exists($photo_path) && $_FILES['photos']['size'][$i] <= max_allowed_upload_image_size) {
                       
            			move_uploaded_file($_FILES['photos']['tmp_name'][$i], $photo_path);
            			
            			$stmt = $pdo->prepare('INSERT INTO tickets_uploads (ticket_id, filepath) VALUES (?, ?)');
            	        $stmt->execute([ $ticket_id, $photo_path ]);
            		}
            	}
            }
        }
       
        $category_name = 'none';
        foreach ($categories as $c) {
            $category_name = $c['id'] == $_POST['category'] ? $c['name'] : $category_name;
        }
  
        send_ticket_email($email, $ticket_id, $_POST['title'], $_POST['msg'], $_POST['priority'], $category_name, $_POST['private'], 'open');
        // Redirect to the view ticket page, the user should see their created ticket on this page
        header('Location: view.php?id=' . $ticket_id . ($_POST['private'] ? '&code=' . md5($ticket_id . $email) : ''));
    }
}
?>
<?=template_header('Create Ticket')?>

<div class="content update">

	<h2>Create Ticket</h2>

    <form action="" method="post" class="responsive-width-100" enctype="multipart/form-data">
        <label for="title">Title</label>
        <input type="text" name="title" placeholder="Title" id="title" required>
        <?php if (!isset($_SESSION['account_loggedin'])): ?>
        <label for="email">Email</label>
        <input type="email" name="email" placeholder="johndoe@example.com" id="email" required>
        <?php endif; ?>
        <label for="category">Category</label>
        <select name="category" id="category">
            <?php foreach($categories as $category): ?>
            <option value="<?=$category['id']?>"><?=$category['name']?></option>
            <?php endforeach; ?>
        </select>

        <div class="wrap"> 
            <label for="priority">Priority</label>
            <label for="private">Private</label>
            <select name="priority" id="priority" required>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>
            <select name="private" id="private" required>
                <option value="1">Yes</option>
                <option value="0">No</option>
            </select>
        </div>
        <label for="msg">Message</label>
        <textarea name="msg" placeholder="Enter your message here..." id="msg" required></textarea>
        <label for="photos">Photos (Optional)</label>
        <input type="file" name="photos[]" id="photos" accept="image/*" multiple>
        <input type="submit" value="Create">
    </form>

    <?php if ($msg): ?>
    <p><?=$msg?></p>
    <?php endif; ?>

</div>

<?=template_footer()?>
