<?php
use PhpQPM\QueueHandle\Sql\SqlQueueHandle;

/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 02:29
 */
class SqlQueueHandleTest extends PHPUnit_Framework_TestCase
{
    public function testIsConected(){
        $handle = new SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $this->assertEquals($handle->isConected(),true);
    }

    public function testPutProcess(){
        $handle = new SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $process = new \PhpQPM\Process('teste',$handle);
        $process->setType(1);
        $process = $handle->putProcess($process);
        $this->assertEquals($handle->isConected(),true);
    }

    public function testUpdate(){
        $handle = new SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $process = new \PhpQPM\Process('teste',$handle);
        $process->setType(1);
        $process = $handle->putProcess($process);
        $process->addAttempts();
        $handle->updateQueue($process);
        $process2 = $handle->getProcess($process->getId());
        $this->assertEquals($process->getAttempts(),$process2->getAttempts());
    }

    public function testHasProcess(){
        $handle = new SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $process = new \PhpQPM\Process('teste',$handle);
        $process->setType(1);
        $process = $handle->putProcess($process);
        $this->assertEquals($handle->hasProcess(),true);
        $handle->clearQueue();
        $this->assertEquals($handle->hasProcess(),false);
    }
}
