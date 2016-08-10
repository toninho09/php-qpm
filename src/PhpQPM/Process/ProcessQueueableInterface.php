<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:11
 */

namespace PhpQPM\Process;


abstract class ProcessQueueableInterface
{
    protected $process;

    /**
     * @return mixed
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param mixed $process
     */
    public function setProcess(&$process)
    {
        $this->process = $process;
    }


    abstract public function run();

}