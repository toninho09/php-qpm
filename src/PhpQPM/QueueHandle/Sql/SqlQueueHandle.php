<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 05/08/2016
 * Time: 22:45
 */

namespace PhpQPM\QueueHandle\Sql;


use PDO;
use PhpQPM\Process;
use PhpQPM\QueueHandle\QueueHandleInterface;

class SqlQueueHandle implements QueueHandleInterface
{
    /**
     * @var PDO
     */
    protected $conn;
    protected $tableName = 'queue';
    protected $queue;

    /**
     * SqlQueueHandle constructor.
     * @param $pdo
     */
    public function __construct($pdo = null)
    {
        $this->conn = $pdo;
        $this->queue = 'default';
    }

    public function connect($dsn,$username = '',$password = '',$options = []){
        $this->conn = new PDO($dsn,$username,$password,$options);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        $this->conn->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTable();
    }

    public function isConected()
    {
        try{
            $sth = $this->conn->prepare("select 1 as status");
            $sth->execute();
            return (boolean) $sth->fetch()->status;
        }catch (\Exception $ex){
            return false;
        }
    }

    public function checkTable(){
        try{
            $sth = $this->conn->prepare("SELECT id,queue,`process`,attempts,reserved,`error`,`return`,`type`,create_at,reserved_at,finish_at FROM queue limit 1");
            return (boolean) $sth->execute();
        }catch (\Exception $ex){
            return false;
        }
    }

    public function droptable(){
        $sth = $this->conn->prepare("DROP TABLE IF EXISTS queue");
        $sth->execute();
    }

    public function createTable(){
        $sth = $this->conn->prepare("CREATE TABLE IF NOT EXISTS `queue` (
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
          PRIMARY KEY (`id`)
        )");
        $sth->execute();
    }

    public function putProcess(Process &$process, $queue = null)
    {
        if($queue === null) $queue = $this->queue;
        $sth = $this->conn->prepare("INSERT INTO queue (queue,process, type) VALUES (:queue,:process,:type)");
        $sth->bindValue(':queue',$queue);
        $sth->bindValue(':process',$process->getProcess());
        $sth->bindValue(':type',$process->getType());
        $sth->execute();
        $id = $this->conn->lastInsertId();
        return $this->getProcess($id);
    }

    public function getProcess($id)
    {
        $sth = $this->conn->prepare("SELECT * FROM queue WHERE id = :id");
        $sth->bindValue(':id',$id);
        $sth->execute();
        return SqlProcessFactory::create($sth->fetchObject(),$this);
    }

    public function updateProcess(Process &$process)
    {
        $sth = $this->conn->prepare("SELECT * FROM queue WHERE id = :id");
        $sth->bindValue(':id',$process->getId());
        $sth->execute();
        return SqlProcessFactory::update($process,$sth->fetchObject());
    }

    public function updateQueue(Process $process, $queue = null)
    {
        if($queue === null) $queue = $this->queue;
        $sth = $this->conn->prepare("UPDATE queue SET queue = :queue, process = :process, attempts = :attempts, reserved = :reserved, error = :error, `return` = :return, type = :type, create_at = :create_at, reserved_at = :reserved_at, finish_at = :finish_at WHERE id = :id and queue = :queue_origin");
        $sth->bindValue(':id',$process->getId());
        $sth->bindValue(':queue',$process->getQueue());
        $sth->bindValue(':process',$process->getProcess());
        $sth->bindValue(':attempts',$process->getAttempts());
        $sth->bindValue(':reserved',$process->isReserved() ? 1 : 0);
        $sth->bindValue(':error',$process->getError());
        $sth->bindValue(':return',$process->getReturn());
        $sth->bindValue(':type',$process->getType());
        $sth->bindValue(':create_at',$process->getCreatedAt());
        $sth->bindValue(':reserved_at',$process->getReservedAt());
        $sth->bindValue(':finish_at',$process->getFinishAt());
        $sth->bindValue(':queue_origin',$queue);
        $sth->execute();
    }

    public function setQueue($queue)
    {
        $this->queue = $queue;
    }

    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param null $queue
     * @return int
     */
    public function processInQueue($queue = null)
    {
        if($queue === null){
            $sth = $this->conn->prepare("select count(*) count from queue where reserved = 0");
        }else{
            $sth = $this->conn->prepare("select count(*) count from queue where reserved = 0, queue = :queue");
            $sth->bindParam(':queue',$queue);
        }
        $sth->execute();
        return $sth->fetchObject()->count;
    }

    public function hasProcess()
    {
        return $this->processInQueue() > 0;
    }

    public function clearQueue($queue = null){
        if($queue !== null){
            $sth = $this->conn->prepare("delete from queue where queue = :queue");
            $sth->bindValue(':queue',$queue);
        }else{
            $sth = $this->conn->prepare("delete from queue");
        }
        $sth->execute();
    }

    public function reserveProcess($queue = null)
    {
        if($queue === null)$queue = $this->queue;
        $this->conn->beginTransaction();
        $sth = $this->conn->prepare("select id from queue where reserved = 0 and queue = :queue order by id desc");
        $sth->bindValue(":queue",$queue);
        $sth->execute();
        $obj = $sth->fetchObject();
        if(empty($obj)) return null;
        $process = $this->getProcess($obj->id);
        $process->reserve();
        $process->addAttempts();
        $process->updateQueue();
        $this->conn->commit();
        return $process;

    }

    public function finishProcess(Process &$process, $queue = null)
    {
        $process->setFinishAt(date('Y-m-d H:i:s'));
        $process->updateQueue();
    }

    public function failedProcess(Process &$process , $error = '', $queue = 'default')
    {
        $process->setError($error);
        $process->setFinishAt(date('Y-m-d H:i:s'));
        $process->updateQueue();
    }

    public function removeProcess($id)
    {
        $sth = $this->conn->prepare("delete from queue where id = :id");
        $sth->bindValue(':id',$id);
        $sth->execute();
    }

    /**
     * @return string
     */
    public function getTableName()
    {
        return $this->tableName;
    }

    /**
     * @param string $tableName
     */
    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }




}