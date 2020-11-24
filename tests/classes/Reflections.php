<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi\tests\classes;

/**
 * Description of Reflections
 *
 * @author Vania
 */
class Reflections
{
    /**
     * @group reflections
     * @coversNothing
     */
    public static function invokeMethod(object &$object, string $methodName, ?array $args = [])
    {
        $class = new \ReflectionClass($object);
        
        $method = $class->getMethod($methodName);
        $method->setAccessible(true);
        
        return $method->invokeArgs($object, $args);
    }
    
    /**
     * @group reflections
     * @coversNothing
     */
    public static function getProperty(object &$object, string $propertyName)
    {
        $class = new \ReflectionClass($object);
        
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);
        
        return $property->getValue($object);
    }
    
    /**
     * @group reflections
     * @coversNothing
     */
    public static function setProperty(object &$object, string $propertyName, $value)
    {
        $class = new \ReflectionClass($object);
        
        $property = $class->getProperty($propertyName);
        $property->setAccessible(true);
        
        return $property->setValue($object, $value);
    }
}
