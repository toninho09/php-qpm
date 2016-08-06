<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:35
 */

namespace PhpQPM\Process\Serialize;


use SuperClosure\Serializer;

class ClosureSerialize implements ProcessSerialize
{

    /**
     * @param $process
     * @return string
     */
    public function serialize($process)
    {
        $serialize = new Serializer();
        return $serialize->serialize($process);
    }

    public function unSerialize($process)
    {
        $serialize = new Serializer();
        return $serialize->unserialize($process);
    }
}