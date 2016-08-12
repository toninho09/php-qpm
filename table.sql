CREATE TABLE `queue` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `queue` varchar(255) DEFAULT 'default',
  `process` text NOT NULL,
  `attempts` int(11) NOT NULL DEFAULT '0',
  `reserved` int(11) NOT NULL DEFAULT '0',
  `error` text,
  `return` text,
  `type` int(11) NOT NULL,
  `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `reserved_at` timestamp NULL DEFAULT NULL,
  `finish_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_QUEUE_ID_RESERVED` (`id`,`reserved`)
);
