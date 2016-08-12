<?php

namespace Tests;
use PHPUnit_Framework_TestCase;

/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 09/08/16
 * Time: 20:44
 */
class ManagerTest extends PHPUnit_Framework_TestCase
{
    protected $manager;
    protected $handle;

    protected function setUp()
    {
        $this->handle = new \PhpQPM\QueueHandle\Sql\SqlQueueHandle();
        $this->handle->connect('mysql:host=localhost;dbname=QueueManager', 'teste', 'teste');
        $this->manager = new \PhpQPM\Manager($this->handle);
        $this->handle->clearQueue();
    }

    public function testConsumeWorker(){

        $worker = $this->manager->createWorker();
        $var1 = 10;
        $process = $this->manager->putProcessOnQueue(function() use($var1){
           return 10 + $var1;
        });

        $process->update();
        $this->assertEquals($process->isWait(),true);
        $this->assertEquals($this->manager->getQueueHandle()->hasProcess(),true);
        $worker->runNextWork();
        $process->update();
        $this->assertEquals($this->manager->getQueueHandle()->hasProcess(),false);
        $this->assertEquals($process->isFinish(),true);
        $this->assertEquals($process->getReturn(),20);
    }

    public function testClassQueueable(){
        $worker = $this->manager->createWorker();

        $process = $this->manager->putProcessOnQueue(new \Tests\SimpleProcess());
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

        $worker = $this->manager->createWorker();
        $process = $this->manager->putProcessOnQueue(new \Tests\VarOnContructorProcess(10));
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
