<?php
include 'main.php';
// Retrieve all the categories
$stmt = $pdo->prepare('SELECT * FROM categories');
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?=template_admin_header('Categories', 'categories')?>

<h2>Categories</h2>

<div class="links">
    <a href="category.php">Create Category</a>
</div>

<div class="content-block">
    <div class="table">
        <table>
            <thead>
                <tr>
                    <td>#</td>
                    <td>Name</td>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($categories)): ?>
                <tr>
                    <td colspan="8" style="text-align:center;">There are no categories</td>
                </tr>
                <?php else: ?>
                <?php foreach ($categories as $category): ?>
                <tr class="details" onclick="location.href='category.php?id=<?=$category['id']?>'">
                    <td><?=$category['id']?></td>
                    <td><?=$category['name']?></td>
                </tr>
                <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?=template_admin_footer()?>
