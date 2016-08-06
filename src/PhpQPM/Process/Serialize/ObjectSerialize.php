<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:30
 */

namespace PhpQPM\Process\Serialize;


class ObjectSerialize implements ProcessSerialize
{

    /**
     * @param $process
     * @return string
     */
    public function serialize($process)
    {
        return serialize($process);
    }

    /**
     * @param $process
     * @return mixed
     */
    public function unSerialize($process)
    {
        return unserialize($process);
    }
}