<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:11
 */

namespace PhpQPM\Process;


use PhpQPM\Process;

abstract class ProcessQueueable
{
    /**
     * @var Process
     */
    protected $process;

    /**
     * @return Process
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param Process $process
     */
    public function setProcess(&$process)
    {
        $this->process = $process;
    }

    abstract public function run();

    public function onFailed()
    {

    }

    public function retry(){
        $this->process->setReserved(0);
        $this->process->setReservedAt(null);
        $this->process->setFinishAt(null);
        $this->process->setReturn(null);
        $this->process->setError(null);
        $this->process->updateQueue();
    }

}