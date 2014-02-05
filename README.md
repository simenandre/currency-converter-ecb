Currency converter ECB (European central bank)
==============================================

XML Parser (of http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml) and currency converter (from one currency to any other).

Originally made by Elvis Ciotti. This version is a stripped down, that is made with a static function, and without the composer. (Fast commit. I might add it later on.)


Example
```
$currency = Currency::convert(1.5, 'EUR', 'GBP');
```