<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 12/08/16
 * Time: 22:09
 */

namespace Tests;


use PhpQPM\QueueHandle\Sync\SyncQueueHandle;

class SyncQueueHandleTest extends \PHPUnit_Framework_TestCase
{

    protected $manager;
    protected $handle;

    protected function setUp()
    {
        $this->handle = new SyncQueueHandle();
        $this->manager = new \PhpQPM\Manager($this->handle);
    }

    public function testIsConected(){
        $this->assertEquals($this->handle->isConected(),true);
    }

    public function testPutProcess(){
        $process = new \PhpQPM\Process('teste',$this->handle);
        $process->setType(1);
        $process = $this->handle->putProcess($process);
        $this->assertEquals($this->handle->isConected(),true);
    }

    public function testUpdate(){
        $process = new \PhpQPM\Process('teste',$this->handle);
        $process->setType(1);
        $process = $this->handle->putProcess($process);
        $process->addAttempts();
        $this->handle->updateQueue($process);
        $this->assertEquals($process->getAttempts(),1);
    }

    public function testHasProcess(){
        $process = new \PhpQPM\Process('teste',$this->handle);
        $process->setType(1);
        $process = $this->handle->putProcess($process);
        $this->assertEquals($this->handle->hasProcess(),false);
    }

    public function testReservProcess(){
        $process = new \PhpQPM\Process('teste',$this->handle);
        $process->setType(1);
        $process = $this->handle->putProcess($process);
        $processReserved = $this->handle->reserveProcess();
        $this->assertEquals($this->handle->hasProcess(),false);
    }

    public function testFinishProcess(){
        $process = new \PhpQPM\Process('10',$this->handle);
        $process->setType(1);

        /** @var Process $process */
        $process = $this->handle->putProcess($process);
        $this->handle->finishProcess($process);
        $this->assertEquals($process->update()->getReturn(),'10');
    }


}

