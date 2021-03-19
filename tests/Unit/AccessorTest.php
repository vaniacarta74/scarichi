<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\Unit;

use PHPUnit\Framework\TestCase;
use vaniacarta74\Scarichi\tests\classes\Extender;
use vaniacarta74\Scarichi\tests\classes\Reflections;

/**
 * Description of AccessorTest
 *
 * @author Vania
 */
class AccessorTest extends TestCase
{
    private $extender;
    
    protected function setUp() : void
    {
        $this->extender = new Extender('pippo');
    }
    
    protected function tearDown() : void
    {
        $this->extender = null;
    }
    
    /**
     * @group accessor
     * @coversNothing
     */
    public function callProvider() : array
    {
        $data = [
            'set' => [
                'args' => [
                    'name' => 'setProperty',
                    'arguments' => [
                        '01/01/2020'
                    ]
                ],
                'expected' => true
            ],
            'get' => [
                'args' => [
                    'name' => 'getProperty',
                    'arguments' => []
                ],
                'expected' => 'pippo'
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group accessor
     * @covers \vaniacarta74\Scarichi\Accessor::__call
     * @covers \vaniacarta74\Scarichi\Accessor::setAccessor
     * @covers \vaniacarta74\Scarichi\Accessor::getAccessor
     * @dataProvider callProvider
     */
    public function testCallEquals(array $args, $expected) : void   
    {        
        $property = lcfirst(preg_replace('/^(set|get)/', '', $args['name']));
        
        Reflections::setProperty($this->extender, $property, 'pippo');
        
        $actual = Reflections::invokeMethod($this->extender, '__call', $args);
        
        $this->assertEquals($expected, $actual);
    }
    
    /**
     * @group accessor
     * @coversNothing
     */
    public function callExceptionProvider() : array
    {
        $data = [
            'no method' => [
                'args' => [
                    'name' => 'getPippo',
                    'arguments' => [
                        'pippo'
                    ]
                ]
            ],
            'wrong method' => [
                'args' => [
                    'name' => 'alias',
                    'arguments' => []
                ]
            ]
        ];
        
        return $data;
    }
    
    /**
     * @group accessor
     * @covers \vaniacarta74\Scarichi\Accessor::__call
     * @covers \vaniacarta74\Scarichi\Accessor::setAccessor
     * @covers \vaniacarta74\Scarichi\Accessor::getAccessor
     * @dataProvider callExceptionProvider
     */
    public function testCallException(array $args) : void
    {
        $this->expectException(\Exception::class);
        
        Reflections::invokeMethod($this->extender, '__call', $args);
    }
}
