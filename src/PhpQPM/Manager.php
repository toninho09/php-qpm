<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 05/08/2016
 * Time: 22:40
 */

namespace PhpQPM;


use PhpQPM\Process\Handle\Observer;
use PhpQPM\Process\Serialize\Exeption\UnSerializableException;
use PhpQPM\Process\Serialize\Handle\SerializeHandle;
use PhpQPM\QueueHandle\QueueHandleInterface;

class Manager
{
    /**
     * @var QueueHandleInterface
     */
    protected $queueHandle;
    protected $serializeHandle;

    /**
     * Manager constructor.
     */
    public function __construct()
    {
        $this->serializeHandle = new SerializeHandle();
    }

    public function putProcessOnQueue($process,$queue = 'default'){
        if(!$this->serializeHandle->isSerializable($process)) throw new UnSerializableException();
        $processDao = new Process($this->serializeHandle->serializeProcess($process),$this->queueHandle);
        $processDao->setType($this->serializeHandle->getType($process));
        $this->queueHandle->putProcess($processDao,$queue);
        return new Observer($processDao);
    }

    /**
     * @return mixed
     */
    public function getQueueHandle()
    {
        return $this->queueHandle;
    }

    /**
     * @param mixed $queueHandle
     */
    public function setQueueHandle(QueueHandleInterface $queueHandle)
    {
        $this->queueHandle = $queueHandle;
    }

    /**
     * @return SerializeHandle
     */
    public function getSerializeHandle()
    {
        return $this->serializeHandle;
    }

    /**
     * @param SerializeHandle $serializeHandle
     */
    public function setSerializeHandle($serializeHandle)
    {
        $this->serializeHandle = $serializeHandle;
    }


}