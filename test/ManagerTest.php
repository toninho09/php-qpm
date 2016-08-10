<?php

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 09/08/16
 * Time: 20:44
 */
class ManagerTest extends PHPUnit_Framework_TestCase
{
    public function testConsumeWorker(){
        $handle = new \PhpQPM\QueueHandle\Sql\SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $manager = new \PhpQPM\Manager($handle);
        $handle->clearQueue();
        $worker = $manager->createWorker();

        $process = $manager->putProcessOnQueue(function(){
           return '10';
        });

        $process->update();
        $this->assertEquals($process->isWait(),true);
        $this->assertEquals($manager->getQueueHandle()->hasProcess(),true);
        $worker->runNextWork();
        $process->update();
        $this->assertEquals($manager->getQueueHandle()->hasProcess(),false);
        $this->assertEquals($process->isFinish(),true);
        $this->assertEquals($process->getReturn(),'10');

    }
}
