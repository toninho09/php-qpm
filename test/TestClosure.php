<?php

/**
 * Created by PhpStorm.
 * User: Zenner
 * Date: 06/08/2016
 * Time: 00:29
 */
class TestClosure extends PHPUnit_Framework_TestCase
{
    public function testSerialize(){
        $serialize = new \PhpQPM\Process('testando essa porra');
        $serialize = serialize($serialize);
        $unserialized = unserialize($serialize);
        $this->assertEquals($unserialized instanceof \PhpQPM\Process , true);
    }

    public function testClosure(){
        $serialize = new \SuperClosure\Serializer();
        $simpleVarSerialized = $serialize->serialize(function(){});
        $simpleVarUnSerialized = $serialize->unserialize($simpleVarSerialized);
        $this->assertEquals($simpleVarUnSerialized instanceof Closure,true);
    }
}
