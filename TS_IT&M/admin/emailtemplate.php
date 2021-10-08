<?php
include 'main.php';

if (isset($_POST['ticket'], $_POST['ticketupdate'])) {
    file_put_contents('../ticket-email-template.php', $_POST['ticket']);
}

$contents = file_get_contents('../ticket-email-template.php');
?>
<?=template_admin_header('Email Template', 'emailtemplate')?>

<h2>Email Template</h2>

<div class="content-block">
    <form action="" method="post" class="form responsive-width-100">
        <label for="ticket">Ticket Email Template</label>
        <textarea name="ticket" id="ticketupdate"><?=htmlspecialchars($contents, ENT_QUOTES)?></textarea>
        <input type="submit" value="Save">
    </form>
</div>

<?=template_admin_footer()?>
