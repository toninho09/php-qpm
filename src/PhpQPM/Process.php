<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 05/08/2016
 * Time: 22:49
 */

namespace PhpQPM;


use PhpQPM\QueueHandle\QueueHandleInterface;

class Process
{
    private $id;
    private $process;
    private $attempts;
    private $queue;
    private $reserved;
    private $reservedAt;
    private $error;
    private $return;
    private $finishAt;
    private $queueHandle;
    private $type;
    private $createdAt;

    const WAIT = 0;
    const RESERVED = 1;
    const RUNNING = 2;
    const FINISH = 3;

    /**
     * AbstractProcess constructor.
     * @param $process
     * @param QueueHandleInterface $handle
     */
    public function __construct($process, QueueHandleInterface &$handle = null)
    {
        $this->process = $process;
        $this->queueHandle = $handle;
    }

    /**
     * @throws \ErrorException
     */
    public function update()
    {
        if ($this->queueHandle == null) {
            throw new \ErrorException("The Queue Handle is not defined");
        }
        $this->queueHandle->updateProcess($this);
        return $this;
    }

    public function updateQueue(){
        if ($this->queueHandle == null) {
            throw new \ErrorException("The Queue Handle is not defined");
        }
        $this->queueHandle->updateQueue($this,$this->queue);
    }

    /**
     * @return int
     */
    public function getStatus()
    {
        if ($this->isFinish()) return self::FINISH;
        if ($this->isRunning()) return self::RUNNING;
        if ($this->isReserved()) return self::RESERVED;
        return self::WAIT;
    }

    /**
     * @return bool
     */
    public function isWait(){
        return !$this->isFinish() && !$this->isRunning() && !$this->isReserved();
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getProcess()
    {
        return $this->process;
    }

    /**
     * @param mixed $handle
     */
    public function setProcess($handle)
    {
        $this->process = $handle;
    }

    /**
     * @return mixed
     */
    public function getAttempts()
    {
        return $this->attempts;
    }

    /**
     * @param mixed $attempts
     */
    public function setAttempts($attempts)
    {
        $this->attempts = $attempts;
    }

    /**
     *
     */
    public function addAttempts()
    {
        $this->attempts++;
    }

    /**
     * @return bool
     */
    public function isRunning()
    {
        return !empty($this->reservedAt) && !empty($this->finishAt);
    }

    /**
     * @return mixed
     */
    public function isReserved()
    {
        return $this->reserved;
    }

    /**
     * @param mixed $reserved
     */
    public function setReserved($reserved)
    {
        $this->reserved = (boolean)$reserved;
    }

    /**
     * @return mixed
     */
    public function getReservedAt()
    {
        return $this->reservedAt;
    }

    /**
     * @param mixed $reservedAt
     */
    public function setReservedAt($reservedAt)
    {
        $this->reservedAt = $reservedAt;
    }

    /**
     *
     */
    public function reserve()
    {
        $this->setReservedAt(date('Y-m-d H:i:s'));
        $this->setReserved(true);
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param mixed $error
     */
    public function setError($error)
    {
        $this->error = $error;
    }

    /**
     * @return bool
     */
    public function hasError()
    {
        return (boolean)$this->getError();
    }

    /**
     * @return mixed
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param mixed $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return mixed
     */
    public function getReturn()
    {
        return $this->return;
    }

    /**
     * @param mixed $return
     */
    public function setReturn($return)
    {
        $this->return = $return;
    }

    /**
     * @return mixed
     */
    public function getFinishAt()
    {
        return $this->finishAt;
    }

    /**
     * @param mixed $finishAt
     */
    public function setFinishAt($finishAt)
    {
        $this->finishAt = $finishAt;
    }

    /**
     * @return bool
     */
    public function isFinish()
    {
        return (boolean)$this->getFinishAt();
    }

    /**
     * @return mixed
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param mixed $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return QueueHandleInterface
     */
    public function getQueueHandle()
    {
        return $this->queueHandle;
    }

    /**
     * @param QueueHandleInterface $queueHandle
     */
    public function setQueueHandle($queueHandle)
    {
        $this->queueHandle = $queueHandle;
    }

    /**
     * @return mixed
     */
    public function getQueue()
    {
        return $this->queue;
    }

    /**
     * @param mixed $queue
     */
    public function setQueue($queue)
    {
        $this->queue = $queue;
    }



}