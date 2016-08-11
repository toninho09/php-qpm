<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 05/08/2016
 * Time: 22:46
 */

namespace PhpQPM\QueueHandle;

use PhpQPM\Process;

interface QueueHandleInterface
{
    /**
     * @return boolean
     */
    public function isConected();

    /**
     * @param Process $process
     * @param string $queue
     * @return Process
     */
    public function putProcess(Process &$process, $queue = 'default');

    /**
     * @param $id
     * @return Process
     */
    public function getProcess($id);

    /**
     * @param Process $process
     * @internal param string $queue
     */
    public function updateProcess(Process &$process);

    /**
     * @param Process $process
     * @param string $queue
     * @return void
     */
    public function updateQueue(Process $process, $queue = 'default');

    /**
     * @param $queue
     * @return void
     */
    public function setQueue($queue);

    /**
     * @return string
     */
    public function getQueue();


    /**
     * @return int
     */
    public function processInQueue();

    /**
     * @return boolean
     */
    public function hasProcess();

    /**
     * @return Process
     */
    public function reserveProcess();

    /**
     * @param Process $process
     * @param string $queue
     * @return void
     */
    public function finishProcess(Process &$process, $queue = 'default');

    /**
     * @param Process $process
     * @param string $error
     * @param string $queue
     */
    public function failedProcess(Process &$process,$error = '', $queue = 'default');

    /**
     * @param $id
     * @return void
     */
    public function removeProcess($id);
}