<?php
include 'main.php';
// Default input category values
$category = [
    'id' => '',
    'name' => ''
];
if (isset($_GET['id'])) {
    // Retrieve the category from the database
    $stmt = $pdo->prepare('SELECT * FROM categories WHERE id = ?');
    $stmt->execute([ $_GET['id'] ]);
    $category = $stmt->fetch(PDO::FETCH_ASSOC);
    // ID param exists, edit an existing category
    $page = 'Edit';
    if (isset($_POST['submit'])) {
        // Update the category
        $stmt = $pdo->prepare('UPDATE categories SET name = ? WHERE id = ?');
        $stmt->execute([ $_POST['name'], $_GET['id'] ]);
        header('Location: categories.php');
        exit;
    }
    if (isset($_POST['delete'])) {
        // Delete the category
        $stmt = $pdo->prepare('DELETE FROM categories WHERE id = ?');
        $stmt->execute([ $_GET['id'] ]);
        header('Location: categories.php');
        exit;
    }
} else {
    // Create a new category
    $page = 'Create';
    if (isset($_POST['submit'])) {
        $stmt = $pdo->prepare('INSERT INTO categories (name) VALUES (?)');
        $stmt->execute([ $_POST['name'] ]);
        header('Location: categories.php');
        exit;
    }
}
?>
<?=template_admin_header($page . ' Category', 'categories')?>

<h2><?=$page?> Category</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="name">Name</label>
        <input type="text" id="name" name="name" placeholder="Name" value="<?=$category['name']?>" required>
        <div class="submit-btns">
            <input type="submit" name="submit" value="Submit">
            <?php if ($page == 'Edit'): ?>
            <input type="submit" name="delete" value="Delete" class="delete">
            <?php endif; ?>
        </div>
    </form>
</div>

<?=template_admin_footer()?>
