<?php

namespace Tests;
/**
 * Created by PhpStorm.
 * User: antonio
 * Date: 09/08/16
 * Time: 22:02
 */
class SimpleProcess extends \PhpQPM\Process\ProcessQueueable
{

    public function run()
    {
        return 10;
    }
}