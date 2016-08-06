<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:05
 */

namespace PhpQPM\Process\Serialize;


use PhpQPM\Process\ProcessQueueableInterface;
use PhpQPM\Process\Serialize\Exeption\UnSerializableException;

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
        if($process instanceof ProcessQueueableInterface) return self::OBJECT;
        if($process instanceof \Closure) return self::CLOSURE;
        throw new UnSerializableException();
    }

    /**
     * @param $process
     * @return bool
     */
    public function isSerializable($process){
        if($process instanceof ProcessQueueableInterface) return true;
        if($process instanceof \Closure) return true;
        return false;
    }

}