<?php
namespace Ec\Ecb;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Parser
     */
    private $object;
    
    public function setUp()
    {
        $this->object = new Parser(file_get_contents(__DIR__.'/ecb.xml'));
    }
    
    public function testFilter()
    {
        $this->assertEquals('1.3304', $this->object['USD']);
        $this->assertEquals('11.7421', $this->object['ZAR']);
        $this->assertFalse(isset($this->object['EUR']));
    }
}