<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 00:14
 */

namespace PhpQPM\Process\Handle;


use PhpQPM\Process;

class Observer
{
    protected $process;

    public function __construct(Process $process)
    {
        $this->process = $process;
    }

    public function setProcess(Process $process){
        $this->process = $process;
    }

    public function isRunning(){
        return $this->process->isRunning();
    }

    public function isReserved(){
        return $this->process->isReserved();
    }

    public function isFinish(){
        return $this->process->isFinish();
    }

    public function isWait(){
        return $this->isWait();
    }

    public function getReturn(){
        return $this->process->getReturn();
    }


}