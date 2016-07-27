-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               10.1.13-MariaDB - mariadb.org binary distribution
-- ОС Сервера:                   Win32
-- HeidiSQL Версия:              9.3.0.4984
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;

-- Дамп структуры для таблица test.articles
DROP TABLE IF EXISTS `articles`;
CREATE TABLE IF NOT EXISTS `articles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `uri` varchar(128) COLLATE utf8_unicode_ci NOT NULL,
  `type` tinyint(4) NOT NULL DEFAULT '0' COMMENT '0 static page, 1 blog post, 2 blog category',
  `title` tinytext COLLATE utf8_unicode_ci,
  `parent` int(11) unsigned DEFAULT NULL,
  `text` text COLLATE utf8_unicode_ci,
  `published` int(10) unsigned NOT NULL,
  `meta_title` tinytext COLLATE utf8_unicode_ci,
  `meta_keywords` text COLLATE utf8_unicode_ci,
  `meta_description` text COLLATE utf8_unicode_ci,
  UNIQUE KEY `ID` (`id`),
  UNIQUE KEY `URI` (`uri`),
  KEY `TYPE` (`type`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Дамп данных таблицы test.articles: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `articles` DISABLE KEYS */;
INSERT INTO `articles` (`id`, `uri`, `type`, `title`, `parent`, `text`, `published`, `meta_title`, `meta_keywords`, `meta_description`) VALUES
	(1, 'der', 1, 'post1', 6, '<p>post13</p>', 0, 'post1', '', 'post1'),
	(4, 'der2', 1, 'post2', 14, '<p>post13dfd dfdfd</p>', 0, 'post1', '', 'post1'),
	(5, '', 3, 'Use redirects when handling forms', NULL, '<p>It is a well-known method used in the development of sites. However, for a beginner it is not obvious that after processing forms necessary to make a redirect. The method consists in the fact that when a user performs an action (send form, or presses &laquo;sign out&raquo;) &ndash; the processing of this action must necessarily be completed with redirects, rather than output html-pages. This makes it possible to solve such problems:</p>\r\n<ul>\r\n<li>Eliminates form re-submission, when the visitor after the form submission clicks F5;</li>\r\n<li>Allows you to clearly distinguish between the &ldquo;model&rdquo; and &ldquo;view&rdquo; &ndash; this is useful even in systems, the principle of which is not based on the MVC model.</li>\r\n</ul>\r\n<p>Also here we can talk about improving security, because without redirects the attacker can easily send the form several times in a very short period of time (pressing F5 and Enter many times). Depending on the method of realization of the form itself, it can lead to spam filling of database tables.</p>\r\n<p>Consider the example of this principle:</p>\r\n<pre>&lt;?php\r\n \r\n// use session to store message for user\r\nsession_start();\r\n \r\n// process the request on the action\r\nif(isset($_GET[\'c\'])){\r\n \r\n  // set the message to show it in the next browser request\r\n  $_SESSION[\'message\'] = \'You clicked \'.$_GET[\'c\'];\r\n \r\n  // send redirect header to the browser\r\n  header(\'Location: http://\'.$_SERVER[\'HTTP_HOST\'].$_SERVER[\'SCRIPT_NAME\']);\r\n \r\n  // and stop script execution\r\n  die();\r\n}\r\n \r\n// no action requests - show default page\r\n \r\n?&gt;\r\n&lt;html&gt;\r\n  &lt;body&gt;&lt;?php\r\n      if(isset($_SESSION[\'message\'])){\r\n        echo $_SESSION[\'message\'];\r\n        unset($_SESSION[\'message\']);\r\n      }\r\n    ?&gt;&lt;br&gt;\r\n    &lt;a href="?c=first button"&gt;[1] Click ME!&lt;/a&gt; OR \r\n    &lt;a href="?c=second button"&gt;[2] click me&lt;/a&gt;&lt;br&gt;\r\n    &lt;a href=""&gt;reload page&lt;/a&gt;\r\n  &lt;/body&gt;\r\n&lt;/html&gt;\r\n</pre>\r\n<p>This script handles the press &ldquo;Click Me&rdquo; button, performing a redirect after processing your request and displays a message indicating that it is pressed by. Of course, in this case was not necessary to use the redirect, but an example has turned simple and intuitive. It`s necessary to have redirects when processing the request, that performs a changing or adding any data to the server. Basically it is forms processing.</p>', 0, 'index meta title', 'index meta keywords', 'index meta description'),
	(6, 'advices', 2, 'Advices', NULL, '<p><span style="color: #373737; font-family: \'Helvetica Neue\', Helvetica, Arial, sans-serif; font-size: 15px; line-height: 24.3px;">Advices, that can be very helpfull for beginners.</span></p>', 0, 'Advices meta title', 'Advices meta keywords, php advices', 'Advices meta description'),
	(14, 'articles', 2, 'Articles', NULL, '', 1469613789, 'Articles meta title', 'Articles meta keywords', 'Articles meta description'),
	(16, 'article1', 1, 'Use redirects when handling forms', 14, '<p>It is a well-known method used in the development of sites. However, for a beginner it is not obvious that after processing forms necessary to make a redirect. The method consists in the fact that when a user performs an action (send form, or presses &laquo;sign out&raquo;) &ndash; the processing of this action must necessarily be completed with redirects, rather than output html-pages. This makes it possible to solve such problems:</p>\r\n<ul>\r\n<li>Eliminates form re-submission, when the visitor after the form submission clicks F5;</li>\r\n<li>Allows you to clearly distinguish between the &ldquo;model&rdquo; and &ldquo;view&rdquo; &ndash; this is useful even in systems, the principle of which is not based on the MVC model.</li>\r\n</ul>\r\n<p>Also here we can talk about improving security, because without redirects the attacker can easily send the form several times in a very short period of time (pressing F5 and Enter many times). Depending on the method of realization of the form itself, it can lead to spam filling of database tables.</p>\r\n<p>Consider the example of this principle:</p>\r\n<pre>&lt;?php\r\n \r\n// use session to store message for user\r\nsession_start();\r\n \r\n// process the request on the action\r\nif(isset($_GET[\'c\'])){\r\n \r\n  // set the message to show it in the next browser request\r\n  $_SESSION[\'message\'] = \'You clicked \'.$_GET[\'c\'];\r\n \r\n  // send redirect header to the browser\r\n  header(\'Location: http://\'.$_SERVER[\'HTTP_HOST\'].$_SERVER[\'SCRIPT_NAME\']);\r\n \r\n  // and stop script execution\r\n  die();\r\n}\r\n \r\n// no action requests - show default page\r\n \r\n?&gt;\r\n&lt;html&gt;\r\n  &lt;body&gt;&lt;?php\r\n      if(isset($_SESSION[\'message\'])){\r\n        echo $_SESSION[\'message\'];\r\n        unset($_SESSION[\'message\']);\r\n      }\r\n    ?&gt;&lt;br&gt;\r\n    &lt;a href="?c=first button"&gt;[1] Click ME!&lt;/a&gt; OR \r\n    &lt;a href="?c=second button"&gt;[2] click me&lt;/a&gt;&lt;br&gt;\r\n    &lt;a href=""&gt;reload page&lt;/a&gt;\r\n  &lt;/body&gt;\r\n&lt;/html&gt;\r\n</pre>\r\n<p>This script handles the press &ldquo;Click Me&rdquo; button, performing a redirect after processing your request and displays a message indicating that it is pressed by. Of course, in this case was not necessary to use the redirect, but an example has turned simple and intuitive. It`s necessary to have redirects when processing the request, that performs a changing or adding any data to the server. Basically it is forms processing.</p>', 1469622767, '', '', ''),
	(19, 'hhhgg', 1, '5656', 6, '<p>ttrh</p>', 1469623001, '', '', '');
/*!40000 ALTER TABLE `articles` ENABLE KEYS */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
