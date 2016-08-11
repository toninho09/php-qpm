<?php
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 09/08/16
 * Time: 22:12
 */

namespace Tests;


use PhpQPM\Process\ProcessQueueable;

class VarOnContructorProcess extends ProcessQueueable
{
    protected $var;

    /**
     * VarOnContructorProcess constructor.
     * @param $var
     */
    public function __construct($var)
    {
        $this->var = $var;
    }


    public function run()
    {
        return $this->var;
    }
}