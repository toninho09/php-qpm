<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:05
 */

namespace PhpQPM\Process\Serialize;


use PhpQPM\Process\ProcessQueueable;
use PhpQPM\Process\Serialize\Exception\UnSerializableException;

class DetectedType
{
    const CLOSURE = 2;
    const OBJECT = 3;

    /**
     * @param $process
     * @return int
     * @throws \ErrorException
     */
    public function detectType($process){
        if($process instanceof ProcessQueueable) return self::OBJECT;
        if($process instanceof \Closure) return self::CLOSURE;
        throw new UnSerializableException();
    }

    /**
     * @param $process
     * @return bool
     */
    public function isSerializable($process){
        if($process instanceof ProcessQueueable) return true;
        if($process instanceof \Closure) return true;
        return false;
    }

}