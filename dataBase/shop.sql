-- phpMyAdmin SQL Dump
-- version 3.3.10deb1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Generation Time: May 20, 2011 at 07:23 PM
-- Server version: 5.1.54
-- PHP Version: 5.3.5-1ubuntu7.2

SET SQL_MODE="NO_AUTO_VALUE_ON_ZERO";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8 */;

--
-- Database: `shop`
--

-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_actions`
--

DROP TABLE IF EXISTS `engine4_activity_actions`;
CREATE TABLE IF NOT EXISTS `engine4_activity_actions` (
  `action_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `subject_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `subject_id` int(11) unsigned NOT NULL,
  `object_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `body` text COLLATE utf8_unicode_ci,
  `params` text COLLATE utf8_unicode_ci,
  `date` datetime NOT NULL,
  `attachment_count` smallint(3) unsigned NOT NULL DEFAULT '0',
  `comment_count` mediumint(5) unsigned NOT NULL DEFAULT '0',
  `like_count` mediumint(5) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`action_id`),
  KEY `SUBJECT` (`subject_type`,`subject_id`),
  KEY `OBJECT` (`object_type`,`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `engine4_activity_actions`
--

INSERT INTO `engine4_activity_actions` (`action_id`, `type`, `subject_type`, `subject_id`, `object_type`, `object_id`, `body`, `params`, `date`, `attachment_count`, `comment_count`, `like_count`) VALUES
(1, 'profile_photo_update', 'user', 1, 'user', 1, '{item:$subject} added a new profile photo.', '[]', '2011-05-20 13:25:17', 1, 0, 1),
(2, 'post_self', 'user', 1, 'user', 1, 'Moscow GTUG Geo Meetup', '[]', '2011-05-20 13:45:44', 1, 1, 1),
(3, 'signup', 'user', 3, 'user', 3, '', '[]', '2011-05-20 13:53:58', 0, 0, 0),
(4, 'friends', 'user', 3, 'user', 1, '{item:$object} is now friends with {item:$subject}.', '[]', '2011-05-20 13:55:13', 0, 0, 0),
(5, 'friends', 'user', 1, 'user', 3, '{item:$object} is now friends with {item:$subject}.', '[]', '2011-05-20 13:55:13', 0, 0, 0),
(6, 'status', 'user', 1, 'user', 1, '<a href="http://www.last.fm/" target="_blank" rel="nofollow">http://www.last.fm/</a>', '[]', '2011-05-20 15:11:27', 0, 0, 0),
(7, 'post_self', 'user', 1, 'user', 1, '', '[]', '2011-05-20 15:12:03', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_actionsettings`
--

DROP TABLE IF EXISTS `engine4_activity_actionsettings`;
CREATE TABLE IF NOT EXISTS `engine4_activity_actionsettings` (
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `publish` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_activity_actionsettings`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_actiontypes`
--

DROP TABLE IF EXISTS `engine4_activity_actiontypes`;
CREATE TABLE IF NOT EXISTS `engine4_activity_actiontypes` (
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `module` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `displayable` tinyint(1) NOT NULL DEFAULT '3',
  `attachable` tinyint(1) NOT NULL DEFAULT '1',
  `commentable` tinyint(1) NOT NULL DEFAULT '1',
  `shareable` tinyint(1) NOT NULL DEFAULT '1',
  `is_generated` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_activity_actiontypes`
--

INSERT INTO `engine4_activity_actiontypes` (`type`, `module`, `body`, `enabled`, `displayable`, `attachable`, `commentable`, `shareable`, `is_generated`) VALUES
('blog_new', 'blog', '{item:$subject} wrote a new blog entry:', 1, 5, 1, 3, 1, 1),
('comment_blog', 'blog', '{item:$subject} commented on {item:$owner}''s {item:$object:blog entry}: {body:$body}', 1, 1, 1, 1, 1, 0),
('comment_poll', 'poll', '{item:$subject} commented on {item:$owner}''s {item:$object:poll}.', 1, 1, 1, 1, 1, 1),
('comment_video', 'video', '{item:$subject} commented on {item:$owner}''s {item:$object:video}: {body:$body}', 1, 1, 1, 1, 1, 0),
('event_create', 'event', '{item:$subject} created a new event:', 1, 5, 1, 1, 1, 1),
('event_join', 'event', '{item:$subject} joined the event {item:$object}', 1, 3, 1, 1, 1, 1),
('event_photo_upload', 'event', '{item:$subject} added {var:$count} photo(s).', 1, 3, 2, 1, 1, 1),
('event_topic_create', 'event', '{item:$subject} posted a {item:$object:topic} in the event {itemParent:$object:event}: {body:$body}', 1, 3, 1, 1, 1, 1),
('event_topic_reply', 'event', '{item:$subject} replied to a {item:$object:topic} in the event {itemParent:$object:event}: {body:$body}', 1, 3, 1, 1, 1, 1),
('fields_change_generic', 'fields', '{item:$subject} changed their {translate:$label} to "{var:$value}".', 1, 3, 1, 1, 1, 1),
('friends', 'user', '{item:$subject} is now friends with {item:$object}.', 1, 3, 0, 1, 1, 1),
('friends_follow', 'user', '{item:$subject} is now following {item:$object}.', 1, 3, 0, 1, 1, 1),
('group_create', 'group', '{item:$subject} created a new group:', 1, 5, 1, 1, 1, 1),
('group_join', 'group', '{item:$subject} joined the group {item:$object}', 1, 3, 1, 1, 1, 1),
('group_photo_upload', 'group', '{item:$subject} added {var:$count} photo(s).', 1, 3, 2, 1, 1, 1),
('group_promote', 'group', '{item:$subject} has been made an officer for the group {item:$object}', 1, 3, 1, 1, 1, 1),
('group_topic_create', 'group', '{item:$subject} posted a {item:$object:topic} in the group {itemParent:$object:group}: {body:$body}', 1, 3, 1, 1, 1, 1),
('group_topic_reply', 'group', '{item:$subject} replied to a {item:$object:topic} in the group {itemParent:$object:group}: {body:$body}', 1, 3, 1, 1, 1, 1),
('login', 'user', '{item:$subject} has signed in.', 0, 1, 0, 1, 1, 1),
('logout', 'user', '{item:$subject} has signed out.', 0, 1, 0, 1, 1, 1),
('network_join', 'network', '{item:$subject} joined the network {item:$object}', 1, 3, 1, 1, 1, 1),
('poll_new', 'poll', '{item:$subject} created a new poll:', 1, 5, 1, 3, 1, 1),
('post', 'user', '{actors:$subject:$object}: {body:$body}', 1, 7, 1, 1, 1, 0),
('post_self', 'user', '{item:$subject} {body:$body}', 1, 5, 1, 1, 1, 0),
('profile_photo_update', 'user', '{item:$subject} has added a new profile photo.', 1, 5, 1, 1, 1, 1),
('signup', 'user', '{item:$subject} has just signed up. Say hello!', 1, 5, 0, 1, 1, 1),
('status', 'user', '{item:$subject} {body:$body}', 1, 5, 0, 1, 4, 0),
('tagged', 'user', '{item:$subject} tagged {item:$object} in a {var:$label}:', 1, 7, 1, 1, 0, 1),
('video_new', 'video', '{item:$subject} posted a new video:', 1, 5, 1, 3, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_attachments`
--

DROP TABLE IF EXISTS `engine4_activity_attachments`;
CREATE TABLE IF NOT EXISTS `engine4_activity_attachments` (
  `attachment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `action_id` int(11) unsigned NOT NULL,
  `type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `id` int(11) unsigned NOT NULL,
  `mode` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`attachment_id`),
  KEY `action_id` (`action_id`),
  KEY `type_id` (`type`,`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `engine4_activity_attachments`
--

INSERT INTO `engine4_activity_attachments` (`attachment_id`, `action_id`, `type`, `id`, `mode`) VALUES
(1, 1, 'storage_file', 1, 1),
(2, 2, 'core_link', 1, 1),
(3, 7, 'core_link', 2, 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_comments`
--

DROP TABLE IF EXISTS `engine4_activity_comments`;
CREATE TABLE IF NOT EXISTS `engine4_activity_comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) unsigned NOT NULL,
  `poster_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `poster_id` int(11) unsigned NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `resource_type` (`resource_id`),
  KEY `poster_type` (`poster_type`,`poster_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `engine4_activity_comments`
--

INSERT INTO `engine4_activity_comments` (`comment_id`, `resource_id`, `poster_type`, `poster_id`, `body`, `creation_date`) VALUES
(1, 2, 'user', 1, 'Прикольно можно коментить!!!! теперь нужно подумать про написание уникальных модулей для проекта!! Начнем конечно же с основной идеи', '2011-05-20 13:46:55');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_likes`
--

DROP TABLE IF EXISTS `engine4_activity_likes`;
CREATE TABLE IF NOT EXISTS `engine4_activity_likes` (
  `like_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_id` int(11) unsigned NOT NULL,
  `poster_type` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `poster_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`like_id`),
  KEY `resource_id` (`resource_id`),
  KEY `poster_type` (`poster_type`,`poster_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `engine4_activity_likes`
--

INSERT INTO `engine4_activity_likes` (`like_id`, `resource_id`, `poster_type`, `poster_id`) VALUES
(1, 1, 'user', 1),
(2, 2, 'user', 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_notifications`
--

DROP TABLE IF EXISTS `engine4_activity_notifications`;
CREATE TABLE IF NOT EXISTS `engine4_activity_notifications` (
  `notification_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `subject_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `subject_id` int(11) unsigned NOT NULL,
  `object_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `params` text COLLATE utf8_unicode_ci,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  `mitigated` tinyint(1) NOT NULL DEFAULT '0',
  `date` datetime NOT NULL,
  PRIMARY KEY (`notification_id`),
  KEY `LOOKUP` (`user_id`,`date`),
  KEY `subject` (`subject_type`,`subject_id`),
  KEY `object` (`object_type`,`object_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `engine4_activity_notifications`
--

INSERT INTO `engine4_activity_notifications` (`notification_id`, `user_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`, `params`, `read`, `mitigated`, `date`) VALUES
(1, 2, 'user', 1, 'user', 2, 'friend_request', NULL, 0, 0, '2011-05-20 13:47:36'),
(2, 1, 'user', 3, 'user', 1, 'friend_request', NULL, 1, 1, '2011-05-20 13:54:40'),
(3, 3, 'user', 1, 'user', 3, 'friend_accepted', NULL, 0, 0, '2011-05-20 13:55:13'),
(4, 3, 'user', 1, 'messages_conversation', 1, 'message_new', NULL, 1, 0, '2011-05-20 14:03:15');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_notificationsettings`
--

DROP TABLE IF EXISTS `engine4_activity_notificationsettings`;
CREATE TABLE IF NOT EXISTS `engine4_activity_notificationsettings` (
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `email` tinyint(4) NOT NULL DEFAULT '1',
  PRIMARY KEY (`user_id`,`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_activity_notificationsettings`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_notificationtypes`
--

DROP TABLE IF EXISTS `engine4_activity_notificationtypes`;
CREATE TABLE IF NOT EXISTS `engine4_activity_notificationtypes` (
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `module` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `is_request` tinyint(1) NOT NULL DEFAULT '0',
  `handler` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  PRIMARY KEY (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_activity_notificationtypes`
--

INSERT INTO `engine4_activity_notificationtypes` (`type`, `module`, `body`, `is_request`, `handler`) VALUES
('commented', 'activity', '{item:$subject} has commented on your {item:$object:$label}.', 0, ''),
('commented_commented', 'activity', '{item:$subject} has commented on a {item:$object:$label} you commented on.', 0, ''),
('event_accepted', 'event', 'Your request to join the event {item:$subject} has been approved.', 0, ''),
('event_approve', 'event', '{item:$object} has requested to join the event {item:$subject}.', 0, ''),
('event_discussion_reply', 'event', '{item:$subject} has {item:$object:posted} on a {itemParent:$object::event topic} you posted on.', 0, ''),
('event_discussion_response', 'event', '{item:$subject} has {item:$object:posted} on a {itemParent:$object::event topic} you created.', 0, ''),
('event_invite', 'event', '{item:$subject} has invited you to the event {item:$object}.', 0, ''),
('friend_accepted', 'user', 'You and {item:$subject} are now friends.', 0, ''),
('friend_follow', 'user', '{item:$subject} is now following you.', 0, ''),
('friend_follow_accepted', 'user', 'You are now following {item:$subject}.', 0, ''),
('friend_follow_request', 'user', '{item:$subject} has requested to follow you.', 1, 'user.friends.request-follow'),
('friend_request', 'user', '{item:$subject} has requested to be your friend.', 1, 'user.friends.request-friend'),
('group_accepted', 'group', 'Your request to join the group {item:$subject} has been approved.', 0, ''),
('group_approve', 'group', '{item:$object} has requested to join the group {item:$subject}.', 0, ''),
('group_discussion_reply', 'group', '{item:$subject} has {item:$object:posted} on a {itemParent:$object::group topic} you posted on.', 0, ''),
('group_discussion_response', 'group', '{item:$subject} has {item:$object:posted} on a {itemParent:$object::group topic} you created.', 0, ''),
('group_invite', 'group', '{item:$subject} has invited you to the group {item:$object}.', 1, 'group.widget.request-group'),
('group_promote', 'group', 'You were promoted to officer in the group {item:$object}.', 0, ''),
('liked', 'activity', '{item:$subject} likes your {item:$object:$label}.', 0, ''),
('liked_commented', 'activity', '{item:$subject} has commented on a {item:$object:$label} you liked.', 0, ''),
('message_new', 'messages', '{item:$subject} has sent you a {item:$object:message}.', 0, ''),
('post_user', 'user', '{item:$subject} has posted on your {item:$object:profile}.', 0, ''),
('tagged', 'user', '{item:$subject} tagged you in a {item:$object:$label}.', 0, ''),
('video_processed', 'video', 'Your {item:$object:video} is ready to be viewed.', 0, '');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_activity_stream`
--

DROP TABLE IF EXISTS `engine4_activity_stream`;
CREATE TABLE IF NOT EXISTS `engine4_activity_stream` (
  `target_type` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `target_id` int(11) unsigned NOT NULL,
  `subject_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `subject_id` int(11) unsigned NOT NULL,
  `object_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `object_id` int(11) unsigned NOT NULL,
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `action_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`target_type`,`target_id`,`action_id`),
  KEY `SUBJECT` (`subject_type`,`subject_id`,`action_id`),
  KEY `OBJECT` (`object_type`,`object_id`,`action_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_activity_stream`
--

INSERT INTO `engine4_activity_stream` (`target_type`, `target_id`, `subject_type`, `subject_id`, `object_type`, `object_id`, `type`, `action_id`) VALUES
('everyone', 0, 'user', 1, 'user', 1, 'profile_photo_update', 1),
('everyone', 0, 'user', 1, 'user', 1, 'post_self', 2),
('everyone', 0, 'user', 3, 'user', 1, 'friends', 4),
('everyone', 0, 'user', 1, 'user', 1, 'status', 6),
('everyone', 0, 'user', 1, 'user', 1, 'post_self', 7),
('members', 1, 'user', 1, 'user', 1, 'profile_photo_update', 1),
('members', 1, 'user', 1, 'user', 1, 'post_self', 2),
('members', 1, 'user', 3, 'user', 1, 'friends', 4),
('members', 1, 'user', 1, 'user', 1, 'status', 6),
('members', 1, 'user', 1, 'user', 1, 'post_self', 7),
('members', 3, 'user', 3, 'user', 3, 'signup', 3),
('members', 3, 'user', 1, 'user', 3, 'friends', 5),
('owner', 1, 'user', 1, 'user', 1, 'profile_photo_update', 1),
('owner', 1, 'user', 1, 'user', 1, 'post_self', 2),
('owner', 1, 'user', 1, 'user', 3, 'friends', 5),
('owner', 1, 'user', 1, 'user', 1, 'status', 6),
('owner', 1, 'user', 1, 'user', 1, 'post_self', 7),
('owner', 3, 'user', 3, 'user', 3, 'signup', 3),
('owner', 3, 'user', 3, 'user', 1, 'friends', 4),
('parent', 1, 'user', 1, 'user', 1, 'profile_photo_update', 1),
('parent', 1, 'user', 1, 'user', 1, 'post_self', 2),
('parent', 1, 'user', 3, 'user', 1, 'friends', 4),
('parent', 1, 'user', 1, 'user', 1, 'status', 6),
('parent', 1, 'user', 1, 'user', 1, 'post_self', 7),
('parent', 3, 'user', 3, 'user', 3, 'signup', 3),
('parent', 3, 'user', 1, 'user', 3, 'friends', 5),
('registered', 0, 'user', 1, 'user', 1, 'profile_photo_update', 1),
('registered', 0, 'user', 1, 'user', 1, 'post_self', 2),
('registered', 0, 'user', 3, 'user', 1, 'friends', 4),
('registered', 0, 'user', 1, 'user', 1, 'status', 6),
('registered', 0, 'user', 1, 'user', 1, 'post_self', 7);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_announcement_announcements`
--

DROP TABLE IF EXISTS `engine4_announcement_announcements`;
CREATE TABLE IF NOT EXISTS `engine4_announcement_announcements` (
  `announcement_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime DEFAULT NULL,
  PRIMARY KEY (`announcement_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_announcement_announcements`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_authorization_allow`
--

DROP TABLE IF EXISTS `engine4_authorization_allow`;
CREATE TABLE IF NOT EXISTS `engine4_authorization_allow` (
  `resource_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `action` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `role` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `role_id` int(11) unsigned NOT NULL DEFAULT '0',
  `value` tinyint(1) NOT NULL DEFAULT '0',
  `params` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`resource_type`,`resource_id`,`action`,`role`,`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_authorization_allow`
--

INSERT INTO `engine4_authorization_allow` (`resource_type`, `resource_id`, `action`, `role`, `role_id`, `value`, `params`) VALUES
('user', 1, 'comment', 'everyone', 0, 1, NULL),
('user', 1, 'comment', 'member', 0, 1, NULL),
('user', 1, 'comment', 'registered', 0, 1, NULL),
('user', 1, 'view', 'everyone', 0, 1, NULL),
('user', 1, 'view', 'member', 0, 1, NULL),
('user', 1, 'view', 'registered', 0, 1, NULL),
('user', 2, 'comment', 'member', 0, 1, NULL),
('user', 2, 'comment', 'network', 0, 1, NULL),
('user', 2, 'view', 'member', 0, 1, NULL),
('user', 2, 'view', 'network', 0, 1, NULL),
('user', 3, 'comment', 'member', 0, 1, NULL),
('user', 3, 'comment', 'network', 0, 1, NULL),
('user', 3, 'view', 'member', 0, 1, NULL),
('user', 3, 'view', 'network', 0, 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_authorization_levels`
--

DROP TABLE IF EXISTS `engine4_authorization_levels`;
CREATE TABLE IF NOT EXISTS `engine4_authorization_levels` (
  `level_id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `type` enum('public','user','moderator','admin') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'user',
  `flag` enum('default','superadmin','public') COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`level_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=6 ;

--
-- Dumping data for table `engine4_authorization_levels`
--

INSERT INTO `engine4_authorization_levels` (`level_id`, `title`, `description`, `type`, `flag`) VALUES
(1, 'Superadmins', 'Users of this level can modify all of your settings and data.  This level cannot be modified or deleted.', 'admin', 'superadmin'),
(2, 'Admins', 'Users of this level have full access to all of your network settings and data.', 'admin', ''),
(3, 'Moderators', 'Users of this level may edit user-side content.', 'moderator', ''),
(4, 'Default Level', 'This is the default user level.  New users are assigned to it automatically.', 'user', 'default'),
(5, 'Public', 'Settings for this level apply to users who have not logged in.', 'public', 'public');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_authorization_permissions`
--

DROP TABLE IF EXISTS `engine4_authorization_permissions`;
CREATE TABLE IF NOT EXISTS `engine4_authorization_permissions` (
  `level_id` int(11) unsigned NOT NULL,
  `type` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `name` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `value` tinyint(3) NOT NULL DEFAULT '0',
  `params` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`level_id`,`type`,`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_authorization_permissions`
--

INSERT INTO `engine4_authorization_permissions` (`level_id`, `type`, `name`, `value`, `params`) VALUES
(1, 'admin', 'view', 1, NULL),
(1, 'announcement', 'create', 1, NULL),
(1, 'announcement', 'delete', 2, NULL),
(1, 'announcement', 'edit', 2, NULL),
(1, 'announcement', 'view', 2, NULL),
(1, 'blog', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(1, 'blog', 'auth_html', 3, 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr'),
(1, 'blog', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(1, 'blog', 'comment', 2, NULL),
(1, 'blog', 'create', 1, NULL),
(1, 'blog', 'delete', 2, NULL),
(1, 'blog', 'edit', 2, NULL),
(1, 'blog', 'max', 3, '1000'),
(1, 'blog', 'style', 1, NULL),
(1, 'blog', 'view', 2, NULL),
(1, 'core_link', 'create', 1, NULL),
(1, 'core_link', 'delete', 2, NULL),
(1, 'core_link', 'view', 2, NULL),
(1, 'event', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(1, 'event', 'auth_photo', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(1, 'event', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(1, 'event', 'comment', 2, NULL),
(1, 'event', 'create', 1, NULL),
(1, 'event', 'delete', 2, NULL),
(1, 'event', 'edit', 2, NULL),
(1, 'event', 'invite', 1, NULL),
(1, 'event', 'photo', 1, NULL),
(1, 'event', 'style', 1, NULL),
(1, 'event', 'view', 2, NULL),
(1, 'general', 'activity', 2, NULL),
(1, 'general', 'style', 2, NULL),
(1, 'group', 'auth_comment', 5, '["registered", "member", "officer"]'),
(1, 'group', 'auth_photo', 5, '["registered", "member", "officer"]'),
(1, 'group', 'auth_view', 5, '["everyone", "registered", "member"]'),
(1, 'group', 'comment', 2, NULL),
(1, 'group', 'create', 1, NULL),
(1, 'group', 'delete', 2, NULL),
(1, 'group', 'edit', 2, NULL),
(1, 'group', 'invite', 1, NULL),
(1, 'group', 'photo', 1, NULL),
(1, 'group', 'style', 1, NULL),
(1, 'group', 'view', 2, NULL),
(1, 'messages', 'create', 1, NULL),
(1, 'poll', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(1, 'poll', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(1, 'poll', 'comment', 2, NULL),
(1, 'poll', 'create', 1, NULL),
(1, 'poll', 'delete', 2, NULL),
(1, 'poll', 'edit', 2, NULL),
(1, 'poll', 'view', 2, NULL),
(1, 'user', 'auth_comment', 5, '["everyone","registered","network","member","owner"]'),
(1, 'user', 'auth_view', 5, '["everyone","registered","network","member","owner"]'),
(1, 'user', 'block', 1, NULL),
(1, 'user', 'comment', 2, NULL),
(1, 'user', 'create', 1, NULL),
(1, 'user', 'delete', 2, NULL),
(1, 'user', 'edit', 2, NULL),
(1, 'user', 'search', 1, NULL),
(1, 'user', 'status', 1, NULL),
(1, 'user', 'style', 2, NULL),
(1, 'user', 'username', 2, NULL),
(1, 'user', 'view', 2, NULL),
(1, 'video', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(1, 'video', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(1, 'video', 'comment', 2, NULL),
(1, 'video', 'create', 1, NULL),
(1, 'video', 'delete', 2, NULL),
(1, 'video', 'edit', 2, NULL),
(1, 'video', 'max', 3, '20'),
(1, 'video', 'upload', 1, NULL),
(1, 'video', 'view', 2, NULL),
(2, 'admin', 'view', 1, NULL),
(2, 'announcement', 'create', 1, NULL),
(2, 'announcement', 'delete', 2, NULL),
(2, 'announcement', 'edit', 2, NULL),
(2, 'announcement', 'view', 2, NULL),
(2, 'blog', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(2, 'blog', 'auth_html', 3, 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr'),
(2, 'blog', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(2, 'blog', 'comment', 2, NULL),
(2, 'blog', 'create', 1, NULL),
(2, 'blog', 'delete', 2, NULL),
(2, 'blog', 'edit', 2, NULL),
(2, 'blog', 'max', 3, '1000'),
(2, 'blog', 'style', 1, NULL),
(2, 'blog', 'view', 2, NULL),
(2, 'core_link', 'create', 1, NULL),
(2, 'core_link', 'delete', 2, NULL),
(2, 'core_link', 'view', 2, NULL),
(2, 'event', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(2, 'event', 'auth_photo', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(2, 'event', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(2, 'event', 'comment', 2, NULL),
(2, 'event', 'create', 1, NULL),
(2, 'event', 'delete', 2, NULL),
(2, 'event', 'edit', 2, NULL),
(2, 'event', 'invite', 1, NULL),
(2, 'event', 'photo', 1, NULL),
(2, 'event', 'style', 1, NULL),
(2, 'event', 'view', 2, NULL),
(2, 'general', 'activity', 2, NULL),
(2, 'general', 'style', 2, NULL),
(2, 'group', 'auth_comment', 5, '["registered", "member", "officer"]'),
(2, 'group', 'auth_photo', 5, '["registered", "member", "officer"]'),
(2, 'group', 'auth_view', 5, '["everyone", "registered", "member"]'),
(2, 'group', 'comment', 2, NULL),
(2, 'group', 'create', 1, NULL),
(2, 'group', 'delete', 2, NULL),
(2, 'group', 'edit', 2, NULL),
(2, 'group', 'invite', 1, NULL),
(2, 'group', 'photo', 1, NULL),
(2, 'group', 'style', 1, NULL),
(2, 'group', 'view', 2, NULL),
(2, 'messages', 'create', 1, NULL),
(2, 'poll', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(2, 'poll', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(2, 'poll', 'comment', 2, NULL),
(2, 'poll', 'create', 1, NULL),
(2, 'poll', 'delete', 2, NULL),
(2, 'poll', 'edit', 2, NULL),
(2, 'poll', 'view', 2, NULL),
(2, 'user', 'auth_comment', 5, '["everyone","registered","network","member","owner"]'),
(2, 'user', 'auth_view', 5, '["everyone","registered","network","member","owner"]'),
(2, 'user', 'block', 1, NULL),
(2, 'user', 'comment', 2, NULL),
(2, 'user', 'create', 1, NULL),
(2, 'user', 'delete', 2, NULL),
(2, 'user', 'edit', 2, NULL),
(2, 'user', 'search', 1, NULL),
(2, 'user', 'status', 1, NULL),
(2, 'user', 'style', 2, NULL),
(2, 'user', 'username', 2, NULL),
(2, 'user', 'view', 2, NULL),
(2, 'video', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(2, 'video', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(2, 'video', 'comment', 2, NULL),
(2, 'video', 'create', 1, NULL),
(2, 'video', 'delete', 2, NULL),
(2, 'video', 'edit', 2, NULL),
(2, 'video', 'max', 3, '20'),
(2, 'video', 'upload', 1, NULL),
(2, 'video', 'view', 2, NULL),
(3, 'announcement', 'view', 1, NULL),
(3, 'blog', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(3, 'blog', 'auth_html', 3, 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr'),
(3, 'blog', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(3, 'blog', 'comment', 2, NULL),
(3, 'blog', 'create', 1, NULL),
(3, 'blog', 'delete', 2, NULL),
(3, 'blog', 'edit', 2, NULL),
(3, 'blog', 'max', 3, '1000'),
(3, 'blog', 'style', 1, NULL),
(3, 'blog', 'view', 2, NULL),
(3, 'core_link', 'create', 1, NULL),
(3, 'core_link', 'delete', 2, NULL),
(3, 'core_link', 'view', 2, NULL),
(3, 'event', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(3, 'event', 'auth_photo', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(3, 'event', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(3, 'event', 'comment', 2, NULL),
(3, 'event', 'create', 1, NULL),
(3, 'event', 'delete', 2, NULL),
(3, 'event', 'edit', 2, NULL),
(3, 'event', 'invite', 1, NULL),
(3, 'event', 'photo', 1, NULL),
(3, 'event', 'style', 1, NULL),
(3, 'event', 'view', 2, NULL),
(3, 'general', 'activity', 2, NULL),
(3, 'general', 'style', 2, NULL),
(3, 'group', 'auth_comment', 5, '["registered", "member", "officer"]'),
(3, 'group', 'auth_photo', 5, '["registered", "member", "officer"]'),
(3, 'group', 'auth_view', 5, '["everyone", "registered", "member"]'),
(3, 'group', 'comment', 2, NULL),
(3, 'group', 'create', 1, NULL),
(3, 'group', 'delete', 2, NULL),
(3, 'group', 'edit', 2, NULL),
(3, 'group', 'invite', 1, NULL),
(3, 'group', 'photo', 1, NULL),
(3, 'group', 'style', 1, NULL),
(3, 'group', 'view', 2, NULL),
(3, 'messages', 'create', 1, NULL),
(3, 'poll', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(3, 'poll', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(3, 'poll', 'comment', 2, NULL),
(3, 'poll', 'create', 1, NULL),
(3, 'poll', 'delete', 2, NULL),
(3, 'poll', 'edit', 2, NULL),
(3, 'poll', 'view', 2, NULL),
(3, 'user', 'auth_comment', 5, '["everyone","registered","network","member","owner"]'),
(3, 'user', 'auth_view', 5, '["everyone","registered","network","member","owner"]'),
(3, 'user', 'block', 1, NULL),
(3, 'user', 'comment', 2, NULL),
(3, 'user', 'create', 1, NULL),
(3, 'user', 'delete', 2, NULL),
(3, 'user', 'edit', 2, NULL),
(3, 'user', 'search', 1, NULL),
(3, 'user', 'status', 1, NULL),
(3, 'user', 'style', 2, NULL),
(3, 'user', 'username', 2, NULL),
(3, 'user', 'view', 2, NULL),
(3, 'video', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(3, 'video', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(3, 'video', 'comment', 2, NULL),
(3, 'video', 'create', 1, NULL),
(3, 'video', 'delete', 2, NULL),
(3, 'video', 'edit', 2, NULL),
(3, 'video', 'max', 3, '20'),
(3, 'video', 'upload', 1, NULL),
(3, 'video', 'view', 2, NULL),
(4, 'announcement', 'view', 1, NULL),
(4, 'blog', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(4, 'blog', 'auth_html', 3, 'strong, b, em, i, u, strike, sub, sup, p, div, pre, address, h1, h2, h3, h4, h5, h6, span, ol, li, ul, a, img, embed, br, hr'),
(4, 'blog', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(4, 'blog', 'comment', 1, NULL),
(4, 'blog', 'create', 1, NULL),
(4, 'blog', 'delete', 1, NULL),
(4, 'blog', 'edit', 1, NULL),
(4, 'blog', 'max', 3, '50'),
(4, 'blog', 'style', 1, NULL),
(4, 'blog', 'view', 1, NULL),
(4, 'core_link', 'create', 1, NULL),
(4, 'core_link', 'delete', 1, NULL),
(4, 'core_link', 'view', 1, NULL),
(4, 'event', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(4, 'event', 'auth_photo', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(4, 'event', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","parent_member","member","owner"]'),
(4, 'event', 'comment', 1, NULL),
(4, 'event', 'create', 1, NULL),
(4, 'event', 'delete', 1, NULL),
(4, 'event', 'edit', 1, NULL),
(4, 'event', 'invite', 1, NULL),
(4, 'event', 'photo', 1, NULL),
(4, 'event', 'style', 1, NULL),
(4, 'event', 'view', 1, NULL),
(4, 'general', 'style', 1, NULL),
(4, 'group', 'auth_comment', 5, '["registered","member","officer"]'),
(4, 'group', 'auth_photo', 5, '["registered","member","officer"]'),
(4, 'group', 'auth_view', 5, '["everyone","registered","member"]'),
(4, 'group', 'comment', 1, NULL),
(4, 'group', 'create', 1, NULL),
(4, 'group', 'delete', 1, NULL),
(4, 'group', 'edit', 1, NULL),
(4, 'group', 'invite', 1, NULL),
(4, 'group', 'photo', 1, NULL),
(4, 'group', 'style', 0, NULL),
(4, 'group', 'view', 1, NULL),
(4, 'messages', 'create', 1, NULL),
(4, 'poll', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(4, 'poll', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(4, 'poll', 'comment', 1, NULL),
(4, 'poll', 'create', 1, NULL),
(4, 'poll', 'delete', 1, NULL),
(4, 'poll', 'edit', 1, NULL),
(4, 'poll', 'view', 1, NULL),
(4, 'user', 'auth_comment', 5, '["network","member","owner"]'),
(4, 'user', 'auth_view', 5, '["network","member","owner"]'),
(4, 'user', 'block', 1, NULL),
(4, 'user', 'comment', 1, NULL),
(4, 'user', 'commenthtml', 3, ''),
(4, 'user', 'create', 1, NULL),
(4, 'user', 'delete', 0, NULL),
(4, 'user', 'edit', 1, NULL),
(4, 'user', 'quota', 0, NULL),
(4, 'user', 'search', 1, NULL),
(4, 'user', 'status', 1, NULL),
(4, 'user', 'style', 0, NULL),
(4, 'user', 'title', 3, 'Default Level'),
(4, 'user', 'username', 1, NULL),
(4, 'user', 'view', 1, NULL),
(4, 'video', 'auth_comment', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(4, 'video', 'auth_view', 5, '["everyone","owner_network","owner_member_member","owner_member","owner"]'),
(4, 'video', 'comment', 1, NULL),
(4, 'video', 'create', 1, NULL),
(4, 'video', 'delete', 1, NULL),
(4, 'video', 'edit', 1, NULL),
(4, 'video', 'max', 3, '20'),
(4, 'video', 'upload', 1, NULL),
(4, 'video', 'view', 1, NULL),
(5, 'announcement', 'view', 1, NULL),
(5, 'blog', 'view', 1, NULL),
(5, 'core_link', 'view', 1, NULL),
(5, 'event', 'view', 1, NULL),
(5, 'group', 'view', 1, NULL),
(5, 'poll', 'view', 1, NULL),
(5, 'user', 'title', 3, 'Public'),
(5, 'user', 'view', 0, NULL),
(5, 'video', 'view', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_blog_blogs`
--

DROP TABLE IF EXISTS `engine4_blog_blogs`;
CREATE TABLE IF NOT EXISTS `engine4_blog_blogs` (
  `blog_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `body` longtext COLLATE utf8_unicode_ci NOT NULL,
  `owner_type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `draft` tinyint(1) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`blog_id`),
  KEY `owner_type` (`owner_type`,`owner_id`),
  KEY `search` (`search`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_blog_blogs`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_blog_categories`
--

DROP TABLE IF EXISTS `engine4_blog_categories`;
CREATE TABLE IF NOT EXISTS `engine4_blog_categories` (
  `category_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `category_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `engine4_blog_categories`
--

INSERT INTO `engine4_blog_categories` (`category_id`, `user_id`, `category_name`) VALUES
(1, 1, 'Arts & Culture'),
(2, 1, 'Business'),
(3, 1, 'Entertainment'),
(5, 1, 'Family & Home'),
(6, 1, 'Health'),
(7, 1, 'Recreation'),
(8, 1, 'Personal'),
(9, 1, 'Shopping'),
(10, 1, 'Society'),
(11, 1, 'Sports'),
(12, 1, 'Technology'),
(13, 1, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_adcampaigns`
--

DROP TABLE IF EXISTS `engine4_core_adcampaigns`;
CREATE TABLE IF NOT EXISTS `engine4_core_adcampaigns` (
  `adcampaign_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `end_settings` tinyint(4) NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `start_time` datetime NOT NULL,
  `end_time` datetime NOT NULL,
  `limit_view` int(11) unsigned NOT NULL DEFAULT '0',
  `limit_click` int(11) unsigned NOT NULL DEFAULT '0',
  `limit_ctr` varchar(11) COLLATE utf8_unicode_ci NOT NULL DEFAULT '0',
  `network` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `level` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  `clicks` int(11) unsigned NOT NULL DEFAULT '0',
  `public` tinyint(4) NOT NULL DEFAULT '0',
  `status` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`adcampaign_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_adcampaigns`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_adphotos`
--

DROP TABLE IF EXISTS `engine4_core_adphotos`;
CREATE TABLE IF NOT EXISTS `engine4_core_adphotos` (
  `adphoto_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `ad_id` int(11) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`adphoto_id`),
  KEY `ad_id` (`ad_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_adphotos`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_ads`
--

DROP TABLE IF EXISTS `engine4_core_ads`;
CREATE TABLE IF NOT EXISTS `engine4_core_ads` (
  `ad_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `ad_campaign` int(11) unsigned NOT NULL,
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  `clicks` int(11) unsigned NOT NULL DEFAULT '0',
  `media_type` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `html_code` text COLLATE utf8_unicode_ci NOT NULL,
  `photo_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`ad_id`),
  KEY `ad_campaign` (`ad_campaign`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_ads`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_auth`
--

DROP TABLE IF EXISTS `engine4_core_auth`;
CREATE TABLE IF NOT EXISTS `engine4_core_auth` (
  `id` varchar(40) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `expires` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`,`user_id`),
  KEY `expires` (`expires`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_auth`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_comments`
--

DROP TABLE IF EXISTS `engine4_core_comments`;
CREATE TABLE IF NOT EXISTS `engine4_core_comments` (
  `comment_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `poster_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `poster_id` int(11) unsigned NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`comment_id`),
  KEY `resource_type` (`resource_type`,`resource_id`),
  KEY `poster_type` (`poster_type`,`poster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_comments`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_content`
--

DROP TABLE IF EXISTS `engine4_core_content`;
CREATE TABLE IF NOT EXISTS `engine4_core_content` (
  `content_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `page_id` int(11) unsigned NOT NULL,
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'widget',
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `parent_content_id` int(11) unsigned DEFAULT NULL,
  `order` int(11) NOT NULL DEFAULT '1',
  `params` text COLLATE utf8_unicode_ci,
  `attribs` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`content_id`),
  KEY `page_id` (`page_id`,`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=583 ;

--
-- Dumping data for table `engine4_core_content`
--

INSERT INTO `engine4_core_content` (`content_id`, `page_id`, `type`, `name`, `parent_content_id`, `order`, `params`, `attribs`) VALUES
(100, 1, 'container', 'main', NULL, 1, '', NULL),
(110, 1, 'widget', 'core.menu-mini', 100, 1, '', NULL),
(111, 1, 'widget', 'core.menu-logo', 100, 2, '', NULL),
(112, 1, 'widget', 'core.menu-main', 100, 3, '', NULL),
(200, 2, 'container', 'main', NULL, 1, '', NULL),
(210, 2, 'widget', 'core.menu-footer', 200, 2, '', NULL),
(300, 3, 'container', 'main', NULL, 1, '', NULL),
(310, 3, 'container', 'left', 300, 1, '', NULL),
(311, 3, 'container', 'right', 300, 2, '', NULL),
(312, 3, 'container', 'middle', 300, 3, '', NULL),
(320, 3, 'widget', 'user.login-or-signup', 310, 1, '', NULL),
(321, 3, 'widget', 'user.list-online', 310, 2, '{"title":"%s Members Online"}', NULL),
(322, 3, 'widget', 'core.statistics', 310, 3, '{"title":"Network Stats"}', NULL),
(330, 3, 'widget', 'user.list-signups', 311, 1, '{"title":"Newest Members"}', NULL),
(331, 3, 'widget', 'user.list-popular', 311, 2, '{"title":"Popular Members"}', NULL),
(340, 3, 'widget', 'announcement.list-announcements', 312, 1, '', NULL),
(341, 3, 'widget', 'activity.feed', 312, 2, '{"title":"What''s New"}', NULL),
(400, 4, 'container', 'main', NULL, 1, '', NULL),
(410, 4, 'container', 'left', 400, 1, '', NULL),
(411, 4, 'container', 'right', 400, 2, '', NULL),
(412, 4, 'container', 'middle', 400, 3, '', NULL),
(420, 4, 'widget', 'user.home-photo', 410, 1, '', NULL),
(421, 4, 'widget', 'user.home-links', 410, 2, '', NULL),
(422, 4, 'widget', 'user.list-online', 410, 3, '{"title":"%s Members Online"}', NULL),
(423, 4, 'widget', 'core.statistics', 410, 4, '{"title":"Network Stats"}', NULL),
(430, 4, 'widget', 'activity.list-requests', 411, 1, '{"title":"Requests"}', NULL),
(431, 4, 'widget', 'user.list-signups', 411, 2, '{"title":"Newest Members"}', NULL),
(432, 4, 'widget', 'user.list-popular', 411, 3, '{"title":"Popular Members"}', NULL),
(440, 4, 'widget', 'announcement.list-announcements', 412, 1, '', NULL),
(441, 4, 'widget', 'activity.feed', 412, 2, '{"title":"What''s New"}', NULL),
(500, 5, 'container', 'main', NULL, 1, '', NULL),
(510, 5, 'container', 'left', 500, 1, '', NULL),
(511, 5, 'container', 'middle', 500, 3, '', NULL),
(520, 5, 'widget', 'user.profile-photo', 510, 1, '', NULL),
(521, 5, 'widget', 'user.profile-options', 510, 2, '', NULL),
(522, 5, 'widget', 'user.profile-friends-common', 510, 3, '{"title":"Mutual Friends"}', NULL),
(523, 5, 'widget', 'user.profile-info', 510, 4, '{"title":"Member Info"}', NULL),
(530, 5, 'widget', 'user.profile-status', 511, 1, '', NULL),
(531, 5, 'widget', 'core.container-tabs', 511, 2, '{"max":"6"}', NULL),
(540, 5, 'widget', 'activity.feed', 531, 1, '{"title":"Updates"}', NULL),
(541, 5, 'widget', 'user.profile-fields', 531, 2, '{"title":"Info"}', NULL),
(542, 5, 'widget', 'user.profile-friends', 531, 3, '{"title":"Friends","titleCount":true}', NULL),
(546, 5, 'widget', 'core.profile-links', 531, 7, '{"title":"Links","titleCount":true}', NULL),
(547, 6, 'container', 'main', NULL, 2, NULL, NULL),
(548, 6, 'container', 'middle', 547, 6, NULL, NULL),
(549, 6, 'widget', 'user.login-or-signup', 548, 3, NULL, NULL),
(550, 5, 'widget', 'blog.profile-blogs', 531, 6, '{"title":"Blogs","titleCount":true}', NULL),
(551, 5, 'widget', 'group.profile-groups', 531, 9, '{"title":"Groups","titleCount":true}', NULL),
(552, 7, 'container', 'main', NULL, 1, '', NULL),
(553, 7, 'container', 'middle', 552, 3, '', NULL),
(554, 7, 'container', 'left', 552, 1, '', NULL),
(555, 7, 'widget', 'core.container-tabs', 553, 2, '{"max":"6"}', NULL),
(556, 7, 'widget', 'group.profile-status', 553, 1, '', NULL),
(557, 7, 'widget', 'group.profile-photo', 554, 1, '', NULL),
(558, 7, 'widget', 'group.profile-options', 554, 2, '', NULL),
(559, 7, 'widget', 'group.profile-info', 554, 3, '', NULL),
(560, 7, 'widget', 'activity.feed', 555, 1, '{"title":"Updates"}', NULL),
(561, 7, 'widget', 'group.profile-members', 555, 2, '{"title":"Members","titleCount":true}', NULL),
(562, 7, 'widget', 'group.profile-photos', 555, 3, '{"title":"Photos","titleCount":true}', NULL),
(563, 7, 'widget', 'group.profile-discussions', 555, 4, '{"title":"Discussions","titleCount":true}', NULL),
(564, 7, 'widget', 'core.profile-links', 555, 5, '{"title":"Links","titleCount":true}', NULL),
(565, 7, 'widget', 'group.profile-events', 555, 6, '{"title":"Events","titleCount":true}', NULL),
(566, 5, 'widget', 'event.profile-events', 531, 8, '{"title":"Events","titleCount":true}', NULL),
(567, 8, 'container', 'main', NULL, 1, '', NULL),
(568, 8, 'container', 'middle', 567, 3, '', NULL),
(569, 8, 'container', 'left', 567, 1, '', NULL),
(570, 8, 'widget', 'core.container-tabs', 568, 2, '{"max":"6"}', NULL),
(571, 8, 'widget', 'event.profile-status', 568, 1, '', NULL),
(572, 8, 'widget', 'event.profile-photo', 569, 1, '', NULL),
(573, 8, 'widget', 'event.profile-options', 569, 2, '', NULL),
(574, 8, 'widget', 'event.profile-info', 569, 3, '', NULL),
(575, 8, 'widget', 'event.profile-rsvp', 569, 4, '', NULL),
(576, 8, 'widget', 'activity.feed', 570, 1, '{"title":"Updates"}', NULL),
(577, 8, 'widget', 'event.profile-members', 570, 2, '{"title":"Guests","titleCount":true}', NULL),
(578, 8, 'widget', 'event.profile-photos', 570, 3, '{"title":"Photos","titleCount":true}', NULL),
(579, 8, 'widget', 'event.profile-discussions', 570, 4, '{"title":"Discussions","titleCount":true}', NULL),
(580, 8, 'widget', 'core.profile-links', 570, 5, '{"title":"Links","titleCount":true}', NULL),
(581, 5, 'widget', 'poll.profile-polls', 531, 11, '{"title":"Polls","titleCount":true}', NULL),
(582, 5, 'widget', 'video.profile-videos', 531, 12, '{"title":"Videos","titleCount":true}', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_geotags`
--

DROP TABLE IF EXISTS `engine4_core_geotags`;
CREATE TABLE IF NOT EXISTS `engine4_core_geotags` (
  `geotag_id` int(11) unsigned NOT NULL,
  `latitude` float NOT NULL,
  `longitude` float NOT NULL,
  PRIMARY KEY (`geotag_id`),
  KEY `latitude` (`latitude`,`longitude`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_geotags`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_languages`
--

DROP TABLE IF EXISTS `engine4_core_languages`;
CREATE TABLE IF NOT EXISTS `engine4_core_languages` (
  `language_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `code` varchar(8) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `fallback` varchar(8) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `order` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`language_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `engine4_core_languages`
--

INSERT INTO `engine4_core_languages` (`language_id`, `code`, `name`, `fallback`, `order`) VALUES
(1, 'en', 'English', 'en', 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_likes`
--

DROP TABLE IF EXISTS `engine4_core_likes`;
CREATE TABLE IF NOT EXISTS `engine4_core_likes` (
  `like_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `poster_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `poster_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`like_id`),
  KEY `resource_type` (`resource_type`,`resource_id`),
  KEY `poster_type` (`poster_type`,`poster_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_likes`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_links`
--

DROP TABLE IF EXISTS `engine4_core_links`;
CREATE TABLE IF NOT EXISTS `engine4_core_links` (
  `link_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `photo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `parent_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `parent_id` int(11) unsigned NOT NULL,
  `owner_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `view_count` mediumint(6) unsigned NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL,
  `search` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`link_id`),
  KEY `owner` (`owner_type`,`owner_id`),
  KEY `parent` (`parent_type`,`parent_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=3 ;

--
-- Dumping data for table `engine4_core_links`
--

INSERT INTO `engine4_core_links` (`link_id`, `uri`, `title`, `description`, `photo_id`, `parent_type`, `parent_id`, `owner_type`, `owner_id`, `view_count`, `creation_date`, `search`) VALUES
(1, 'http://habrahabr.ru/company/google/blog/', 'Блог / Google / Компании / Хабрахабр', 'Блог / Google / Компании / Хабрахабр', 5, 'user', 1, 'user', 1, 0, '2011-05-20 13:45:35', 1),
(2, 'http://www.last.fm/', 'Last.fm - Listen to internet radio and the largest music catalogue online', 'Last.fm - Listen to internet radio and the largest music catalogue online', 6, 'user', 1, 'user', 1, 0, '2011-05-20 15:12:02', 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_listitems`
--

DROP TABLE IF EXISTS `engine4_core_listitems`;
CREATE TABLE IF NOT EXISTS `engine4_core_listitems` (
  `listitem_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_id` int(11) unsigned NOT NULL,
  `child_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`listitem_id`),
  KEY `list_id` (`list_id`),
  KEY `child_id` (`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_listitems`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_lists`
--

DROP TABLE IF EXISTS `engine4_core_lists`;
CREATE TABLE IF NOT EXISTS `engine4_core_lists` (
  `list_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `owner_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `child_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `child_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`list_id`),
  KEY `owner_type` (`owner_type`,`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_lists`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_mail`
--

DROP TABLE IF EXISTS `engine4_core_mail`;
CREATE TABLE IF NOT EXISTS `engine4_core_mail` (
  `mail_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('system','zend') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `priority` smallint(3) DEFAULT '100',
  `recipient_count` int(11) unsigned DEFAULT '0',
  `recipient_total` int(10) NOT NULL DEFAULT '0',
  `creation_time` datetime NOT NULL,
  PRIMARY KEY (`mail_id`),
  KEY `priority` (`priority`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `engine4_core_mail`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_mailrecipients`
--

DROP TABLE IF EXISTS `engine4_core_mailrecipients`;
CREATE TABLE IF NOT EXISTS `engine4_core_mailrecipients` (
  `recipient_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `mail_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `email` varchar(128) DEFAULT NULL,
  PRIMARY KEY (`recipient_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=7 ;

--
-- Dumping data for table `engine4_core_mailrecipients`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_mailtemplates`
--

DROP TABLE IF EXISTS `engine4_core_mailtemplates`;
CREATE TABLE IF NOT EXISTS `engine4_core_mailtemplates` (
  `mailtemplate_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `module` varchar(64) NOT NULL DEFAULT '',
  `vars` varchar(255) NOT NULL,
  PRIMARY KEY (`mailtemplate_id`),
  UNIQUE KEY `type` (`type`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=37 ;

--
-- Dumping data for table `engine4_core_mailtemplates`
--

INSERT INTO `engine4_core_mailtemplates` (`mailtemplate_id`, `type`, `module`, `vars`) VALUES
(1, 'header', 'core', ''),
(2, 'footer', 'core', ''),
(3, 'header_member', 'core', ''),
(4, 'footer_member', 'core', ''),
(5, 'core_contact', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_name],[sender_email],[sender_link],[sender_photo],[message]'),
(6, 'core_verification', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link]'),
(7, 'core_verification_password', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[password]'),
(8, 'core_welcome', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link]'),
(9, 'core_welcome_password', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link],[password]'),
(10, 'core_lostpassword', 'core', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[object_link]'),
(11, 'notify_commented', 'activity', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(12, 'notify_commented_commented', 'activity', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(13, 'notify_liked', 'activity', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(14, 'notify_liked_commented', 'activity', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(15, 'notify_friend_accepted', 'user', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(16, 'notify_friend_request', 'user', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(17, 'notify_friend_follow_request', 'user', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(18, 'notify_friend_follow_accepted', 'user', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(19, 'notify_friend_follow', 'user', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(20, 'notify_post_user', 'user', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(21, 'notify_tagged', 'user', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(22, 'notify_message_new', 'messages', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(23, 'invite', 'invite', '[host],[email],[sender_email],[sender_title],[sender_link],[sender_photo],[message],[object_link],[code]'),
(24, 'invite_code', 'invite', '[host],[email],[sender_email],[sender_title],[sender_link],[sender_photo],[message],[object_link],[code]'),
(25, 'notify_group_accepted', 'group', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(26, 'notify_group_approve', 'group', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(27, 'notify_group_discussion_reply', 'group', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(28, 'notify_group_discussion_response', 'group', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(29, 'notify_group_invite', 'group', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(30, 'notify_group_promote', 'group', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(31, 'notify_event_accepted', 'event', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(32, 'notify_event_approve', 'event', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(33, 'notify_event_discussion_response', 'event', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(34, 'notify_event_discussion_reply', 'event', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(35, 'notify_event_invite', 'event', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]'),
(36, 'notify_video_processed', 'video', '[host],[email],[recipient_title],[recipient_link],[recipient_photo],[sender_title],[sender_link],[sender_photo],[object_title],[object_link],[object_photo],[object_description]');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_menuitems`
--

DROP TABLE IF EXISTS `engine4_core_menuitems`;
CREATE TABLE IF NOT EXISTS `engine4_core_menuitems` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `module` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `label` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `plugin` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `params` text COLLATE utf8_unicode_ci NOT NULL,
  `menu` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `submenu` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `custom` tinyint(1) NOT NULL DEFAULT '0',
  `order` smallint(6) NOT NULL DEFAULT '999',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `LOOKUP` (`name`,`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=154 ;

--
-- Dumping data for table `engine4_core_menuitems`
--

INSERT INTO `engine4_core_menuitems` (`id`, `name`, `module`, `label`, `plugin`, `params`, `menu`, `submenu`, `enabled`, `custom`, `order`) VALUES
(1, 'core_main_home', 'core', 'Home', 'User_Plugin_Menus', '', 'core_main', '', 1, 0, 1),
(2, 'core_sitemap_home', 'core', 'Home', '', '{"route":"default"}', 'core_sitemap', '', 1, 0, 1),
(3, 'core_footer_privacy', 'core', 'Privacy', '', '{"route":"default","module":"core","controller":"help","action":"privacy"}', 'core_footer', '', 1, 0, 1),
(4, 'core_footer_terms', 'core', 'Terms of Service', '', '{"route":"default","module":"core","controller":"help","action":"terms"}', 'core_footer', '', 1, 0, 2),
(5, 'core_footer_contact', 'core', 'Contact', '', '{"route":"default","module":"core","controller":"help","action":"contact"}', 'core_footer', '', 1, 0, 3),
(6, 'core_mini_admin', 'core', 'Admin', 'User_Plugin_Menus', '', 'core_mini', '', 1, 0, 6),
(7, 'core_mini_profile', 'user', 'My Profile', 'User_Plugin_Menus', '', 'core_mini', '', 1, 0, 5),
(8, 'core_mini_settings', 'user', 'Settings', 'User_Plugin_Menus', '', 'core_mini', '', 1, 0, 3),
(9, 'core_mini_auth', 'user', 'Auth', 'User_Plugin_Menus', '', 'core_mini', '', 1, 0, 2),
(10, 'core_mini_signup', 'user', 'Signup', 'User_Plugin_Menus', '', 'core_mini', '', 1, 0, 1),
(11, 'core_admin_main_home', 'core', 'Home', '', '{"route":"admin_default"}', 'core_admin_main', '', 1, 0, 1),
(12, 'core_admin_main_manage', 'core', 'Manage', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_manage', 1, 0, 2),
(13, 'core_admin_main_settings', 'core', 'Settings', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_settings', 1, 0, 3),
(14, 'core_admin_main_plugins', 'core', 'Plugins', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_plugins', 1, 0, 4),
(15, 'core_admin_main_layout', 'core', 'Layout', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_layout', 1, 0, 5),
(16, 'core_admin_main_ads', 'core', 'Ads', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_ads', 1, 0, 6),
(17, 'core_admin_main_stats', 'core', 'Stats', '', '{"uri":"javascript:void(0);this.blur();"}', 'core_admin_main', 'core_admin_main_stats', 1, 0, 7),
(18, 'core_admin_main_manage_levels', 'core', 'Member Levels', '', '{"route":"admin_default","module":"authorization","controller":"level"}', 'core_admin_main_manage', '', 1, 0, 2),
(19, 'core_admin_main_manage_networks', 'network', 'Networks', '', '{"route":"admin_default","module":"network","controller":"manage"}', 'core_admin_main_manage', '', 1, 0, 3),
(20, 'core_admin_main_manage_announcements', 'announcement', 'Announcements', '', '{"route":"admin_default","module":"announcement","controller":"manage"}', 'core_admin_main_manage', '', 1, 0, 4),
(21, 'core_admin_message_mail', 'core', 'Email All Members', '', '{"route":"admin_default","module":"core","controller":"message","action":"mail"}', 'core_admin_main_manage', '', 1, 0, 5),
(22, 'core_admin_main_manage_reports', 'core', 'Abuse Reports', '', '{"route":"admin_default","module":"core","controller":"report"}', 'core_admin_main_manage', '', 1, 0, 6),
(23, 'core_admin_main_manage_packages', 'core', 'Packages & Plugins', '', '{"route":"admin_default","module":"core","controller":"packages"}', 'core_admin_main_manage', '', 1, 0, 7),
(24, 'core_admin_main_settings_general', 'core', 'General Settings', '', '{"route":"core_admin_settings","action":"general"}', 'core_admin_main_settings', '', 1, 0, 1),
(25, 'core_admin_main_settings_locale', 'core', 'Locale Settings', '', '{"route":"core_admin_settings","action":"locale"}', 'core_admin_main_settings', '', 1, 0, 1),
(26, 'core_admin_main_settings_fields', 'fields', 'Profile Questions', '', '{"route":"admin_default","module":"user","controller":"fields"}', 'core_admin_main_settings', '', 1, 0, 2),
(27, 'core_admin_main_settings_spam', 'core', 'Spam & Banning Tools', '', '{"route":"core_admin_settings","action":"spam"}', 'core_admin_main_settings', '', 1, 0, 5),
(28, 'core_admin_main_settings_mailtemplates', 'core', 'Mail Templates', '', '{"route":"admin_default","controller":"mail","action":"templates"}', 'core_admin_main_settings', '', 1, 0, 6),
(29, 'core_admin_main_settings_mailsettings', 'core', 'Mail Settings', '', '{"route":"admin_default","controller":"mail","action":"settings"}', 'core_admin_main_settings', '', 1, 0, 7),
(30, 'core_admin_main_settings_performance', 'core', 'Performance & Caching', '', '{"route":"core_admin_settings","action":"performance"}', 'core_admin_main_settings', '', 1, 0, 8),
(31, 'core_admin_main_settings_password', 'core', 'Admin Password', '', '{"route":"core_admin_settings","action":"password"}', 'core_admin_main_settings', '', 1, 0, 9),
(32, 'core_admin_main_settings_tasks', 'core', 'Task Scheduler', '', '{"route":"admin_default","controller":"tasks"}', 'core_admin_main_settings', '', 1, 0, 10),
(33, 'core_admin_main_layout_content', 'core', 'Layout Editor', '', '{"route":"admin_default","controller":"content"}', 'core_admin_main_layout', '', 1, 0, 1),
(34, 'core_admin_main_layout_themes', 'core', 'Theme Editor', '', '{"route":"admin_default","controller":"themes"}', 'core_admin_main_layout', '', 1, 0, 2),
(35, 'core_admin_main_layout_files', 'core', 'File & Media Manager', '', '{"route":"admin_default","controller":"files"}', 'core_admin_main_layout', '', 1, 0, 3),
(36, 'core_admin_main_layout_language', 'core', 'Language Manager', '', '{"route":"admin_default","controller":"language"}', 'core_admin_main_layout', '', 1, 0, 4),
(37, 'core_admin_main_layout_menus', 'core', 'Menu Editor', '', '{"route":"admin_default","controller":"menus"}', 'core_admin_main_layout', '', 1, 0, 5),
(38, 'core_admin_main_ads_manage', 'core', 'Manage Ad Campaigns', '', '{"route":"admin_default","controller":"ads"}', 'core_admin_main_ads', '', 1, 0, 1),
(39, 'core_admin_main_ads_create', 'core', 'Create New Campaign', '', '{"route":"admin_default","controller":"ads","action":"create"}', 'core_admin_main_ads', '', 1, 0, 2),
(40, 'core_admin_main_stats_statistics', 'core', 'Site-wide Statistics', '', '{"route":"admin_default","controller":"stats"}', 'core_admin_main_stats', '', 1, 0, 1),
(41, 'core_admin_main_stats_url', 'core', 'Referring URLs', '', '{"route":"admin_default","controller":"stats","action":"referrers"}', 'core_admin_main_stats', '', 1, 0, 2),
(42, 'core_admin_main_stats_resources', 'core', 'Server Information', '', '{"route":"admin_default","controller":"system"}', 'core_admin_main_stats', '', 1, 0, 3),
(43, 'core_admin_main_stats_logs', 'core', 'Log Browser', '', '{"route":"admin_default","controller":"system","action":"log"}', 'core_admin_main_stats', '', 1, 0, 3),
(44, 'adcampaign_admin_main_edit', 'core', 'Edit Settings', '', '{"route":"admin_default","module":"core","controller":"ads","action":"edit"}', 'adcampaign_admin_main', '', 1, 0, 1),
(45, 'adcampaign_admin_main_manageads', 'core', 'Manage Advertisements', '', '{"route":"admin_default","module":"core","controller":"ads","action":"manageads"}', 'adcampaign_admin_main', '', 1, 0, 2),
(46, 'core_admin_main_settings_activity', 'activity', 'Activity Feed Settings', '', '{"route":"activity_admin_settings_general"}', 'core_admin_main_settings', '', 1, 0, 4),
(47, 'authorization_admin_main_manage', 'authorization', 'View Member Levels', '', '{"route":"admin_default","module":"authorization","controller":"level"}', 'authorization_admin_main', '', 1, 0, 1),
(48, 'authorization_admin_main_level', 'authorization', 'Member Level Settings', '', '{"route":"admin_default","module":"authorization","controller":"level","action":"edit"}', 'authorization_admin_main', '', 1, 0, 3),
(49, 'authorization_admin_level_main', 'authorization', 'Level Info', '', '{"route":"admin_default","module":"authorization","controller":"level","action":"edit"}', 'authorization_admin_level', '', 1, 0, 1),
(50, 'core_main_user', 'user', 'Members', '', '{"route":"user_general","action":"browse"}', 'core_main', '', 0, 0, 3),
(51, 'core_sitemap_user', 'user', 'Members', '', '{"route":"user_general","action":"browse"}', 'core_sitemap', '', 1, 0, 2),
(52, 'user_home_updates', 'user', 'View Recent Updates', '', '{"route":"recent_activity","icon":"application/modules/User/externals/images/links/updates.png"}', 'user_home', '', 1, 0, 3),
(53, 'user_home_view', 'user', 'View My Profile', 'User_Plugin_Menus', '{"route":"user_profile_self","icon":"application/modules/User/externals/images/links/profile.png"}', 'user_home', '', 1, 0, 4),
(54, 'user_home_edit', 'user', 'Edit My Profile', 'User_Plugin_Menus', '{"route":"user_extended","module":"user","controller":"edit","action":"profile","icon":"application/modules/User/externals/images/links/edit.png"}', 'user_home', '', 1, 0, 5),
(55, 'user_home_friends', 'user', 'Browse Members', '', '{"route":"user_general","controller":"index","action":"browse","icon":"application/modules/User/externals/images/links/search.png"}', 'user_home', '', 1, 0, 6),
(56, 'user_profile_edit', 'user', 'Edit Profile', 'User_Plugin_Menus', '', 'user_profile', '', 1, 0, 1),
(57, 'user_profile_friend', 'user', 'Friends', 'User_Plugin_Menus', '', 'user_profile', '', 1, 0, 2),
(58, 'user_profile_block', 'user', 'Block', 'User_Plugin_Menus', '', 'user_profile', '', 1, 0, 4),
(59, 'user_profile_report', 'user', 'Report User', 'User_Plugin_Menus', '', 'user_profile', '', 1, 0, 5),
(60, 'user_edit_profile', 'user', 'Personal Info', '', '{"route":"user_extended","module":"user","controller":"edit","action":"profile"}', 'user_edit', '', 1, 0, 1),
(61, 'user_edit_photo', 'user', 'Edit My Photo', '', '{"route":"user_extended","module":"user","controller":"edit","action":"photo"}', 'user_edit', '', 1, 0, 2),
(62, 'user_edit_style', 'user', 'Profile Style', '', '{"route":"user_extended","module":"user","controller":"edit","action":"style"}', 'user_edit', '', 1, 0, 3),
(63, 'user_settings_general', 'user', 'General', '', '{"route":"user_extended","module":"user","controller":"settings","action":"general"}', 'user_settings', '', 1, 0, 1),
(64, 'user_settings_privacy', 'user', 'Privacy', '', '{"route":"user_extended","module":"user","controller":"settings","action":"privacy"}', 'user_settings', '', 1, 0, 2),
(65, 'user_settings_notifications', 'user', 'Notifications', '', '{"route":"user_extended","module":"user","controller":"settings","action":"notifications"}', 'user_settings', '', 1, 0, 3),
(66, 'user_settings_password', 'user', 'Change Password', '', '{"route":"user_extended", "module":"user", "controller":"settings", "action":"password"}', 'user_settings', '', 1, 0, 4),
(67, 'user_settings_delete', 'user', 'Delete Account', 'User_Plugin_Menus::canDelete', '{"route":"user_extended", "module":"user", "controller":"settings", "action":"delete"}', 'user_settings', '', 1, 0, 5),
(68, 'core_admin_main_manage_members', 'user', 'Members', '', '{"route":"admin_default","module":"user","controller":"manage"}', 'core_admin_main_manage', '', 1, 0, 1),
(69, 'core_admin_main_signup', 'user', 'Signup Process', '', '{"route":"admin_default", "controller":"signup", "module":"user"}', 'core_admin_main_settings', '', 1, 0, 3),
(70, 'core_admin_main_facebook', 'user', 'Facebook Integration', '', '{"route":"admin_default", "action":"facebook", "controller":"settings", "module":"user"}', 'core_admin_main_settings', '', 1, 0, 4),
(71, 'core_admin_main_settings_friends', 'user', 'Friendship Settings', '', '{"route":"admin_default","module":"user","controller":"settings","action":"friends"}', 'core_admin_main_settings', '', 1, 0, 6),
(72, 'authorization_admin_level_user', 'user', 'Members', '', '{"route":"admin_default","module":"user","controller":"settings","action":"level"}', 'authorization_admin_level', '', 1, 0, 2),
(73, 'core_mini_messages', 'messages', 'Messages', 'Messages_Plugin_Menus', '', 'core_mini', '', 1, 0, 4),
(74, 'user_profile_message', 'messages', 'Send Message', 'Messages_Plugin_Menus', '', 'user_profile', '', 1, 0, 3),
(75, 'authorization_admin_level_messages', 'messages', 'Messages', '', '{"route":"admin_default","module":"messages","controller":"settings","action":"level"}', 'authorization_admin_level', '', 1, 0, 3),
(76, 'user_settings_network', 'network', 'Networks', '', '{"route":"user_extended", "module":"user", "controller":"settings", "action":"network"}', 'user_settings', '', 1, 0, 3),
(77, 'core_main_invite', 'invite', 'Invite', 'Invite_Plugin_Menus::canInvite', '{"route":"default","module":"invite"}', 'core_main', '', 0, 0, 2),
(78, 'user_home_invite', 'invite', 'Invite Your Friends', 'Invite_Plugin_Menus::canInvite', '{"route":"default","module":"invite","icon":"application/modules/Invite/externals/images/invite.png"}', 'user_home', '', 1, 0, 7),
(79, 'core_main_blog', 'blog', 'Blogs', '', '{"route":"blog_general"}', 'core_main', '', 1, 0, 4),
(80, 'core_sitemap_blog', 'blog', 'Blogs', '', '{"route":"blog_general"}', 'core_sitemap', '', 1, 0, 4),
(81, 'blog_main_browse', 'blog', 'Browse Entries', 'Blog_Plugin_Menus::canViewBlogs', '{"route":"blog_general"}', 'blog_main', '', 1, 0, 1),
(82, 'blog_main_manage', 'blog', 'My Entries', 'Blog_Plugin_Menus::canCreateBlogs', '{"route":"blog_general","action":"manage"}', 'blog_main', '', 1, 0, 2),
(83, 'blog_main_create', 'blog', 'Write New Entry', 'Blog_Plugin_Menus::canCreateBlogs', '{"route":"blog_general","action":"create"}', 'blog_main', '', 1, 0, 3),
(84, 'blog_quick_create', 'blog', 'Write New Entry', 'Blog_Plugin_Menus::canCreateBlogs', '{"route":"blog_general","action":"create","class":"buttonlink icon_blog_new"}', 'blog_quick', '', 1, 0, 1),
(85, 'blog_quick_style', 'blog', 'Edit Blog Style', 'Blog_Plugin_Menus', '{"route":"blog_general","action":"style","class":"smoothbox buttonlink icon_blog_style"}', 'blog_quick', '', 1, 0, 2),
(86, 'blog_gutter_list', 'blog', 'View All Entries', 'Blog_Plugin_Menus', '{"route":"blog_view","class":"buttonlink icon_blog_viewall"}', 'blog_gutter', '', 1, 0, 1),
(87, 'blog_gutter_create', 'blog', 'Write New Entry', 'Blog_Plugin_Menus', '{"route":"blog_general","action":"create","class":"buttonlink icon_blog_new"}', 'blog_gutter', '', 1, 0, 2),
(88, 'blog_gutter_edit', 'blog', 'Edit This Entry', 'Blog_Plugin_Menus', '{"route":"blog_specific","action":"edit","class":"buttonlink icon_blog_edit"}', 'blog_gutter', '', 1, 0, 3),
(89, 'blog_gutter_delete', 'blog', 'Delete This Entry', 'Blog_Plugin_Menus', '{"route":"blog_specific","action":"delete","class":"buttonlink icon_blog_delete"}', 'blog_gutter', '', 1, 0, 4),
(90, 'core_admin_main_plugins_blog', 'blog', 'Blogs', '', '{"route":"admin_default","module":"blog","controller":"settings"}', 'core_admin_main_plugins', '', 1, 0, 999),
(91, 'blog_admin_main_manage', 'blog', 'View Blogs', '', '{"route":"admin_default","module":"blog","controller":"manage"}', 'blog_admin_main', '', 1, 0, 1),
(92, 'blog_admin_main_settings', 'blog', 'Global Settings', '', '{"route":"admin_default","module":"blog","controller":"settings"}', 'blog_admin_main', '', 1, 0, 2),
(93, 'blog_admin_main_level', 'blog', 'Member Level Settings', '', '{"route":"admin_default","module":"blog","controller":"level"}', 'blog_admin_main', '', 1, 0, 3),
(94, 'blog_admin_main_categories', 'blog', 'Categories', '', '{"route":"admin_default","module":"blog","controller":"settings", "action":"categories"}', 'blog_admin_main', '', 1, 0, 4),
(95, 'authorization_admin_level_blog', 'blog', 'Blogs', '', '{"route":"admin_default","module":"blog","controller":"level","action":"index"}', 'authorization_admin_level', '', 1, 0, 999),
(97, 'custom_97', 'core', 'Мой шкаф', '', '{"uri":"cabinet","target":"","enabled":"1"}', 'user_home', '', 1, 1, 1),
(98, 'custom_98', 'core', 'Добавить покупку', '', '{"uri":"cabiten","target":"","enabled":"1"}', 'user_home', '', 1, 1, 2),
(100, 'core_main_group', 'group', 'Groups', '', '{"route":"group_general"}', 'core_main', '', 1, 0, 6),
(101, 'core_sitemap_group', 'group', 'Groups', '', '{"route":"group_general"}', 'core_sitemap', '', 1, 0, 6),
(102, 'group_main_browse', 'group', 'Browse Groups', '', '{"route":"group_general","action":"browse"}', 'group_main', '', 1, 0, 1),
(103, 'group_main_manage', 'group', 'My Groups', 'Group_Plugin_Menus', '{"route":"group_general","action":"manage"}', 'group_main', '', 1, 0, 2),
(104, 'group_main_create', 'group', 'Create New Group', 'Group_Plugin_Menus', '{"route":"group_general","action":"create"}', 'group_main', '', 1, 0, 3),
(105, 'group_profile_edit', 'group', 'Edit Profile', 'Group_Plugin_Menus', '', 'group_profile', '', 1, 0, 1),
(106, 'group_profile_style', 'group', 'Edit Styles', 'Group_Plugin_Menus', '', 'group_profile', '', 1, 0, 2),
(107, 'group_profile_member', 'group', 'Member', 'Group_Plugin_Menus', '', 'group_profile', '', 1, 0, 3),
(108, 'group_profile_report', 'group', 'Report Group', 'Group_Plugin_Menus', '', 'group_profile', '', 1, 0, 4),
(109, 'group_profile_share', 'group', 'Share', 'Group_Plugin_Menus', '', 'group_profile', '', 1, 0, 5),
(110, 'group_profile_invite', 'group', 'Invite', 'Group_Plugin_Menus', '', 'group_profile', '', 1, 0, 6),
(111, 'group_profile_message', 'group', 'Message Members', 'Group_Plugin_Menus', '', 'group_profile', '', 1, 0, 7),
(112, 'core_admin_main_plugins_group', 'group', 'Groups', '', '{"route":"admin_default","module":"group","controller":"manage"}', 'core_admin_main_plugins', '', 1, 0, 999),
(113, 'group_admin_main_manage', 'group', 'Manage Groups', '', '{"route":"admin_default","module":"group","controller":"manage"}', 'group_admin_main', '', 1, 0, 1),
(114, 'group_admin_main_level', 'group', 'Member Level Settings', '', '{"route":"admin_default","module":"group","controller":"level"}', 'group_admin_main', '', 1, 0, 2),
(115, 'group_admin_main_categories', 'group', 'Categories', '', '{"route":"admin_default","module":"group","controller":"settings","action":"categories"}', 'group_admin_main', '', 1, 0, 3),
(116, 'authorization_admin_level_group', 'group', 'Groups', '', '{"route":"admin_default","module":"group","controller":"settings","action":"level"}', 'authorization_admin_level', '', 1, 0, 999),
(117, 'core_main_event', 'event', 'Events', '', '{"route":"event_general"}', 'core_main', '', 1, 0, 6),
(118, 'core_sitemap_event', 'event', 'Events', '', '{"route":"event_general"}', 'core_sitemap', '', 1, 0, 6),
(119, 'event_main_upcoming', 'event', 'Upcoming Events', '', '{"route":"event_upcoming"}', 'event_main', '', 1, 0, 1),
(120, 'event_main_past', 'event', 'Past Events', '', '{"route":"event_past"}', 'event_main', '', 1, 0, 2),
(121, 'event_main_manage', 'event', 'My Events', 'Event_Plugin_Menus', '{"route":"event_general","action":"manage"}', 'event_main', '', 1, 0, 3),
(122, 'event_main_create', 'event', 'Create New Event', 'Event_Plugin_Menus', '{"route":"event_general","action":"create"}', 'event_main', '', 1, 0, 4),
(123, 'event_profile_edit', 'event', 'Edit Profile', 'Event_Plugin_Menus', '', 'event_profile', '', 1, 0, 1),
(124, 'event_profile_style', 'event', 'Edit Styles', 'Event_Plugin_Menus', '', 'event_profile', '', 1, 0, 2),
(125, 'event_profile_member', 'event', 'Member', 'Event_Plugin_Menus', '', 'event_profile', '', 1, 0, 3),
(126, 'event_profile_report', 'event', 'Report Event', 'Event_Plugin_Menus', '', 'event_profile', '', 1, 0, 4),
(127, 'event_profile_share', 'event', 'Share', 'Event_Plugin_Menus', '', 'event_profile', '', 1, 0, 5),
(128, 'event_profile_invite', 'event', 'Invite', 'Event_Plugin_Menus', '', 'event_profile', '', 1, 0, 6),
(129, 'event_profile_message', 'event', 'Message Members', 'Event_Plugin_Menus', '', 'event_profile', '', 1, 0, 7),
(130, 'core_admin_main_plugins_event', 'event', 'Events', '', '{"route":"admin_default","module":"event","controller":"manage"}', 'core_admin_main_plugins', '', 1, 0, 999),
(131, 'event_admin_main_manage', 'event', 'Manage Events', '', '{"route":"admin_default","module":"event","controller":"manage"}', 'event_admin_main', '', 1, 0, 1),
(132, 'event_admin_main_level', 'event', 'Member Level Settings', '', '{"route":"admin_default","module":"event","controller":"settings","action":"level"}', 'event_admin_main', '', 1, 0, 2),
(133, 'event_admin_main_categories', 'event', 'Categories', '', '{"route":"admin_default","module":"event","controller":"settings","action":"categories"}', 'event_admin_main', '', 1, 0, 3),
(134, 'authorization_admin_level_event', 'event', 'Events', '', '{"route":"admin_default","module":"event","controller":"level","action":"index"}', 'authorization_admin_level', '', 1, 0, 999),
(135, 'core_main_poll', 'poll', 'Polls', '', '{"route":"poll_browse"}', 'core_main', '', 1, 0, 5),
(136, 'core_sitemap_poll', 'poll', 'Polls', '', '{"route":"poll_browse"}', 'core_sitemap', '', 1, 0, 5),
(137, 'core_admin_main_plugins_poll', 'poll', 'Polls', '', '{"route":"admin_default","module":"poll","controller":"settings"}', 'core_admin_main_plugins', '', 1, 0, 999),
(138, 'poll_admin_main_manage', 'poll', 'Manage Polls', '', '{"route":"admin_default","module":"poll","controller":"manage"}', 'poll_admin_main', '', 1, 0, 1),
(139, 'poll_admin_main_settings', 'poll', 'Global Settings', '', '{"route":"admin_default","module":"poll","controller":"settings"}', 'poll_admin_main', '', 1, 0, 2),
(140, 'poll_admin_main_level', 'poll', 'Member Level Settings', '', '{"route":"admin_default","module":"poll","controller":"settings","action":"level"}', 'poll_admin_main', '', 1, 0, 3),
(141, 'authorization_admin_level_poll', 'poll', 'Polls', '', '{"route":"admin_default","module":"poll","controller":"settings","action":"level"}', 'authorization_admin_level', '', 1, 0, 999),
(142, 'core_main_video', 'video', 'Videos', '', '{"route":"video_general"}', 'core_main', '', 1, 0, 7),
(143, 'core_sitemap_video', 'video', 'Videos', '', '{"route":"video_general"}', 'core_sitemap', '', 1, 0, 7),
(144, 'core_admin_main_plugins_video', 'video', 'Videos', '', '{"route":"admin_default","module":"video","controller":"settings"}', 'core_admin_main_plugins', '', 1, 0, 999),
(145, 'video_main_browse', 'video', 'Browse Videos', '', '{"route":"video_general"}', 'video_main', '', 1, 0, 1),
(146, 'video_main_manage', 'video', 'My Videos', 'Video_Plugin_Menus', '{"route":"video_general","action":"manage"}', 'video_main', '', 1, 0, 2),
(147, 'video_main_create', 'video', 'Post New Video', 'Video_Plugin_Menus', '{"route":"video_general","action":"create"}', 'video_main', '', 1, 0, 3),
(148, 'video_admin_main_manage', 'video', 'Manage Videos', '', '{"route":"admin_default","module":"video","controller":"manage"}', 'video_admin_main', '', 1, 0, 1),
(149, 'video_admin_main_utility', 'video', 'Video Utilities', '', '{"route":"admin_default","module":"video","controller":"settings","action":"utility"}', 'video_admin_main', '', 1, 0, 2),
(150, 'video_admin_main_settings', 'video', 'Global Settings', '', '{"route":"admin_default","module":"video","controller":"settings"}', 'video_admin_main', '', 1, 0, 3),
(151, 'video_admin_main_level', 'video', 'Member Level Settings', '', '{"route":"admin_default","module":"video","controller":"settings","action":"level"}', 'video_admin_main', '', 1, 0, 4),
(152, 'video_admin_main_categories', 'video', 'Categories', '', '{"route":"admin_default","module":"video","controller":"settings","action":"categories"}', 'video_admin_main', '', 1, 0, 5),
(153, 'authorization_admin_level_video', 'video', 'Videos', '', '{"route":"admin_default","module":"video","controller":"settings","action":"level"}', 'authorization_admin_level', '', 1, 0, 999);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_menus`
--

DROP TABLE IF EXISTS `engine4_core_menus`;
CREATE TABLE IF NOT EXISTS `engine4_core_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `type` enum('standard','hidden','custom') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'standard',
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(3) NOT NULL DEFAULT '999',
  PRIMARY KEY (`id`),
  UNIQUE KEY `name` (`name`),
  KEY `order` (`order`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=17 ;

--
-- Dumping data for table `engine4_core_menus`
--

INSERT INTO `engine4_core_menus` (`id`, `name`, `type`, `title`, `order`) VALUES
(1, 'core_main', 'standard', 'Main Navigation Menu', 1),
(2, 'core_mini', 'standard', 'Mini Navigation Menu', 2),
(3, 'core_footer', 'standard', 'Footer Menu', 3),
(4, 'core_sitemap', 'standard', 'Sitemap', 4),
(5, 'user_home', 'standard', 'Member Home Quick Links Menu', 999),
(6, 'user_profile', 'standard', 'Member Profile Options Menu', 999),
(7, 'user_edit', 'standard', 'Member Edit Profile Navigation Menu', 999),
(8, 'user_settings', 'standard', 'Member Settings Navigation Menu', 999),
(9, 'blog_main', 'standard', 'Blog Main Navigation Menu', 999),
(10, 'blog_quick', 'standard', 'Blog Quick Navigation Menu', 999),
(11, 'blog_gutter', 'standard', 'Blog Gutter Navigation Menu', 999),
(12, 'group_main', 'standard', 'Group Main Navigation Menu', 999),
(13, 'group_profile', 'standard', 'Group Profile Options Menu', 999),
(14, 'event_main', 'standard', 'Event Main Navigation Menu', 999),
(15, 'event_profile', 'standard', 'Event Profile Options Menu', 999),
(16, 'video_main', 'standard', 'Video Main Navigation Menu', 999);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_modules`
--

DROP TABLE IF EXISTS `engine4_core_modules`;
CREATE TABLE IF NOT EXISTS `engine4_core_modules` (
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci,
  `version` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `enabled` tinyint(1) NOT NULL DEFAULT '0',
  `type` enum('core','standard','extra') CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'extra',
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_modules`
--

INSERT INTO `engine4_core_modules` (`name`, `title`, `description`, `version`, `enabled`, `type`) VALUES
('activity', 'Activity', 'Activity', '4.0.4', 1, 'core'),
('announcement', 'Announcements', 'Announcements', '4.0.2', 1, 'standard'),
('authorization', 'Authorization', 'Authorization', '4.0.4', 1, 'core'),
('blog', 'Blogs', 'Blogs', '4.0.4', 1, 'extra'),
('cabinet', 'cabinet', 'cabinet - это модуль шкафа в который и будут пропадать все покупки.', '4.0.0', 1, 'extra'),
('core', 'Core', 'The Alpha and the Omega.', '4.0.4', 1, 'core'),
('event', 'Events', 'Events', '4.0.4', 1, 'extra'),
('fields', 'Fields', 'Fields', '4.0.4', 1, 'core'),
('group', 'Groups', 'Groups', '4.0.4', 1, 'extra'),
('invite', 'Invites', 'Invites', '4.0.2', 1, 'standard'),
('messages', 'Messages', 'Messages', '4.0.4', 1, 'standard'),
('network', 'Networks', 'Networks', '4.0.4', 1, 'standard'),
('poll', 'Polls', 'Polls', '4.0.4', 1, 'extra'),
('storage', 'Storage', 'Storage', '4.0.3', 1, 'core'),
('user', 'Users', 'Users', '4.0.4', 1, 'core'),
('video', 'Videos', 'Videos', '4.0.4', 1, 'extra');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_pages`
--

DROP TABLE IF EXISTS `engine4_core_pages`;
CREATE TABLE IF NOT EXISTS `engine4_core_pages` (
  `page_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `displayname` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `url` varchar(128) COLLATE utf8_unicode_ci DEFAULT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `keywords` text COLLATE utf8_unicode_ci NOT NULL,
  `custom` tinyint(1) NOT NULL DEFAULT '1',
  `fragment` tinyint(1) NOT NULL DEFAULT '0',
  `layout` varchar(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`page_id`),
  UNIQUE KEY `name` (`name`),
  UNIQUE KEY `url` (`url`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=9 ;

--
-- Dumping data for table `engine4_core_pages`
--

INSERT INTO `engine4_core_pages` (`page_id`, `name`, `displayname`, `url`, `title`, `description`, `keywords`, `custom`, `fragment`, `layout`, `view_count`) VALUES
(1, 'header', 'Site Header', NULL, '', '', '', 0, 1, '', 0),
(2, 'footer', 'Site Footer', NULL, '', '', '', 0, 1, '', 0),
(3, 'core_index_index', 'Home Page', NULL, 'Home Page', 'This is the home page.', '', 0, 0, '', 0),
(4, 'user_index_home', 'Member Home Page', NULL, 'Member Home Page', 'This is the home page for members.', '', 0, 0, '', 0),
(5, 'user_profile_index', 'Member Profile', NULL, 'Member Profile', 'This is a member''s profile.', '', 0, 0, '', 0),
(6, NULL, 'Login Page', NULL, '', '', '', 1, 0, '', 0),
(7, 'group_profile_index', 'Group Profile', NULL, 'Group Profile', 'This is the profile for an group.', '', 0, 0, '', 0),
(8, 'event_profile_index', 'Event Profile', NULL, 'Event Profile', 'This is the profile for an event.', '', 0, 0, '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_referrers`
--

DROP TABLE IF EXISTS `engine4_core_referrers`;
CREATE TABLE IF NOT EXISTS `engine4_core_referrers` (
  `host` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `path` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `query` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `value` int(11) unsigned NOT NULL,
  PRIMARY KEY (`host`,`path`,`query`),
  KEY `value` (`value`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_referrers`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_reports`
--

DROP TABLE IF EXISTS `engine4_core_reports`;
CREATE TABLE IF NOT EXISTS `engine4_core_reports` (
  `report_id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `category` varchar(16) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `subject_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `subject_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `read` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`report_id`),
  KEY `category` (`category`),
  KEY `user_id` (`user_id`),
  KEY `read` (`read`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_reports`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_routes`
--

DROP TABLE IF EXISTS `engine4_core_routes`;
CREATE TABLE IF NOT EXISTS `engine4_core_routes` (
  `name` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `config` text COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '1',
  PRIMARY KEY (`name`),
  KEY `order` (`order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_routes`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_search`
--

DROP TABLE IF EXISTS `engine4_core_search`;
CREATE TABLE IF NOT EXISTS `engine4_core_search` (
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `id` int(11) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `keywords` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `hidden` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`type`,`id`),
  FULLTEXT KEY `LOOKUP` (`title`,`description`,`keywords`,`hidden`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_search`
--

INSERT INTO `engine4_core_search` (`type`, `id`, `title`, `description`, `keywords`, `hidden`) VALUES
('authorization_level', 4, 'Default Level', 'This is the default user level.  New users are assigned to it automatically.', '', ''),
('authorization_level', 5, 'Public', 'Settings for this level apply to users who have not logged in.', '', ''),
('user', 1, 'Дима Овчаренко', '', '', ''),
('user', 2, 'Test Testoviy', '', '', ''),
('user', 3, 'Сергей Овчаренко', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_session`
--

DROP TABLE IF EXISTS `engine4_core_session`;
CREATE TABLE IF NOT EXISTS `engine4_core_session` (
  `id` char(32) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `modified` int(11) DEFAULT NULL,
  `lifetime` int(11) DEFAULT NULL,
  `data` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_session`
--

INSERT INTO `engine4_core_session` (`id`, `modified`, `lifetime`, `data`) VALUES
('0542k2lgqtcbuj7m0hs05bi8j6', 1305905513, 86400, ''),
('09hq01pj8hu28osdesvid9l874', 1305901001, 86400, ''),
('09v1jri5018qt23o2dndfd7s04', 1305905163, 86400, ''),
('11pggllu5t5q7njueqae7nsol1', 1305905309, 86400, ''),
('120enaviolvunll1t2ouml91j2', 1305898676, 86400, ''),
('1m1gf27k4vqh6p38nvuu15oka5', 1305898737, 86400, ''),
('1mtvve5klbsgc3cudu47b6euc6', 1305899285, 86400, ''),
('2bubecc95uiqkjrgjtkc5rful7', 1305903124, 86400, ''),
('35o8t3nn7a53fs0i0nar21bsl0', 1305906639, 86400, ''),
('3mo8hs4k6etglcai0di34kk020', 1305898092, 86400, ''),
('3q5073ok7uh4mq8dkmi26kcac0', 1305905993, 86400, ''),
('3u5jivv1j998n5ib6von2bpc71', 1305565039, 86400, ''),
('4a9bq98sd79v7kfklj068mhcu1', 1305904651, 86400, ''),
('4e1lo50uup53ko1q565j7pbr10', 1305901843, 86400, ''),
('4if9foula4hr07l728nfjb6hd2', 1305897735, 86400, ''),
('4jbav56jnoef12ld44fh4ceep7', 1305905442, 86400, ''),
('4leh1697keaj6bmlph8ec21943', 1305902083, 86400, ''),
('503ocr3dvp8sc35mtjvem82i36', 1305899849, 86400, ''),
('59o8v0spd2l4d4f6eec0fcfvn5', 1305900196, 86400, ''),
('5a2eh480kmgfn39o401qenk4p3', 1305897083, 86400, ''),
('5a2f3sdukolhe5uiu7ifvs43s0', 1305905873, 86400, ''),
('5rg3a6pjo0ddgd4hclrhc869n7', 1305897310, 86400, ''),
('6brr8db43or0ke721sq6h5ooa7', 1305898469, 86400, ''),
('6pmo98es4jrhbll63f4ighbu74', 1305898154, 86400, ''),
('79jq8fpd1hmulj3bb6817imub6', 1305898409, 86400, ''),
('7lg42qk20pb164esdr82p3ej21', 1305899035, 86400, ''),
('8a9sivjq48epa06emirkcsi0j6', 1305565282, 86400, ''),
('8lohl0esp7qct213togubasea6', 1305907453, 86400, ''),
('98fbcgd4a0scc5klkv58dbamn6', 1305900403, 86400, ''),
('9c19qpd38g7bsvqpv2uokqom05', 1305901963, 86400, ''),
('9fn4qsdr4g767aotc9dss6vpe0', 1305907567, 86400, ''),
('9hpcagobmqmrtvqolt4i43nm74', 1305905091, 86400, ''),
('9o39vqo8a75cmi52c58dm1rbp4', 1305565403, 86400, ''),
('a5j0f3a7b992rn29toms3nsbn0', 1305898280, 86400, ''),
('a9tq3ugpoegl4fja4oc6uosma6', 1305894323, 86400, ''),
('ah61nch5vek7ue0dimptc7shd3', 1305900842, 86400, ''),
('b1eaddlm6fqkl43q3dvb5p7564', 1305899658, 86400, ''),
('b5ah6kjke98vbqr4ivj5ccjt91', 1305904927, 86400, ''),
('b78l2l86m0ropf9bp8hetoghl2', 1305906792, 86400, ''),
('b8j6q94kunq2hedabog6r8edk0', 1305564662, 86400, ''),
('be5otb2coijndfpndo21b123d5', 1305901603, 86400, ''),
('bka2ru3hst39bg12v30m6n7dr2', 1305903750, 86400, ''),
('bm1jvprr172b6nco1kgdpmamc6', 1305899969, 86400, ''),
('buiouids366lp2p5rjj2lqrom2', 1305899112, 86400, ''),
('cberi2q8b5b4mqk1rcofn16c30', 1305907687, 86400, ''),
('cmhp4pan5job8rlk69frlls2g6', 1305898600, 86400, ''),
('cqd6e6pjlrctsgg6nvklvh34j2', 1305905753, 86400, ''),
('crfjmf0glkce6a2vndau772ui3', 1305907086, 86400, ''),
('deivgq62na7q0udaimgb894pf7', 1305565527, 86400, ''),
('dl78gprfls2sfb0ln8g1h8rvj5', 1305896843, 86400, ''),
('e0o1lt5hnmr8atupq5drvnf540', 1305900473, 86400, ''),
('e4dk7rqfgkgnvei4f5jg641n51', 1305903901, 86400, ''),
('ecq8e6mjq7pqpnc53vm3bihqj2', 1305907207, 86400, ''),
('enhivci97rumvmgm79j8ia25b0', 1305905382, 86400, ''),
('f2ll4tqidgiatnfsc7o20fbrs7', 1305899599, 86400, ''),
('fdt2ku3snh2mr49nimjc69bo46', 1305904080, 86400, ''),
('g3n6e3ubm0ofnkb7bua1lbkem0', 1305904996, 86400, ''),
('g88llddd5hbburh7tbbp70eg56', 1305897877, 86400, ''),
('g8m6h9426sms1o6g7h15qpr8a6', 1305898851, 86400, ''),
('gt1bh124kvhdpqavbmpin2kvm4', 1305900602, 86400, ''),
('h1sln3kr4huea9keirvth77nf5', 1305905633, 86400, ''),
('h2nn7j1fdtj342571r8dsber73', 1305904021, 86400, ''),
('hc2huer7ebav42b153ok6i1uq4', 1305903661, 86400, ''),
('hfnqkmp91401e9hjstk5gm9ou3', 1305907688, 86400, 'User_AuthController|a:1:{s:4:"data";N;}Signup_Confirm|a:2:{s:8:"approved";i:1;s:8:"verified";i:0;}__ZF|a:1:{s:33:"Zend_Form_Element_Hash_salt_token";a:1:{s:4:"ENNH";i:1;}}Zend_Auth|a:1:{s:7:"storage";s:27:"shopaholiccompany@gmail.com";}'),
('hk8rv6a50sccdm03mdmajb8lm5', 1305907327, 86400, ''),
('i246qt62t841mpj8vh7rc9dc35', 1305565644, 86400, ''),
('i5mff3suat5q4u1bj1d0rcnvv0', 1305901443, 86400, ''),
('j0a25bnrnpf4lvol7cr3brbit3', 1305903418, 86400, ''),
('jc71nm2iq5quqj6nc0qt5g7s70', 1305900902, 86400, ''),
('jpk7bg09eh28dr4h6eoqq9dpb2', 1305900089, 86400, ''),
('jr5q7ebp3ine9645e6kavvdoo1', 1305907022, 86400, ''),
('kkiu3au1ubls1khqrm201vote3', 1305897187, 86400, ''),
('kr9g0fkj6pu3jur2dii80kvv56', 1305896963, 86400, ''),
('kvn7t1mjjd0ldvss1hqpirl8v4', 1305564919, 86400, ''),
('lu2kvbfcch0itm79copok4o2f4', 1305565168, 86400, ''),
('m4rrcdinbk0ttmtuq2v6f5uep7', 1305897462, 86400, ''),
('mg66oms8ddaqqhis3o74hb5d32', 1305906113, 86400, ''),
('mgonntki6s33hv5dhui8rvlfh1', 1305903541, 86400, ''),
('mn3169l6kivsj1pnc8jlu7ehi3', 1305899718, 86400, ''),
('mot7uus5v3qf0bdvckqi6luj90', 1305565641, 86400, 'Zend_Auth|a:1:{s:7:"storage";s:27:"shopaholiccompany@gmail.com";}'),
('n1nfq2f7fqe88m4ktllenebvr7', 1305898219, 86400, ''),
('n274kktmffvh76tann011pt0r6', 1305906853, 86400, ''),
('n5e68b6546cb6qdccn9ck2b1b1', 1305900722, 86400, ''),
('nbrd4uam6v1v40sl0vakrrgga3', 1305897615, 86400, ''),
('nnso35td7i2a44ohbq3vir9m81', 1305904836, 86400, ''),
('oktua4h06coa30j0fvt2qtdf06', 1305905226, 86400, ''),
('optsa8tj0tedrbgh6mk0hk6760', 1305900331, 86400, ''),
('oqfmcs4mg2l45t1oi826v1d7l4', 1305904427, 86400, ''),
('p4vo0j6klusj0r4o0smrd8vet1', 1305564801, 86400, ''),
('pg7m0j04ovt0aibqehgesg87q6', 1305904210, 86400, ''),
('pqd4rtckm6erp9cb5mh98no575', 1305907393, 86400, ''),
('qjv6ifda95o68056d0klq95aq5', 1305901723, 86400, ''),
('qkovs9u0ld5e11psldtgfhejt2', 1305898914, 86400, ''),
('r0ngso2cgv7qbq2c25vjif0ui4', 1305902985, 86400, ''),
('r4bk7bpsfgrbqvptkl352s3qo2', 1305904288, 86400, ''),
('rql97v366bp0fgbu9pqld0b1s1', 1305897251, 86400, ''),
('t2akhveoijqs3cf68ubncffit3', 1305897992, 86400, ''),
('ta85ki1tkiaucjmu038t700en7', 1305899531, 86400, ''),
('tg4n761pvh43rraevk3bf0u0q0', 1305903045, 86400, ''),
('uo1vkrkjd3ug77ttcdnalnmcr7', 1305899215, 86400, ''),
('uvpsffbpv5rpcgegq8dupg3hl7', 1305904350, 86400, ''),
('vkjstrqn4qvb17htodicn2ucc4', 1305904764, 86400, '');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_settings`
--

DROP TABLE IF EXISTS `engine4_core_settings`;
CREATE TABLE IF NOT EXISTS `engine4_core_settings` (
  `name` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `value` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`name`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_settings`
--

INSERT INTO `engine4_core_settings` (`name`, `value`) VALUES
('activity.content', 'everyone'),
('activity.disallowed', 'N'),
('activity.filter', '1'),
('activity.length', '10'),
('activity.liveupdate', '120000'),
('activity.notifications.template', 'Hello %title%,\n\n%body%\n\n--Site Admin'),
('activity.publish', '1'),
('activity.userdelete', '1'),
('activity.userlength', '10'),
('authorization.defaultlevel', '4'),
('core.admin.mode', 'none'),
('core.admin.password', ''),
('core.admin.reauthenticate', '0'),
('core.admin.timeout', '600'),
('core.comet.delay', '1000'),
('core.comet.enabled', '1'),
('core.comet.mode', 'short'),
('core.comet.reconnect', '2000'),
('core.doctype', 'XHTML1_STRICT'),
('core.facebook.enable', 'none'),
('core.facebook.key', ''),
('core.facebook.secret', ''),
('core.general.browse', '0'),
('core.general.commenthtml', ''),
('core.general.notificationupdate', '120000'),
('core.general.portal', '0'),
('core.general.profile', '0'),
('core.general.quota', '0'),
('core.general.search', '0'),
('core.general.site.description', ''),
('core.general.site.keywords', ''),
('core.general.site.title', 'Shopaholic'),
('core.license.email', 'email@domain.com'),
('core.license.key', '4461-8376-6748-1344'),
('core.license.statistics', '1'),
('core.locale.locale', 'ru_RU'),
('core.locale.timezone', 'Europe/Athens'),
('core.mail.count', '25'),
('core.mail.enabled', '1'),
('core.mail.from', 'email@domain.com'),
('core.mail.name', 'Site Admin'),
('core.mail.queueing', '1'),
('core.secret', 'ac30854d6d211cdb2a458f7209543bb584e9b77b'),
('core.site.creation', '2011-05-16 16:48:41'),
('core.site.title', 'Social Network'),
('core.spam.censor', ''),
('core.spam.comment', '0'),
('core.spam.contact', '0'),
('core.spam.invite', '0'),
('core.spam.ipbans', ''),
('core.spam.login', '0'),
('core.spam.signup', '0'),
('core.tasks.interval', '60'),
('core.tasks.key', '01e33d86'),
('core.tasks.last', '1305907687'),
('core.tasks.mode', 'socket'),
('core.tasks.pid', ''),
('core.tasks.timeout', '900'),
('core.thumbnails.icon.height', '48'),
('core.thumbnails.icon.mode', 'crop'),
('core.thumbnails.icon.width', '48'),
('core.thumbnails.main.height', '720'),
('core.thumbnails.main.mode', 'resize'),
('core.thumbnails.main.width', '720'),
('core.thumbnails.normal.height', '160'),
('core.thumbnails.normal.mode', 'resize'),
('core.thumbnails.normal.width', '140'),
('core.thumbnails.profile.height', '400'),
('core.thumbnails.profile.mode', 'resize'),
('core.thumbnails.profile.width', '200'),
('invite.allowCustomMessage', '1'),
('invite.fromEmail', ''),
('invite.fromName', ''),
('invite.max', '10'),
('invite.message', 'You are being invited to join our social network.'),
('invite.subject', 'Join Us'),
('polls.canChangeVote', '1'),
('polls.maxOptions', '15'),
('polls.showPieChart', '0'),
('user.friends.direction', '1'),
('user.friends.eligible', '2'),
('user.friends.lists', '1'),
('user.friends.verification', '1'),
('user.signup.approve', '1'),
('user.signup.checkemail', '1'),
('user.signup.inviteonly', '0'),
('user.signup.random', '0'),
('user.signup.terms', '1'),
('user.signup.verifyemail', '1'),
('video.ffmpeg.path', '/usr/local/bin/ffmpeg'),
('video.jobs', '2');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_statistics`
--

DROP TABLE IF EXISTS `engine4_core_statistics`;
CREATE TABLE IF NOT EXISTS `engine4_core_statistics` (
  `type` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `date` datetime NOT NULL,
  `value` int(11) NOT NULL DEFAULT '0',
  PRIMARY KEY (`type`,`date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_statistics`
--

INSERT INTO `engine4_core_statistics` (`type`, `date`, `value`) VALUES
('core.emails', '2011-05-20 00:00:00', 6),
('core.views', '2011-05-16 00:00:00', 27),
('core.views', '2011-05-20 00:00:00', 815),
('messages.creations', '2011-05-20 00:00:00', 1),
('user.creations', '2011-05-20 00:00:00', 2),
('user.friendships', '2011-05-20 00:00:00', 1),
('user.logins', '2011-05-16 00:00:00', 1),
('user.logins', '2011-05-20 00:00:00', 8);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_status`
--

DROP TABLE IF EXISTS `engine4_core_status`;
CREATE TABLE IF NOT EXISTS `engine4_core_status` (
  `status_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `body` text NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`status_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=2 ;

--
-- Dumping data for table `engine4_core_status`
--

INSERT INTO `engine4_core_status` (`status_id`, `resource_type`, `resource_id`, `body`, `creation_date`) VALUES
(1, 'user', 1, '<a href="http://www.last.fm/" target="_blank" rel="nofollow">http://www.last.fm/</a>', '2011-05-20 15:11:27');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_styles`
--

DROP TABLE IF EXISTS `engine4_core_styles`;
CREATE TABLE IF NOT EXISTS `engine4_core_styles` (
  `type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `id` int(11) unsigned NOT NULL,
  `style` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`type`,`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_core_styles`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_tagmaps`
--

DROP TABLE IF EXISTS `engine4_core_tagmaps`;
CREATE TABLE IF NOT EXISTS `engine4_core_tagmaps` (
  `tagmap_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `resource_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `resource_id` int(11) unsigned NOT NULL,
  `tagger_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `tagger_id` int(11) unsigned NOT NULL,
  `tag_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `tag_id` int(11) unsigned NOT NULL,
  `extra` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`tagmap_id`),
  KEY `resource_type` (`resource_type`,`resource_id`),
  KEY `tagger_type` (`tagger_type`,`tagger_id`),
  KEY `tag_type` (`tag_type`,`tag_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_tagmaps`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_tags`
--

DROP TABLE IF EXISTS `engine4_core_tags`;
CREATE TABLE IF NOT EXISTS `engine4_core_tags` (
  `tag_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `text` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`tag_id`),
  UNIQUE KEY `text` (`text`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_core_tags`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_tasks`
--

DROP TABLE IF EXISTS `engine4_core_tasks`;
CREATE TABLE IF NOT EXISTS `engine4_core_tasks` (
  `task_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `category` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'general',
  `module` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `system` tinyint(1) NOT NULL DEFAULT '0',
  `plugin` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `timeout` int(11) unsigned NOT NULL,
  `type` enum('disabled','manual','automatic','semi-automatic') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'automatic',
  `state` enum('dormant','active','sleeping','ready') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'dormant',
  `data` text COLLATE utf8_unicode_ci,
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `executing` tinyint(1) NOT NULL DEFAULT '0',
  `executing_id` int(11) unsigned NOT NULL DEFAULT '0',
  `started_last` int(11) NOT NULL DEFAULT '0',
  `started_count` int(11) unsigned NOT NULL DEFAULT '0',
  `completed_last` int(11) NOT NULL DEFAULT '0',
  `completed_count` int(11) unsigned NOT NULL DEFAULT '0',
  `failure_last` int(11) NOT NULL DEFAULT '0',
  `failure_count` int(11) unsigned NOT NULL DEFAULT '0',
  `success_last` int(11) NOT NULL DEFAULT '0',
  `success_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`task_id`),
  UNIQUE KEY `plugin` (`plugin`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=13 ;

--
-- Dumping data for table `engine4_core_tasks`
--

INSERT INTO `engine4_core_tasks` (`task_id`, `title`, `category`, `module`, `system`, `plugin`, `timeout`, `type`, `state`, `data`, `enabled`, `executing`, `executing_id`, `started_last`, `started_count`, `completed_last`, `completed_count`, `failure_last`, `failure_count`, `success_last`, `success_count`) VALUES
(1, 'Background Mailer', 'system', 'core', 1, 'Core_Plugin_Task_Mail', 60, 'automatic', 'dormant', NULL, 1, 0, 0, 1305907687, 49, 1305907687, 49, 0, 0, 1305907687, 49),
(2, 'Statistics', 'system', 'core', 1, 'Core_Plugin_Task_Statistics', 43200, 'automatic', 'dormant', NULL, 1, 0, 0, 1305896843, 2, 1305896843, 2, 1305896843, 1, 1305564801, 1),
(3, 'Rebuild Activity Privacy', 'rebuild_privacy', 'activity', 0, 'Activity_Plugin_Task_Maintenance_RebuildPrivacy', 0, 'semi-automatic', 'dormant', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(4, 'Member Data Maintenance', 'system', 'user', 1, 'User_Plugin_Task_Cleanup', 60, 'automatic', 'dormant', NULL, 1, 0, 0, 1305907687, 49, 1305907687, 49, 0, 0, 1305907687, 49),
(5, 'Rebuild Member Privacy', 'rebuild_privacy', 'user', 0, 'User_Plugin_Task_Maintenance_RebuildPrivacy', 0, 'semi-automatic', 'dormant', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(6, 'Rebuild Network Membership', 'maintenance', 'network', 0, 'Network_Plugin_Task_Maintenance_RebuildMembership', 0, 'semi-automatic', 'dormant', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(7, 'Rebuild Blog Privacy', 'rebuild_privacy', 'blog', 0, 'Blog_Plugin_Task_Maintenance_RebuildPrivacy', 0, 'semi-automatic', 'dormant', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(8, 'Rebuild Group Privacy', 'rebuild_privacy', 'group', 0, 'Group_Plugin_Task_Maintenance_RebuildPrivacy', 0, 'semi-automatic', 'dormant', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(9, 'Rebuild Event Privacy', 'rebuild_privacy', 'event', 0, 'Event_Plugin_Task_Maintenance_RebuildPrivacy', 0, 'semi-automatic', 'dormant', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(10, 'Rebuild Poll Privacy', 'rebuild_privacy', 'poll', 0, 'Poll_Plugin_Task_Maintenance_RebuildPrivacy', 0, 'semi-automatic', 'dormant', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0),
(11, 'Background Video Encoder', 'system', 'video', 1, 'Video_Plugin_Task_Encode', 300, 'automatic', 'dormant', NULL, 1, 0, 0, 1305907453, 1, 1305907453, 1, 0, 0, 1305907453, 1),
(12, 'Rebuild Video Privacy', 'rebuild_privacy', 'video', 0, 'Video_Plugin_Task_Maintenance_RebuildPrivacy', 0, 'semi-automatic', 'dormant', NULL, 1, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_core_themes`
--

DROP TABLE IF EXISTS `engine4_core_themes`;
CREATE TABLE IF NOT EXISTS `engine4_core_themes` (
  `theme_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `title` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `active` tinyint(4) NOT NULL DEFAULT '0',
  PRIMARY KEY (`theme_id`),
  UNIQUE KEY `name` (`name`),
  KEY `active` (`active`)
) ENGINE=InnoDB  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `engine4_core_themes`
--

INSERT INTO `engine4_core_themes` (`theme_id`, `name`, `title`, `description`, `active`) VALUES
(1, 'default', 'Default Theme', '', 0),
(2, 'midnight', 'Midnight', '', 0),
(3, 'bamboo', 'Bamboo Theme', '', 0),
(4, 'snowbot', 'Snowbot Theme', '', 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_event_albums`
--

DROP TABLE IF EXISTS `engine4_event_albums`;
CREATE TABLE IF NOT EXISTS `engine4_event_albums` (
  `album_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `photo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `collectible_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`album_id`),
  KEY `event_id` (`event_id`),
  KEY `search` (`search`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_event_albums`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_event_categories`
--

DROP TABLE IF EXISTS `engine4_event_categories`;
CREATE TABLE IF NOT EXISTS `engine4_event_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=30 ;

--
-- Dumping data for table `engine4_event_categories`
--

INSERT INTO `engine4_event_categories` (`category_id`, `title`) VALUES
(1, 'Arts'),
(2, 'Business'),
(3, 'Conferences'),
(4, 'Festivals'),
(5, 'Food'),
(6, 'Fundraisers'),
(7, 'Galleries'),
(8, 'Health'),
(9, 'Just For Fun'),
(10, 'Kids'),
(11, 'Learning'),
(12, 'Literary'),
(13, 'Movies'),
(14, 'Museums'),
(15, 'Neighborhood'),
(16, 'Networking'),
(17, 'Nightlife'),
(18, 'On Campus'),
(19, 'Organizations'),
(20, 'Outdoors'),
(21, 'Pets'),
(22, 'Politics'),
(23, 'Sales'),
(24, 'Science'),
(25, 'Spirituality'),
(26, 'Sports'),
(27, 'Technology'),
(28, 'Theatre'),
(29, 'Other');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_event_events`
--

DROP TABLE IF EXISTS `engine4_event_events`;
CREATE TABLE IF NOT EXISTS `engine4_event_events` (
  `event_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(512) COLLATE utf8_unicode_ci NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `parent_type` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `parent_id` int(11) unsigned NOT NULL,
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `starttime` datetime NOT NULL,
  `endtime` datetime NOT NULL,
  `host` varchar(115) COLLATE utf8_unicode_ci NOT NULL,
  `location` varchar(115) COLLATE utf8_unicode_ci NOT NULL,
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `member_count` int(11) unsigned NOT NULL DEFAULT '0',
  `approval` tinyint(1) NOT NULL DEFAULT '0',
  `invite` tinyint(1) NOT NULL DEFAULT '0',
  `photo_id` int(11) unsigned NOT NULL,
  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`event_id`),
  KEY `user_id` (`user_id`),
  KEY `parent_type` (`parent_type`,`parent_id`),
  KEY `starttime` (`starttime`),
  KEY `search` (`search`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_event_events`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_event_membership`
--

DROP TABLE IF EXISTS `engine4_event_membership`;
CREATE TABLE IF NOT EXISTS `engine4_event_membership` (
  `resource_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `resource_approved` tinyint(1) NOT NULL DEFAULT '0',
  `user_approved` tinyint(1) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_unicode_ci,
  `rsvp` tinyint(3) NOT NULL DEFAULT '3',
  `title` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`resource_id`,`user_id`),
  KEY `REVERSE` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_event_membership`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_event_photos`
--

DROP TABLE IF EXISTS `engine4_event_photos`;
CREATE TABLE IF NOT EXISTS `engine4_event_photos` (
  `photo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(11) unsigned NOT NULL,
  `event_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `collection_id` int(11) unsigned NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `album_id` (`album_id`),
  KEY `event_id` (`event_id`),
  KEY `collection_id` (`collection_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_event_photos`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_event_posts`
--

DROP TABLE IF EXISTS `engine4_event_posts`;
CREATE TABLE IF NOT EXISTS `engine4_event_posts` (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `event_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `topic_id` (`topic_id`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_event_posts`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_event_topics`
--

DROP TABLE IF EXISTS `engine4_event_topics`;
CREATE TABLE IF NOT EXISTS `engine4_event_topics` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `event_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `post_count` int(11) unsigned NOT NULL DEFAULT '0',
  `lastpost_id` int(11) unsigned NOT NULL,
  `lastposter_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`topic_id`),
  KEY `event_id` (`event_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_event_topics`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_albums`
--

DROP TABLE IF EXISTS `engine4_group_albums`;
CREATE TABLE IF NOT EXISTS `engine4_group_albums` (
  `album_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `photo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `collectible_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`album_id`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_group_albums`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_categories`
--

DROP TABLE IF EXISTS `engine4_group_categories`;
CREATE TABLE IF NOT EXISTS `engine4_group_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=18 ;

--
-- Dumping data for table `engine4_group_categories`
--

INSERT INTO `engine4_group_categories` (`category_id`, `title`) VALUES
(1, 'Animals'),
(2, 'Business & Finance'),
(3, 'Computers & Internet'),
(4, 'Cultures & Community'),
(5, 'Dating & Relationships'),
(6, 'Entertainment & Arts'),
(7, 'Family & Home'),
(8, 'Games'),
(9, 'Government & Politics'),
(10, 'Health & Wellness'),
(11, 'Hobbies & Crafts'),
(12, 'Music'),
(13, 'Recreation & Sports'),
(14, 'Regional'),
(15, 'Religion & Beliefs'),
(16, 'Schools & Education'),
(17, 'Science');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_groups`
--

DROP TABLE IF EXISTS `engine4_group_groups`;
CREATE TABLE IF NOT EXISTS `engine4_group_groups` (
  `group_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `invite` tinyint(1) NOT NULL DEFAULT '1',
  `approval` tinyint(1) NOT NULL DEFAULT '0',
  `photo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `member_count` smallint(6) unsigned NOT NULL,
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`group_id`),
  KEY `user_id` (`user_id`),
  KEY `search` (`search`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_group_groups`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_listitems`
--

DROP TABLE IF EXISTS `engine4_group_listitems`;
CREATE TABLE IF NOT EXISTS `engine4_group_listitems` (
  `listitem_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_id` int(11) unsigned NOT NULL,
  `child_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`listitem_id`),
  KEY `list_id` (`list_id`),
  KEY `child_id` (`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_group_listitems`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_lists`
--

DROP TABLE IF EXISTS `engine4_group_lists`;
CREATE TABLE IF NOT EXISTS `engine4_group_lists` (
  `list_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `owner_id` int(11) unsigned NOT NULL,
  `child_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`list_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_group_lists`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_membership`
--

DROP TABLE IF EXISTS `engine4_group_membership`;
CREATE TABLE IF NOT EXISTS `engine4_group_membership` (
  `resource_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `resource_approved` tinyint(1) NOT NULL DEFAULT '0',
  `user_approved` tinyint(1) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_unicode_ci,
  `title` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`resource_id`,`user_id`),
  KEY `REVERSE` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_group_membership`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_photos`
--

DROP TABLE IF EXISTS `engine4_group_photos`;
CREATE TABLE IF NOT EXISTS `engine4_group_photos` (
  `photo_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `album_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `collection_id` int(11) unsigned NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`photo_id`),
  KEY `album_id` (`album_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_group_photos`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_posts`
--

DROP TABLE IF EXISTS `engine4_group_posts`;
CREATE TABLE IF NOT EXISTS `engine4_group_posts` (
  `post_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `topic_id` int(11) unsigned NOT NULL,
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`post_id`),
  KEY `topic_id` (`topic_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_group_posts`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_group_topics`
--

DROP TABLE IF EXISTS `engine4_group_topics`;
CREATE TABLE IF NOT EXISTS `engine4_group_topics` (
  `topic_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `group_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `sticky` tinyint(1) NOT NULL DEFAULT '0',
  `closed` tinyint(1) NOT NULL DEFAULT '0',
  `post_count` int(11) unsigned NOT NULL DEFAULT '0',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `lastpost_id` int(11) unsigned NOT NULL DEFAULT '0',
  `lastposter_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`topic_id`),
  KEY `group_id` (`group_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_group_topics`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_invites`
--

DROP TABLE IF EXISTS `engine4_invites`;
CREATE TABLE IF NOT EXISTS `engine4_invites` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `recipient` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `code` varchar(255) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `timestamp` datetime NOT NULL,
  `message` text COLLATE utf8_unicode_ci NOT NULL,
  `new_user_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `code` (`code`),
  KEY `user_id` (`user_id`),
  KEY `recipient` (`recipient`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_invites`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_messages_conversations`
--

DROP TABLE IF EXISTS `engine4_messages_conversations`;
CREATE TABLE IF NOT EXISTS `engine4_messages_conversations` (
  `conversation_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `user_id` int(11) unsigned NOT NULL,
  `recipients` int(11) unsigned NOT NULL,
  `modified` datetime NOT NULL,
  `locked` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`conversation_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `engine4_messages_conversations`
--

INSERT INTO `engine4_messages_conversations` (`conversation_id`, `title`, `user_id`, `recipients`, `modified`, `locked`) VALUES
(1, 'Привет ', 1, 1, '2011-05-20 14:03:15', 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_messages_messages`
--

DROP TABLE IF EXISTS `engine4_messages_messages`;
CREATE TABLE IF NOT EXISTS `engine4_messages_messages` (
  `message_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `conversation_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `body` text COLLATE utf8_unicode_ci NOT NULL,
  `date` datetime NOT NULL,
  `attachment_type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT '',
  `attachment_id` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`message_id`),
  UNIQUE KEY `CONVERSATIONS` (`conversation_id`,`message_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `engine4_messages_messages`
--

INSERT INTO `engine4_messages_messages` (`message_id`, `conversation_id`, `user_id`, `title`, `body`, `date`, `attachment_type`, `attachment_id`) VALUES
(1, 1, 1, 'Привет ', 'Ну что давай начнем думать о первом модуле для нашего проекта. \r\n1) Нам нужно создать свой шкаф - т. е. вместилише всех покупок\r\n2) шкаф делиться на полки - категории товаров\r\n3) Рейтинг шопоголиков - нужно подумать про рейтинг более детально но главное это количество \r\n3.а) В рейтинг так же будет включено описание товара - т.е фото, категории, описание и тд\r\n4) Товары которые вы хотите обменять или продать \r\n5) Собираем все товары которые хотите обменять или продать и делаем магазин на сайте с этими товарами\r\n\r\nна данный момент пока все - сейчас займемся подключением стандартных модулей. после этого приступим к написанию уникальных модулей. нужно найти мануалы как это красиво сделать.', '2011-05-20 14:03:15', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_messages_recipients`
--

DROP TABLE IF EXISTS `engine4_messages_recipients`;
CREATE TABLE IF NOT EXISTS `engine4_messages_recipients` (
  `user_id` int(11) unsigned NOT NULL,
  `conversation_id` int(11) unsigned NOT NULL,
  `inbox_message_id` int(11) unsigned DEFAULT NULL,
  `inbox_updated` datetime DEFAULT NULL,
  `inbox_read` tinyint(1) DEFAULT NULL,
  `inbox_deleted` tinyint(1) DEFAULT NULL,
  `outbox_message_id` int(11) unsigned DEFAULT NULL,
  `outbox_updated` datetime DEFAULT NULL,
  `outbox_deleted` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`user_id`,`conversation_id`),
  KEY `INBOX_UPDATED` (`user_id`,`conversation_id`,`inbox_updated`),
  KEY `OUTBOX_UPDATED` (`user_id`,`conversation_id`,`outbox_updated`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_messages_recipients`
--

INSERT INTO `engine4_messages_recipients` (`user_id`, `conversation_id`, `inbox_message_id`, `inbox_updated`, `inbox_read`, `inbox_deleted`, `outbox_message_id`, `outbox_updated`, `outbox_deleted`) VALUES
(1, 1, NULL, NULL, 1, 1, 1, '2011-05-20 14:03:15', 0),
(3, 1, 1, '2011-05-20 14:03:15', 1, 0, 0, NULL, 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_network_membership`
--

DROP TABLE IF EXISTS `engine4_network_membership`;
CREATE TABLE IF NOT EXISTS `engine4_network_membership` (
  `resource_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `resource_approved` tinyint(1) NOT NULL DEFAULT '0',
  `user_approved` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`resource_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_network_membership`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_network_networks`
--

DROP TABLE IF EXISTS `engine4_network_networks`;
CREATE TABLE IF NOT EXISTS `engine4_network_networks` (
  `network_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `field_id` int(11) unsigned NOT NULL DEFAULT '0',
  `pattern` text COLLATE utf8_unicode_ci,
  `member_count` int(11) unsigned NOT NULL DEFAULT '0',
  `hide` tinyint(1) NOT NULL DEFAULT '0',
  `assignment` tinyint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`network_id`),
  KEY `assignment` (`assignment`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=8 ;

--
-- Dumping data for table `engine4_network_networks`
--

INSERT INTO `engine4_network_networks` (`network_id`, `title`, `description`, `field_id`, `pattern`, `member_count`, `hide`, `assignment`) VALUES
(1, 'North America', '', 0, NULL, 0, 0, 0),
(2, 'South America', '', 0, NULL, 0, 0, 0),
(3, 'Europe', '', 0, NULL, 0, 0, 0),
(4, 'Asia', '', 0, NULL, 0, 0, 0),
(5, 'Africa', '', 0, NULL, 0, 0, 0),
(6, 'Australia', '', 0, NULL, 0, 0, 0),
(7, 'Antarctica', '', 0, NULL, 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_poll_options`
--

DROP TABLE IF EXISTS `engine4_poll_options`;
CREATE TABLE IF NOT EXISTS `engine4_poll_options` (
  `poll_option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `poll_id` int(11) unsigned NOT NULL,
  `poll_option` text COLLATE utf8_unicode_ci NOT NULL,
  `votes` smallint(4) unsigned NOT NULL,
  PRIMARY KEY (`poll_option_id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_poll_options`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_poll_polls`
--

DROP TABLE IF EXISTS `engine4_poll_polls`;
CREATE TABLE IF NOT EXISTS `engine4_poll_polls` (
  `poll_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `is_closed` tinyint(1) NOT NULL DEFAULT '0',
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  `views` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `vote_count` int(11) unsigned NOT NULL DEFAULT '0',
  `search` tinyint(1) NOT NULL DEFAULT '1',
  PRIMARY KEY (`poll_id`),
  KEY `user_id` (`user_id`),
  KEY `is_closed` (`is_closed`),
  KEY `creation_date` (`creation_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_poll_polls`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_poll_votes`
--

DROP TABLE IF EXISTS `engine4_poll_votes`;
CREATE TABLE IF NOT EXISTS `engine4_poll_votes` (
  `poll_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `poll_option_id` int(11) unsigned NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  PRIMARY KEY (`poll_id`,`user_id`),
  KEY `poll_option_id` (`poll_option_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_poll_votes`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_storage_chunks`
--

DROP TABLE IF EXISTS `engine4_storage_chunks`;
CREATE TABLE IF NOT EXISTS `engine4_storage_chunks` (
  `chunk_id` bigint(20) unsigned NOT NULL AUTO_INCREMENT,
  `file_id` int(11) unsigned NOT NULL,
  `data` blob NOT NULL,
  PRIMARY KEY (`chunk_id`),
  KEY `file_id` (`file_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COLLATE=utf8_bin AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_storage_chunks`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_storage_files`
--

DROP TABLE IF EXISTS `engine4_storage_files`;
CREATE TABLE IF NOT EXISTS `engine4_storage_files` (
  `file_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `parent_file_id` int(11) unsigned DEFAULT NULL,
  `type` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci DEFAULT NULL,
  `parent_type` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `parent_id` int(11) unsigned DEFAULT NULL,
  `user_id` int(11) unsigned DEFAULT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `storage_type` varchar(32) COLLATE utf8_unicode_ci NOT NULL,
  `storage_path` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `extension` varchar(8) COLLATE utf8_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `mime_major` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `mime_minor` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `size` bigint(20) unsigned NOT NULL,
  `hash` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  PRIMARY KEY (`file_id`),
  UNIQUE KEY `parent_file_id` (`parent_file_id`,`type`),
  KEY `PARENT` (`parent_type`,`parent_id`),
  KEY `user_id` (`user_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=7 ;

--
-- Dumping data for table `engine4_storage_files`
--

INSERT INTO `engine4_storage_files` (`file_id`, `parent_file_id`, `type`, `parent_type`, `parent_id`, `user_id`, `creation_date`, `modified_date`, `storage_type`, `storage_path`, `extension`, `name`, `mime_major`, `mime_minor`, `size`, `hash`) VALUES
(1, NULL, NULL, 'user', 1, 1, '2011-05-20 13:25:17', '2011-05-20 13:25:17', 'local', 'public/user/1000000/1000/1/1.jpg', 'jpg', 'm_219591_10150180097323674_694078673_6848888_5665830_o.jpg', 'image', 'jpeg', 48839, 'e08f4a03663249be81b85b8dcb51f43e'),
(2, 1, 'thumb.profile', 'user', 1, 1, '2011-05-20 13:25:17', '2011-05-20 13:25:17', 'local', 'public/user/1000000/1000/1/2.jpg', 'jpg', 'p_219591_10150180097323674_694078673_6848888_5665830_o.jpg', 'image', 'jpeg', 6656, '87f35d47cb95da462088c62597a24fda'),
(3, 1, 'thumb.normal', 'user', 1, 1, '2011-05-20 13:25:17', '2011-05-20 13:25:17', 'local', 'public/user/1000000/1000/1/3.jpg', 'jpg', 'in_219591_10150180097323674_694078673_6848888_5665830_o.jpg', 'image', 'jpeg', 3907, 'ab8a192c03fe71dfd91e58cf295123db'),
(4, 1, 'thumb.icon', 'user', 1, 1, '2011-05-20 13:25:17', '2011-05-20 13:25:17', 'local', 'public/user/1000000/1000/1/4.jpg', 'jpg', 'is_219591_10150180097323674_694078673_6848888_5665830_o.jpg', 'image', 'jpeg', 1525, '90289a429e39174ad48f653769fdde72'),
(5, NULL, NULL, 'core_link', 1, 1, '2011-05-20 13:45:44', '2011-05-20 13:45:44', 'local', 'public/core_link/1000000/1000/1/5.png', 'png', 'thumb_a6e15735c0deb064976e6386d28c7554..png', 'image', 'jpeg', 2996, '7a0ce130c200019f6cf52324a15190b2'),
(6, NULL, NULL, 'core_link', 2, 1, '2011-05-20 15:12:03', '2011-05-20 15:12:03', 'local', 'public/core_link/1000000/1000/2/6.gif', 'gif', 'thumb_f62659e6e9966eda15dc487ba11c3d07..gif', 'image', 'jpeg', 2081, 'd3fff82bca6b6457a2c29430a64b5f62');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_users`
--

DROP TABLE IF EXISTS `engine4_users`;
CREATE TABLE IF NOT EXISTS `engine4_users` (
  `user_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `username` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `displayname` varchar(128) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `photo_id` int(11) unsigned NOT NULL DEFAULT '0',
  `status` text COLLATE utf8_unicode_ci,
  `status_date` datetime DEFAULT NULL,
  `password` char(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `salt` char(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `locale` varchar(16) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'auto',
  `language` varchar(8) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'en_US',
  `timezone` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT 'America/Los_Angeles',
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `show_profileviewers` tinyint(1) NOT NULL DEFAULT '1',
  `level_id` int(11) unsigned NOT NULL,
  `invites_used` int(11) unsigned NOT NULL DEFAULT '0',
  `extra_invites` int(11) unsigned NOT NULL DEFAULT '0',
  `enabled` tinyint(1) NOT NULL DEFAULT '1',
  `verified` tinyint(1) NOT NULL DEFAULT '0',
  `creation_date` datetime NOT NULL,
  `creation_ip` bigint(11) NOT NULL,
  `modified_date` datetime NOT NULL,
  `lastlogin_date` datetime DEFAULT NULL,
  `lastlogin_ip` int(11) DEFAULT NULL,
  `update_date` int(11) DEFAULT NULL,
  `member_count` smallint(5) unsigned NOT NULL DEFAULT '0',
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `EMAIL` (`email`),
  UNIQUE KEY `USERNAME` (`username`),
  KEY `MEMBER_COUNT` (`member_count`),
  KEY `CREATION_DATE` (`creation_date`),
  KEY `search` (`search`),
  KEY `enabled` (`enabled`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `engine4_users`
--

INSERT INTO `engine4_users` (`user_id`, `email`, `username`, `displayname`, `photo_id`, `status`, `status_date`, `password`, `salt`, `locale`, `language`, `timezone`, `search`, `show_profileviewers`, `level_id`, `invites_used`, `extra_invites`, `enabled`, `verified`, `creation_date`, `creation_ip`, `modified_date`, `lastlogin_date`, `lastlogin_ip`, `update_date`, `member_count`, `view_count`) VALUES
(1, 'shopaholiccompany@gmail.com', 'shopaholic', 'Дима Овчаренко', 1, '<a href="http://www.last.fm/" target="_blank" rel="nofollow">http://www.last.fm/</a>', '2011-05-20 15:11:27', '7b99d778d3070821b88bd2f73fc907ac', '6121585', 'ru_RU', 'ru_RU', 'Europe/Athens', 1, 1, 1, 0, 0, 1, 1, '2011-05-16 16:49:53', 2130706433, '2011-05-20 15:12:03', '2011-05-20 14:08:01', 1270, NULL, 1, 3),
(2, 'snicho@urk.net', 'snicho', 'Test Testoviy', 0, NULL, NULL, '26d2612ebbd056aeba34619f40141436', '8028141', 'ru_RU', 'ru_RU', 'Europe/Athens', 1, 1, 4, 0, 0, 1, 0, '2011-05-20 13:31:19', 2130706433, '2011-05-20 13:31:19', NULL, NULL, NULL, 0, 0),
(3, 'snicho@rambler.ru', 'snich', 'Сергей Овчаренко', 0, NULL, NULL, '58f4591fe22c842928f3d8ad50671cda', '9179519', 'ru_RU', 'ru_RU', 'Europe/Athens', 1, 1, 4, 0, 0, 1, 1, '2011-05-20 13:53:58', 2130706433, '2011-05-20 13:55:13', '2011-05-20 14:06:53', 1270, NULL, 1, 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_block`
--

DROP TABLE IF EXISTS `engine4_user_block`;
CREATE TABLE IF NOT EXISTS `engine4_user_block` (
  `user_id` int(11) unsigned NOT NULL,
  `blocked_user_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`user_id`,`blocked_user_id`),
  KEY `REVERSE` (`blocked_user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_user_block`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_facebook`
--

DROP TABLE IF EXISTS `engine4_user_facebook`;
CREATE TABLE IF NOT EXISTS `engine4_user_facebook` (
  `user_id` int(11) unsigned NOT NULL,
  `facebook_uid` bigint(20) unsigned NOT NULL,
  PRIMARY KEY (`user_id`),
  UNIQUE KEY `facebook_uid` (`facebook_uid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_user_facebook`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_fields_maps`
--

DROP TABLE IF EXISTS `engine4_user_fields_maps`;
CREATE TABLE IF NOT EXISTS `engine4_user_fields_maps` (
  `field_id` int(11) unsigned NOT NULL,
  `option_id` int(11) unsigned NOT NULL,
  `child_id` int(11) unsigned NOT NULL,
  `order` smallint(6) NOT NULL,
  PRIMARY KEY (`field_id`,`option_id`,`child_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `engine4_user_fields_maps`
--

INSERT INTO `engine4_user_fields_maps` (`field_id`, `option_id`, `child_id`, `order`) VALUES
(0, 0, 1, 1),
(1, 1, 2, 2),
(1, 1, 3, 3),
(1, 1, 4, 4),
(1, 1, 5, 5),
(1, 1, 6, 6),
(1, 1, 7, 7),
(1, 1, 8, 8),
(1, 1, 9, 9),
(1, 1, 10, 10),
(1, 1, 11, 11),
(1, 1, 12, 12),
(1, 1, 13, 13);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_fields_meta`
--

DROP TABLE IF EXISTS `engine4_user_fields_meta`;
CREATE TABLE IF NOT EXISTS `engine4_user_fields_meta` (
  `field_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `type` varchar(24) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `label` varchar(64) COLLATE utf8_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `alias` varchar(32) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL DEFAULT '',
  `required` tinyint(1) NOT NULL DEFAULT '0',
  `display` tinyint(1) unsigned NOT NULL,
  `publish` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `search` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `order` smallint(3) unsigned NOT NULL DEFAULT '999',
  `config` text COLLATE utf8_unicode_ci,
  `validators` text COLLATE utf8_unicode_ci,
  `filters` text COLLATE utf8_unicode_ci,
  `style` text COLLATE utf8_unicode_ci,
  `error` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=14 ;

--
-- Dumping data for table `engine4_user_fields_meta`
--

INSERT INTO `engine4_user_fields_meta` (`field_id`, `type`, `label`, `description`, `alias`, `required`, `display`, `publish`, `search`, `order`, `config`, `validators`, `filters`, `style`, `error`) VALUES
(1, 'profile_type', 'Profile Type', '', 'profile_type', 1, 0, 0, 2, 999, '', NULL, NULL, NULL, NULL),
(2, 'heading', 'Personal Information', '', '', 0, 1, 0, 0, 999, '', NULL, NULL, NULL, NULL),
(3, 'first_name', 'First Name', '', 'first_name', 1, 1, 0, 2, 999, '', '[["StringLength",false,[1,32]]]', NULL, NULL, NULL),
(4, 'last_name', 'Last Name', '', 'last_name', 1, 1, 0, 2, 999, '', '[["StringLength",false,[1,32]]]', NULL, NULL, NULL),
(5, 'gender', 'Gender', '', 'gender', 0, 1, 0, 1, 999, '', NULL, NULL, NULL, NULL),
(6, 'birthdate', 'Birthday', '', 'birthdate', 0, 1, 0, 1, 999, '', NULL, NULL, NULL, NULL),
(7, 'heading', 'Contact Information', '', '', 0, 1, 0, 0, 999, '', NULL, NULL, NULL, NULL),
(8, 'website', 'Website', '', '', 0, 1, 0, 0, 999, '', NULL, NULL, NULL, NULL),
(9, 'twitter', 'Twitter', '', '', 0, 1, 0, 0, 999, '', NULL, NULL, NULL, NULL),
(10, 'facebook', 'Facebook', '', '', 0, 1, 0, 0, 999, '', NULL, NULL, NULL, NULL),
(11, 'aim', 'AIM', '', '', 0, 1, 0, 0, 999, '', NULL, NULL, NULL, NULL),
(12, 'heading', 'Personal Details', '', '', 0, 1, 0, 0, 999, '', NULL, NULL, NULL, NULL),
(13, 'about_me', 'About Me', '', '', 0, 1, 0, 0, 999, '', NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_fields_options`
--

DROP TABLE IF EXISTS `engine4_user_fields_options`;
CREATE TABLE IF NOT EXISTS `engine4_user_fields_options` (
  `option_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `field_id` int(11) unsigned NOT NULL,
  `label` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '999',
  PRIMARY KEY (`option_id`),
  KEY `field_id` (`field_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=4 ;

--
-- Dumping data for table `engine4_user_fields_options`
--

INSERT INTO `engine4_user_fields_options` (`option_id`, `field_id`, `label`, `order`) VALUES
(1, 1, 'Regular Member', 1),
(2, 5, 'Male', 1),
(3, 5, 'Female', 2);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_fields_search`
--

DROP TABLE IF EXISTS `engine4_user_fields_search`;
CREATE TABLE IF NOT EXISTS `engine4_user_fields_search` (
  `item_id` int(11) unsigned NOT NULL,
  `profile_type` smallint(11) unsigned DEFAULT NULL,
  `first_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `last_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `gender` smallint(6) unsigned DEFAULT NULL,
  `birthdate` date DEFAULT NULL,
  PRIMARY KEY (`item_id`),
  KEY `profile_type` (`profile_type`),
  KEY `first_name` (`first_name`),
  KEY `last_name` (`last_name`),
  KEY `gender` (`gender`),
  KEY `birthdate` (`birthdate`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_user_fields_search`
--

INSERT INTO `engine4_user_fields_search` (`item_id`, `profile_type`, `first_name`, `last_name`, `gender`, `birthdate`) VALUES
(1, 1, 'Дима', 'Овчаренко', 2, '1986-11-24'),
(2, 1, 'Test', 'Testoviy', NULL, NULL),
(3, 1, 'Сергей', 'Овчаренко', NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_fields_values`
--

DROP TABLE IF EXISTS `engine4_user_fields_values`;
CREATE TABLE IF NOT EXISTS `engine4_user_fields_values` (
  `item_id` int(11) unsigned NOT NULL,
  `field_id` int(11) unsigned NOT NULL,
  `index` smallint(3) unsigned NOT NULL DEFAULT '0',
  `value` text COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`item_id`,`field_id`,`index`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_user_fields_values`
--

INSERT INTO `engine4_user_fields_values` (`item_id`, `field_id`, `index`, `value`) VALUES
(1, 1, 0, '1'),
(1, 3, 0, 'Дима'),
(1, 4, 0, 'Овчаренко'),
(1, 5, 0, '2'),
(1, 6, 0, '1986-11-24'),
(2, 1, 0, '1'),
(2, 3, 0, 'Test'),
(2, 4, 0, 'Testoviy'),
(3, 1, 0, '1'),
(3, 3, 0, 'Сергей'),
(3, 4, 0, 'Овчаренко');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_forgot`
--

DROP TABLE IF EXISTS `engine4_user_forgot`;
CREATE TABLE IF NOT EXISTS `engine4_user_forgot` (
  `user_id` int(11) unsigned NOT NULL,
  `code` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `creation_date` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_user_forgot`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_listitems`
--

DROP TABLE IF EXISTS `engine4_user_listitems`;
CREATE TABLE IF NOT EXISTS `engine4_user_listitems` (
  `listitem_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `list_id` int(11) unsigned NOT NULL,
  `child_id` int(11) unsigned NOT NULL,
  PRIMARY KEY (`listitem_id`),
  KEY `list_id` (`list_id`),
  KEY `child_id` (`child_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `engine4_user_listitems`
--

INSERT INTO `engine4_user_listitems` (`listitem_id`, `list_id`, `child_id`) VALUES
(1, 1, 3);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_lists`
--

DROP TABLE IF EXISTS `engine4_user_lists`;
CREATE TABLE IF NOT EXISTS `engine4_user_lists` (
  `list_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(64) COLLATE utf8_unicode_ci NOT NULL DEFAULT '',
  `owner_id` int(11) unsigned NOT NULL,
  `child_count` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`list_id`),
  KEY `owner_id` (`owner_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=2 ;

--
-- Dumping data for table `engine4_user_lists`
--

INSERT INTO `engine4_user_lists` (`list_id`, `title`, `owner_id`, `child_count`) VALUES
(1, 'Семья', 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_membership`
--

DROP TABLE IF EXISTS `engine4_user_membership`;
CREATE TABLE IF NOT EXISTS `engine4_user_membership` (
  `resource_id` int(11) unsigned NOT NULL,
  `user_id` int(11) unsigned NOT NULL,
  `active` tinyint(1) NOT NULL DEFAULT '0',
  `resource_approved` tinyint(1) NOT NULL DEFAULT '0',
  `user_approved` tinyint(1) NOT NULL DEFAULT '0',
  `message` text COLLATE utf8_unicode_ci,
  `description` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`resource_id`,`user_id`),
  KEY `REVERSE` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_user_membership`
--

INSERT INTO `engine4_user_membership` (`resource_id`, `user_id`, `active`, `resource_approved`, `user_approved`, `message`, `description`) VALUES
(1, 2, 0, 1, 0, NULL, NULL),
(1, 3, 1, 1, 1, NULL, NULL),
(2, 1, 0, 0, 1, NULL, NULL),
(3, 1, 1, 1, 1, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_online`
--

DROP TABLE IF EXISTS `engine4_user_online`;
CREATE TABLE IF NOT EXISTS `engine4_user_online` (
  `ip` bigint(11) NOT NULL,
  `user_id` int(11) unsigned NOT NULL DEFAULT '0',
  `active` datetime NOT NULL,
  PRIMARY KEY (`ip`,`user_id`),
  KEY `LOOKUP` (`active`)
) ENGINE=MEMORY DEFAULT CHARSET=latin1;

--
-- Dumping data for table `engine4_user_online`
--

INSERT INTO `engine4_user_online` (`ip`, `user_id`, `active`) VALUES
(2130706433, 0, '2011-05-20 16:08:07'),
(2130706433, 1, '2011-05-20 16:08:08');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_signup`
--

DROP TABLE IF EXISTS `engine4_user_signup`;
CREATE TABLE IF NOT EXISTS `engine4_user_signup` (
  `signup_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `class` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `order` smallint(6) NOT NULL DEFAULT '999',
  `enable` smallint(1) NOT NULL DEFAULT '0',
  PRIMARY KEY (`signup_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=5 ;

--
-- Dumping data for table `engine4_user_signup`
--

INSERT INTO `engine4_user_signup` (`signup_id`, `class`, `order`, `enable`) VALUES
(1, 'User_Plugin_Signup_Account', 0, 1),
(2, 'User_Plugin_Signup_Fields', 1, 1),
(3, 'User_Plugin_Signup_Photo', 2, 1),
(4, 'User_Plugin_Signup_Invite', 3, 0);

-- --------------------------------------------------------

--
-- Table structure for table `engine4_user_verify`
--

DROP TABLE IF EXISTS `engine4_user_verify`;
CREATE TABLE IF NOT EXISTS `engine4_user_verify` (
  `user_id` int(11) unsigned NOT NULL,
  `code` varchar(64) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`user_id`),
  KEY `code` (`code`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_user_verify`
--

INSERT INTO `engine4_user_verify` (`user_id`, `code`, `date`) VALUES
(2, '969eb7b5e68b19000ea7af20ae84c9d4', '2011-05-20 13:31:19');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_video_categories`
--

DROP TABLE IF EXISTS `engine4_video_categories`;
CREATE TABLE IF NOT EXISTS `engine4_video_categories` (
  `category_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned NOT NULL,
  `category_name` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  PRIMARY KEY (`category_id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=16 ;

--
-- Dumping data for table `engine4_video_categories`
--

INSERT INTO `engine4_video_categories` (`category_id`, `user_id`, `category_name`) VALUES
(1, 0, 'Autos & Vehicles'),
(2, 0, 'Comedy'),
(3, 0, 'Education'),
(4, 0, 'Entertainment'),
(5, 0, 'Film & Animation'),
(6, 0, 'Gaming'),
(7, 0, 'Howto & Style'),
(8, 0, 'Music'),
(9, 0, 'News & Politics'),
(10, 0, 'Nonprofits & Activism'),
(11, 0, 'People & Blogs'),
(12, 0, 'Pets & Animals'),
(13, 0, 'Science & Technology'),
(14, 0, 'Sports'),
(15, 0, 'Travel & Events');

-- --------------------------------------------------------

--
-- Table structure for table `engine4_video_ratings`
--

DROP TABLE IF EXISTS `engine4_video_ratings`;
CREATE TABLE IF NOT EXISTS `engine4_video_ratings` (
  `video_id` int(10) unsigned NOT NULL,
  `user_id` int(9) unsigned NOT NULL,
  `rating` tinyint(1) unsigned DEFAULT NULL,
  PRIMARY KEY (`video_id`,`user_id`),
  KEY `INDEX` (`video_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dumping data for table `engine4_video_ratings`
--


-- --------------------------------------------------------

--
-- Table structure for table `engine4_video_videos`
--

DROP TABLE IF EXISTS `engine4_video_videos`;
CREATE TABLE IF NOT EXISTS `engine4_video_videos` (
  `video_id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `title` varchar(100) COLLATE utf8_unicode_ci NOT NULL,
  `description` text COLLATE utf8_unicode_ci NOT NULL,
  `search` tinyint(1) NOT NULL DEFAULT '1',
  `owner_type` varchar(128) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL,
  `owner_id` int(11) NOT NULL,
  `creation_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `view_count` int(11) unsigned NOT NULL DEFAULT '0',
  `comment_count` int(11) unsigned NOT NULL DEFAULT '0',
  `type` tinyint(1) NOT NULL,
  `code` varchar(150) COLLATE utf8_unicode_ci NOT NULL,
  `photo_id` int(11) unsigned DEFAULT NULL,
  `rating` float NOT NULL,
  `category_id` int(11) unsigned NOT NULL DEFAULT '0',
  `status` tinyint(1) NOT NULL,
  `file_id` int(11) unsigned NOT NULL,
  `duration` int(9) unsigned NOT NULL,
  PRIMARY KEY (`video_id`),
  KEY `owner_id` (`owner_id`,`owner_type`),
  KEY `search` (`search`),
  KEY `creation_date` (`creation_date`),
  KEY `view_count` (`creation_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci AUTO_INCREMENT=1 ;

--
-- Dumping data for table `engine4_video_videos`
--

