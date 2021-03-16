<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace vaniacarta74\Scarichi;

/**
 * Description of Accessor
 *
 * @author Vania
 */
class Accessor
{
    /**
     * @param string $name
     * @param array $arguments
     * @return boolean|mixed
     * @throws \Exception
     */
    public function __call(string $name, array $arguments)
    {
        try {
            $method = substr($name, 0, 3);
            $property = lcfirst(substr($name, 3));            
            switch ($method) {
                case 'set':
                    $response = $this->setAccessor($property, $arguments);
                    break;
                case 'get':
                    $response = $this->getAccessor($property);
                    break;
                default:
                    throw new \Exception('Nome metodo accessorio errato');
                    break;
            }
            if (isset($response) && $response !== false) {
                return $response;
            } else {
                throw new \Exception('Funzione inesistente');
            }            
        } catch (\Exception $e) {
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;
        }        
    }
    
    /**
     * @param string $property
     * @param array $arguments
     * @return boolean
     * @throws \Exception
     */
    public function setAccessor(string $property, array $arguments)
    {
        try {
            $response = false;
            if (property_exists($this, $property)) {
                $this->$property = $arguments[0];
                $response = true;
            }
            return $response;
        // @codeCoverageIgnoreStart
        } catch (\Exception $e) {
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
    
    /**
     * @param string $property
     * @return boolean|mixed
     * @throws \Exception
     */
    public function getAccessor(string $property)
    {
        try {
            if (property_exists($this, $property)) {
                $response = $this->$property;
            } else {
                $response = false;
            }
            return $response;
        // @codeCoverageIgnoreStart
        } catch (\Exception $e) {
            Error::printErrorInfo(__FUNCTION__, Error::debugLevel());
            throw $e;
        }
        // @codeCoverageIgnoreEnd
    }
}
