<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 11/08/16
 * Time: 21:15
 */

namespace PhpQPM\QueueHandle\Sql;


class SqliteQueueHandle extends SqlQueueHandle
{
    public function createTable()
    {
        $sth = $this->conn->prepare("CREATE TABLE IF NOT EXISTS `queue` (
          `id` INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL ,
          `queue` varchar(255) DEFAULT 'default',
          `process` text NOT NULL,
          `attempts` int(11) NOT NULL DEFAULT '0',
          `reserved` int(11) NOT NULL DEFAULT '0',
          `error` text,
          `return` text,
          `type` int(11) NOT NULL,
          `create_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
          `reserved_at` timestamp NULL DEFAULT NULL,
          `finish_at` timestamp NULL DEFAULT NULL
        )");
        $sth->execute();
    }
}