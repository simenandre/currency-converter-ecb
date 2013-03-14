<?php
namespace Ec\Ecb;

/**
 * Parser of currency rates from european central bank
 * 
 * $parser = new Parser(...xml string...);
 * echo $parser['USD'] // 1.3304 (at the time of writing)
 * 
 * for values, use
 * http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml
 */
class Parser implements \ArrayAccess
{
    /**
     * @var array
     */
    protected $_currencyToRate = array();
    
    /**
     * @param string $data
     */
    public function __construct($data)
    {
        $root = simplexml_load_string($data);
        
        if (!$root) {
            throw new \RuntimeException("cannot parse XML input string");
        }
        foreach ($root->Cube->Cube->Cube as $node) {
            $this->_currencyToRate[(string)$node['currency']] = (string)$node['rate'];
        }
    }
    
    /**
     * @return array
     */
    public function getAvailableCurrencies()
    {
        return array_keys($this->_currencyToRate);
    }
    
    public function offsetExists($offset)
    {
        return isset($this->_currencyToRate[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->_currencyToRate[$offset];
    }

    public function offsetSet($offset, $value)
    {
        throw new \RuntimeException('Read only');
    }

    public function offsetUnset($offset)
    {
        throw new \RuntimeException('Read only');
    }
    
}