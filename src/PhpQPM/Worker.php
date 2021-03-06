<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 09/08/16
 * Time: 20:07
 */

namespace PhpQPM;


use PhpQPM\Process\ProcessQueueable;

class Worker
{

    /**
     * @var Manager
     */
    protected $manager;
    protected $waitQueue;

    /**
     * @var Process
     */
    protected $actualProcess;

    /**
     * Worker constructor.
     * @param Manager $manager
     */
    public function __construct(Manager $manager)
    {
        $this->manager = $manager;
        $this->waitQueue = 1;
    }

    private function reserve(){
        $this->actualProcess = $this->manager->reserveProcess();
        return $this->actualProcess;
    }

    public function work(){
        while(true){
            if(!$this->manager->hasProcess()){
                sleep($this->waitQueue);
            }else{
                $this->runNextWork();
            }
        }
    }

    public function runNextWork(){
        try{
            if(!$this->manager->hasProcess())return;
            $this->reserve();
            $process = $this->manager->getUnSerializeProcess($this->actualProcess);
            if($process instanceof \Closure) $this->actualProcess->setReturn($process());
            if($process instanceof ProcessQueueable) {
                $process->setProcess($this->actualProcess);
                try{
                    $this->actualProcess->setReturn($process->run());
                }catch (\Exception $ex){
                    $process->onFailed();
                    throw $ex;
                }
            }
            $this->manager->getQueueHandle()->finishProcess($this->actualProcess);
        }catch (\Exception $ex){
            if(empty($this->actualProcess))return;
            $this->manager->getQueueHandle()->failedProcess($this->actualProcess,$ex->getMessage(),$this->actualProcess->getQueue());
        }
    }

    /**
     * @return mixed
     */
    public function getWaitQueue()
    {
        return $this->waitQueue;
    }

    /**
     * @param mixed $waitQueue
     */
    public function setWaitQueue($waitQueue)
    {
        $this->waitQueue = $waitQueue;
    }

}