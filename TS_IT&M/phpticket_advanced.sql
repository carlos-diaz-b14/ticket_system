CREATE DATABASE IF NOT EXISTS `phpticket_advanced` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;
USE `phpticket_advanced`;

CREATE TABLE IF NOT EXISTS `accounts` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `role` enum('Member','Admin') NOT NULL DEFAULT 'Member'
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

INSERT INTO `accounts` (`id`, `name`, `password`, `email`, `role`) VALUES
(1, 'admin', '$2y$10$wXkhBmUEz7814.uAtHhYduoq.8WmFU3rRuwqc1k9xvSnB.OWj5aGq', 'admin@yourwebsite.com', 'Admin');

CREATE TABLE IF NOT EXISTS `categories` (
`id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8;

INSERT INTO `categories` (`id`, `name`) VALUES
(1, 'General'),
(2, 'Technical'),
(3, 'Other');

CREATE TABLE IF NOT EXISTS `tickets` (
`id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `msg` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `status` enum('open','closed','resolved') NOT NULL DEFAULT 'open',
  `priority` enum('low','medium','high') NOT NULL DEFAULT 'low',
  `category_id` int(1) NOT NULL DEFAULT '1',
  `private` tinyint(1) NOT NULL DEFAULT '1',
  `account_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

INSERT INTO `tickets` (`id`, `title`, `msg`, `email`, `created`, `status`, `priority`, `category_id`, `private`, `account_id`) VALUES
(1, 'How do I navigate to the website?', 'Hello, I''m having trouble and need your help!', 'test@codeshack.io', '2020-06-10 13:06:17', 'open', 'low', 1, 0, 0),
(2, 'Website issue', 'I''m having issues running the website on my laptop, can you help?', 'test@codeshack.io', '2020-06-10 13:07:40', 'resolved', 'medium', 1, 0, 0),
(3, 'Responsive design issue', 'I have noticed on mobile devices the website does not work correctly, will you guys fix this problem?', 'test@codeshack.io', '2020-06-10 14:30:33', 'open', 'low', 1, 0, 0),
(4, 'Navigation menu not aligned', 'When I browser the website on a mobile device I have noticed the menu is not aligned, just letting you guys know.', 'test@codeshack.io', '2020-06-16 15:47:20', 'closed', 'high', 1, 0, 0);

CREATE TABLE IF NOT EXISTS `tickets_comments` (
`id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `msg` text NOT NULL,
  `created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `account_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `tickets_uploads` (
`id` int(11) NOT NULL,
  `ticket_id` int(11) NOT NULL,
  `filepath` varchar(255) NOT NULL
) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;


ALTER TABLE `accounts`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `categories`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `tickets`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `tickets_comments`
 ADD PRIMARY KEY (`id`);

ALTER TABLE `tickets_uploads`
 ADD PRIMARY KEY (`id`);


ALTER TABLE `accounts`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
ALTER TABLE `categories`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
ALTER TABLE `tickets`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=14;
ALTER TABLE `tickets_comments`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=10;
ALTER TABLE `tickets_uploads`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=6;
