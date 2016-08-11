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

        $var1 = 10;
        $process = $manager->putProcessOnQueue(function() use($var1){
           return 10 + $var1;
        });

        $process->update();
        $this->assertEquals($process->isWait(),true);
        $this->assertEquals($manager->getQueueHandle()->hasProcess(),true);
        $worker->runNextWork();
        $process->update();
        $this->assertEquals($manager->getQueueHandle()->hasProcess(),false);
        $this->assertEquals($process->isFinish(),true);
        $this->assertEquals($process->getReturn(),20);
    }

    public function testClassQueueable(){
        $handle = new \PhpQPM\QueueHandle\Sql\SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $manager = new \PhpQPM\Manager($handle);
        $handle->clearQueue();
        $worker = $manager->createWorker();

        $process = $manager->putProcessOnQueue(new \Tests\SimpleProcess());
        $process->update();
        $this->assertEquals($process->isWait(),true);
        $worker->runNextWork();
        $process->update();
        $this->assertEquals($process->isWait(),false);
        $this->assertEquals($process->isReserved(),true);
        $this->assertEquals($process->isFinish(), true);
        $this->assertEquals($process->hasError(),false);
        $this->assertEquals($process->getReturn(),10);

    }

    public function testClassWithVarQueueable(){
        $handle = new \PhpQPM\QueueHandle\Sql\SqlQueueHandle();
        $handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $manager = new \PhpQPM\Manager($handle);
        $handle->clearQueue();
        $worker = $manager->createWorker();

        $process = $manager->putProcessOnQueue(new \Tests\VarOnContructorProcess(10));
        $process->update();
        $this->assertEquals($process->isWait(),true);
        $worker->runNextWork();
        $process->update();
        $this->assertEquals($process->isWait(),false);
        $this->assertEquals($process->isReserved(),true);
        $this->assertEquals($process->isFinish(), true);
        $this->assertEquals($process->hasError(),false);
        $this->assertEquals($process->getReturn(),10);

    }
}
