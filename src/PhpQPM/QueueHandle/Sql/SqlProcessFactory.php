<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 08/08/16
 * Time: 19:49
 */

namespace PhpQPM\QueueHandle\Sql;


use PhpQPM\Process;
use PhpQPM\QueueHandle\QueueHandleInterface;

class SqlProcessFactory
{
    public static function create(&$object,QueueHandleInterface $queueHandle){
        $process = new Process($object->process,$queueHandle);
        $process->setId($object->id);
        $process->setAttempts($object->attempts);
        $process->setCreatedAt($object->create_at);
        $process->setError($object->error);
        $process->setFinishAt($object->finish_at);
        $process->setReserved($object->reserved);
        $process->setReservedAt($object->reserved_at);
        $process->setReturn($object->return);
        $process->setProcess($object->process);
        $process->setType($object->type);
        $process->setQueue($object->queue);
        return $process;
    }

    public static  function update(Process &$process,&$object){
        $process->setId($object->id);
        $process->setAttempts($object->attempts);
        $process->setCreatedAt($object->create_at);
        $process->setError($object->error);
        $process->setFinishAt($object->finish_at);
        $process->setReserved($object->reserved);
        $process->setReservedAt($object->reserved_at);
        $process->setReturn($object->return);
        $process->setProcess($object->process);
        $process->setType($object->type);
        $process->setQueue($object->queue);
        return $process;
    }
}