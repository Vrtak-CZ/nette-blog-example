CREATE TABLE `articles` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(254) NOT NULL,
  `slug` varchar(254) NOT NULL,
  `text` text NOT NULL,
  `published` datetime NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `comments` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `article_id` int(11) NOT NULL,
  `name` varchar(254) NOT NULL,
  `email` varchar(254) NOT NULL,
  `web` varchar(254) DEFAULT NULL,
  `text` text NOT NULL,
  `published` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `comments_x_article_id` (`article_id`),
  CONSTRAINT `comments_x_article` FOREIGN KEY (`article_id`) REFERENCES `articles` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;
