<?php
namespace Ec\Ecb;

class ConverterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * round floats to this number of decimal before assert their equality
     */
    const ASSERT_FLOAT_PRECISION = 0.001;
    
    /**
     * @var Converter
     */
    private $object;
    
    public function setUp()
    {
        $mockParser = $this->getMockBuilder(__NAMESPACE__ . '\\Parser')
            ->disableOriginalConstructor()
            ->setMethods(array('offsetGet', 'offsetExists'))
            ->getMock();
        
        $mockParser->expects($this->any())
            ->method('offsetExists')
            ->will($this->returnValueMap(array(
                array('GBP', true),
                array('USD', true),
                array('INVALID', false),
                array('INVALID2', false),
            )));
        
        $mockParser->expects($this->any())
            ->method('offsetGet')
            ->will($this->returnValueMap(array(
                array('GBP', 0.87890),
                array('USD', 1.3304),
            )));
        
        $this->object = new Converter($mockParser);
    }
    
    public function provider()
    {
        return array(
            array(1, 'GBP', 'EUR', 1.137785),
            array(1, 'gbp', 'eur', 1.137785),
            array(1, 'EUR', 'GBP', 0.87890),
            array(10, 'EUR', 'GBP', 8.7890),
            array(1, 'EUR', 'USD', 1.3304),
            // two-steps via eur conversion
            array(1, 'GBP', 'USD', 1.513709164), // = GBP->EUR * EUR->USD exhange rates
            array(1, 'USD', 'USD', 1),
            array(1, 'EUR', 'EUR', 1),
            array(0, 'INVALID', 'INVALID', 0),
        );
    }
    
    /**
     * @dataProvider provider
     */
    public function testConvert($value, $from, $to, $expectedValue)
    {
        $actual = $this->object->convert($value, $from, $to);
        
        $this->assertTrue(abs($expectedValue - $actual) < self::ASSERT_FLOAT_PRECISION, "expected: $expectedValue, actual: $actual");
    }
    
    public function providerInvalid()
    {
        return array(
            array(1, 'EUR', 'INVALID'),
            array(1, 'INVALID', 'EUR'),
            array(1, 'USD', 'INVALID'),
            array(1, 'INVALID', 'USD'),
            array(1, 'INVALID', 'INVALID2'),
        );
    }
    
    /**
     * @dataProvider providerInvalid
     * @expectedException \InvalidArgumentException
     */
    public function testConvertInvalidValues($value, $from, $to)
    {
        $actual = $this->object->convert($value, $from, $to);
    }
}