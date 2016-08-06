<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 05/08/2016
 * Time: 22:45
 */

namespace PhpQPM\QueueHandle;


use PDO;
use PhpQPM\Process;

class SqlQueueHandle implements QueueHandleInterface
{
    /**
     * @var PDO
     */
    private $conn;
    private $tableName = 'queue';

    /**
     * SqlQueueHandle constructor.
     * @param $pdo
     */
    public function __construct($pdo = null)
    {
        $this->conn = $pdo;
    }

    public function connect($dsn,$username = '',$password = '',$options = []){
        $this->conn = new PDO($dsn,$username,$password,$options);
        $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
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

    public function putProcess(Process &$process, $queue = 'default')
    {
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
        return $sth->fetchObject();
    }

    public function updateProcess(Process &$process, $queue = 'default')
    {
        // TODO: Implement updateProcess() method.
    }

    public function updateQueue(Process $process, $queue = 'default')
    {
        // TODO: Implement updateQueue() method.
    }

    public function setQueue($queue)
    {
        // TODO: Implement setQueue() method.
    }

    public function getQueue()
    {
        // TODO: Implement getQueue() method.
    }

    public function hasProcess()
    {
        // TODO: Implement hasProcess() method.
    }

    public function reserveProcess()
    {
        // TODO: Implement reserveProcess() method.
    }

    public function finishProcess(Process &$process, $queue = 'default')
    {
        // TODO: Implement finishProcess() method.
    }

    public function failedProcess(Process &$process, $queue = 'default')
    {
        // TODO: Implement failedProcess() method.
    }

    public function removeProcess($id)
    {
        // TODO: Implement removeProcess() method.
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