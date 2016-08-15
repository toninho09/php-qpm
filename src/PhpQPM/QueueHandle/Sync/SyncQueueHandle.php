<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 12/08/16
 * Time: 21:55
 */

namespace PhpQPM\QueueHandle\Sync;


use PhpQPM\Process;
use PhpQPM\QueueHandle\QueueHandleInterface;

class SyncQueueHandle implements QueueHandleInterface
{
    protected $serialize;

    /**
     * SyncQueueHandle constructor.
     * @param $serialize
     */
    public function __construct()
    {
        $this->serialize = new Process\Serialize\Handle\SerializeHandle();
    }


    /**
     * @return boolean
     */
    public function isConected()
    {
        return true;
    }

    /**
     * @param Process $process
     * @param string $queue
     * @return Process
     */
    public function putProcess(Process &$process, $queue = 'default')
    {
        try{
            if($this->serialize->isSerializable($process->getProcess())) {
                $this->runProcess($process);
            }else{
                $process->setReturn($process->getProcess());
            }
            $this->finishProcess($process);
        }catch (\Exception $ex){
            if(empty($process))return;
            $this->failedProcess($process,$ex->getMessage());
        }
        return $process;
    }

    /**
     * @param $id
     * @return Process
     */
    public function getProcess($id)
    {
        return null;
    }

    /**
     * @param Process $process
     * @internal param string $queue
     */
    public function updateProcess(Process &$process)
    {
        return $process;
    }

    /**
     * @param Process $process
     * @param string $queue
     * @return void
     */
    public function updateQueue(Process $process, $queue = 'default')
    {
        return;
    }

    /**
     * @param $queue
     * @return void
     */
    public function setQueue($queue)
    {
        return;
    }

    /**
     * @return string
     */
    public function getQueue()
    {
        return 'default';
    }

    /**
     * @return int
     */
    public function processInQueue()
    {
        return 0;
    }

    /**
     * @return boolean
     */
    public function hasProcess()
    {
        return false;
    }

    /**
     * @return Process
     */
    public function reserveProcess()
    {
        return null;
    }

    /**
     * @param Process $process
     * @param string $queue
     * @return void
     */
    public function finishProcess(Process &$process, $queue = 'default')
    {
        $process->setFinishAt(date('Y-m-d H:i:s'));
    }

    /**
     * @param Process $process
     * @param string $error
     * @param string $queue
     */
    public function failedProcess(Process &$process, $error = '', $queue = 'default')
    {
        $process->setError($error);
        $process->setFinishAt(date('Y-m-d H:i:s'));
    }

    /**
     * @param $id
     * @return void
     */
    public function removeProcess($id)
    {
        return;
    }

    /**
     * @param Process $process
     * @throws \Exception
     */
    private function runProcess(Process &$process)
    {
        $processWork = $this->serialize->unserializeProcess($process->getProcess(), $process->getType());
        if ($processWork instanceof \Closure) $process->setReturn($processWork());
        if ($processWork instanceof ProcessQueueable) {
            $processWork->setProcess($process);
            try {
                $process->setReturn($processWork->run());
            } catch (\Exception $e) {
                $processWork->onFailed();
                throw $e;
            }
        }
    }
}