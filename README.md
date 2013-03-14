Currency converter ECB (European central bank)
==============================================

XML Parser (of http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml) and currency converter (from one currency to any other).

Example
	
	$parser = new Parser(file_get_content('http://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml')); //cache this object
        $converter = new Converter($parser);
 	echo $converter->convert(1.5, 'EUR', 'GBP'); //convert 1.5 EUR into GBP


