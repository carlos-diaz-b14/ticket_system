<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width,minimum-scale=1">
	</head>
	<body style="background-color: #F5F6F8;font-size: 16px;box-sizing: border-box;font-family: -apple-system, BlinkMacSystemFont, 'segoe ui', roboto, oxygen, ubuntu, cantarell, 'fira sans', 'droid sans', 'helvetica neue', Arial, sans-serif;">
		<div style="text-align: center;margin: 60px;background-color: #fff;padding: 60px;">
			<h1 style="font-size: 18px;color: #474a50;padding-bottom: 10px;">Your Ticket #<?=$id?></h1>
			<p><?=$subject?>! Your ticket details are below.</p>
			<?php if ($type == 'comment'): ?>
			<table style="text-align:left;">
                <tr>
                    <td style="padding:5px;font-weight:bold;">Comment:</td>
                    <td><?=nl2br($msg)?></td>
                </tr>
            </table>
			<?php else: ?>
            <table style="text-align:left;">
                <tr>
                    <td style="padding:5px;font-weight:bold;">Title:</td>
                    <td><?=$title?></td>
                </tr>
                <tr>
                    <td style="padding:5px;font-weight:bold;">Message:</td>
                    <td><?=nl2br($msg)?></td>
                </tr>
                <tr>
                    <td style="padding:5px;font-weight:bold;">Priority:</td>
                    <td><?=$priority?></td>
                </tr>
                <tr>
                    <td style="padding:5px;font-weight:bold;">Category:</td>
                    <td><?=$category?></td>
                </tr>
                <tr>
                    <td style="padding:5px;font-weight:bold;">Private:</td>
                    <td><?=$private==1?'Yes':'No'?></td>
                </tr>
				<tr>
                    <td style="padding:5px;font-weight:bold;">Status:</td>
                    <td><?=$status?></td>
                </tr>
            </table>
			<?php endif; ?>
			<p>Click <a href="<?=$link?>" style="color: #c52424;text-decoration: none;">here</a> to view your ticket.</p>
		</div>
	</body>
</html>
