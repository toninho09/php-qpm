<?php
namespace Tests;
use PhpQPM\Process;

/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 02:29
 */
class SqliteSqlQueueHandleTest extends \PHPUnit_Framework_TestCase
{

    protected $manager;
    protected $handle;

    protected function setUp()
    {
        $this->handle = new \PhpQPM\QueueHandle\Sql\SqliteQueueHandle();
        $this->handle->connect('sqlite::memory:');
        $this->manager = new \PhpQPM\Manager($this->handle);
        $this->handle->clearQueue();
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
        $process2 = $this->handle->getProcess($process->getId());
        $this->assertEquals($process->getAttempts(),$process2->getAttempts());
    }

    public function testHasProcess(){
        $process = new \PhpQPM\Process('teste',$this->handle);
        $process->setType(1);
        $process = $this->handle->putProcess($process);
        $this->assertEquals($this->handle->hasProcess(),true);
        $this->handle->clearQueue();
        $this->assertEquals($this->handle->hasProcess(),false);
    }

    public function testReservProcess(){
        $process = new \PhpQPM\Process('teste',$this->handle);
        $process->setType(1);
        $process = $this->handle->putProcess($process);
        $this->assertEquals($this->handle->hasProcess(),true);
        $processReserved = $this->handle->reserveProcess();
        $this->assertEquals($this->handle->hasProcess(),false);
    }

    public function testFinishProcess(){
        $process = new \PhpQPM\Process('teste',$this->handle);
        $process->setType(1);

        /** @var Process $process */
        $process = $this->handle->putProcess($process);
        $this->assertEquals($this->handle->hasProcess(),true);
        $processReserved = $this->handle->reserveProcess();
        $processReserved->setReturn('10');
        $this->handle->finishProcess($processReserved);
        $this->assertEquals($process->update()->getReturn(),'10');
    }

    public function testCreateTable(){
        $this->handle->createTable();
        if($this->handle->checkTable()){
            $this->handle->droptable();
        }
        $this->assertEquals($this->handle->checkTable(),false);
        $this->handle->createTable();
        $this->assertEquals($this->handle->checkTable(),true);
    }

    public function testProcess(){

    }
}
