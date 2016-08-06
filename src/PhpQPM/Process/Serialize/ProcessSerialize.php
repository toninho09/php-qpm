<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:31
 */

namespace PhpQPM\Process\Serialize;


interface ProcessSerialize
{
    public function serialize($process);
    public function unSerialize($process);
}