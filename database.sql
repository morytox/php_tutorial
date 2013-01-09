CREATE TABLE `highscores` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
    `score` int(11) unsigned NOT NULL,
  `nick` varchar(255) DEFAULT NULL,
    `realname` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
    `datesubmitted` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
  ) ENGINE=InnoDB AUTO_INCREMENT=46 DEFAULT CHARSET=latin1
