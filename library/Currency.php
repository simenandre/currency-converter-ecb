<?php

/**
 * Parser of currency rates from european central bank
 */
class Converter
{
    /**
     * @var Parser
     */
    private $parser = array();

    /**
     * @var Url
     * URL to European Central Bank
     */
    private $url = 'http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';
    
    /**
     * @param string $currency
     * 
     * @throws \InvalidArgumentException if the currency does not exist
     */
    private function _checkCurrencyExists($currency)
    {
        if ($currency != 'EUR' && !isset($this->parser[$currency])) {
            throw new \InvalidArgumentException(
                "Unrecognised [$currency] currency, available currencies: "
                . $this->parser->getAvailableCurrencies()
            );
        }
    }
    
    /**
     * Convert a value from a currenty into another
     * 
     * @param float $value
     * @param string $from
     * @param string $to
     * 
     * @return float
     */
    static function convert($value, $from, $to)
    {

        $root = simplexml_load_string($data);
        
        if (!$root) {
            throw new \RuntimeException("cannot parse XML input string");
        }
        foreach ($root->Cube->Cube->Cube as $node) {
            $this->parser[(string)$node['currency']] = (string)$node['rate'];
        }

        $from = strtoupper($from);
        $to = strtoupper($to);
        
        if ($from == $to) {
            return $value;
        }
        
        if ($value == 0) {
            return 0;
        }
        
        $this->_checkCurrencyExists($from);
        $this->_checkCurrencyExists($to);
        
        if ($from == 'EUR') {
            return $this->parser[$to] * $value;
        }
        
        if ($to == 'EUR') {
            return $value / $this->parser[$from];
        }
        
        $valueInEur = $this->convert($value, $from, 'EUR');
        
        return $this->convert($valueInEur, 'EUR', $to);
    }
    
}