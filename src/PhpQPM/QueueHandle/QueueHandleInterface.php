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
    public function isConected();
    public function putProcess(Process &$process, $queue = 'default');
    public function getProcess($id);
    public function updateProcess(Process &$process, $queue = 'default');
    public function updateQueue(Process $process, $queue = 'default');
    public function setQueue($queue);
    public function getQueue();
    public function hasProcess();
    public function reserveProcess();
    public function finishProcess(Process &$process, $queue = 'default');
    public function failedProcess(Process &$process, $queue = 'default');
    public function removeProcess($id);
}