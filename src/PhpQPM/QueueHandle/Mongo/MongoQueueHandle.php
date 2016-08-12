<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 11/08/16
 * Time: 22:14
 */

namespace PhpQPM\QueueHandle\Mongo;


use PhpQPM\Process;
use PhpQPM\QueueHandle\QueueHandleInterface;

class MongoQueueHandle implements QueueHandleInterface
{

    /**
     * @return boolean
     */
    public function isConected()
    {
        // TODO: Implement isConected() method.
    }

    /**
     * @param Process $process
     * @param string $queue
     * @return Process
     */
    public function putProcess(Process &$process, $queue = 'default')
    {
        // TODO: Implement putProcess() method.
    }

    /**
     * @param $id
     * @return Process
     */
    public function getProcess($id)
    {
        // TODO: Implement getProcess() method.
    }

    /**
     * @param Process $process
     * @internal param string $queue
     */
    public function updateProcess(Process &$process)
    {
        // TODO: Implement updateProcess() method.
    }

    /**
     * @param Process $process
     * @param string $queue
     * @return void
     */
    public function updateQueue(Process $process, $queue = 'default')
    {
        // TODO: Implement updateQueue() method.
    }

    /**
     * @param $queue
     * @return void
     */
    public function setQueue($queue)
    {
        // TODO: Implement setQueue() method.
    }

    /**
     * @return string
     */
    public function getQueue()
    {
        // TODO: Implement getQueue() method.
    }

    /**
     * @return int
     */
    public function processInQueue()
    {
        // TODO: Implement processInQueue() method.
    }

    /**
     * @return boolean
     */
    public function hasProcess()
    {
        // TODO: Implement hasProcess() method.
    }

    /**
     * @return Process
     */
    public function reserveProcess()
    {
        // TODO: Implement reserveProcess() method.
    }

    /**
     * @param Process $process
     * @param string $queue
     * @return void
     */
    public function finishProcess(Process &$process, $queue = 'default')
    {
        // TODO: Implement finishProcess() method.
    }

    /**
     * @param Process $process
     * @param string $error
     * @param string $queue
     */
    public function failedProcess(Process &$process, $error = '', $queue = 'default')
    {
        // TODO: Implement failedProcess() method.
    }

    /**
     * @param $id
     * @return void
     */
    public function removeProcess($id)
    {
        // TODO: Implement removeProcess() method.
    }
}