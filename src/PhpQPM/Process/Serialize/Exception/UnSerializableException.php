<?php
/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 01:24
 */

namespace PhpQPM\Process\Serialize\Exception;


class UnSerializableException extends \ErrorException
{
    protected $message = 'The Process is not a Closure or ProcessQueueableInterface instance';

}