<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:22
 */

namespace PhpQPM\Process\Serialize\Handle;


use PhpQPM\Process\ProcessQueueable;
use PhpQPM\Process\Serialize\ClosureSerialize;
use PhpQPM\Process\Serialize\DetectedType;
use PhpQPM\Process\Serialize\Exception\UnSerializableException;
use PhpQPM\Process\Serialize\ObjectSerialize;

class SerializeHandle
{
    protected $detector;

    /**
     * SerializeHandle constructor.
     */
    public function __construct()
    {
        $this->detector = new DetectedType();
    }

    /**
     * @param $process
     * @return ProcessQueueable| \Closure
     * @throws UnSerializableException
     */
    public function serializeProcess($process){
        switch ($this->detector->detectType($process)){
            case DetectedType::OBJECT :
                $serialize = new ObjectSerialize();
                return $serialize->serialize($process);
            case DetectedType::CLOSURE :
                $serialize = new ClosureSerialize();
                return $serialize->serialize($process);
                break;
        }
        throw new UnSerializableException();
    }

    /**
     * @param $process
     * @param $type
     * @return string
     * @throws UnSerializableException
     */
    public function unserializeProcess($process, $type){
        switch ($type){
            case DetectedType::OBJECT :
                $serialize = new ObjectSerialize();
                return $serialize->unSerialize($process);
            case DetectedType::CLOSURE :
                $serialize = new ClosureSerialize();
                return $serialize->unSerialize($process);
                break;
        }
        throw new UnSerializableException();
    }

    /**
     * @param $process
     * @return int
     */
    public function getType($process){
        return $this->detector->detectType($process);
    }

    public function isSerializable($process){
        return $this->detector->isSerializable($process);
    }


}